<?php

namespace App\Services;

use App\Entity\Code;
use App\Entity\Company;
use App\Entity\Profile;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Mailer\TwigSwiftMailer;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ExcelManagerService
{
    /* @var Spreadsheet */
    private $objPHPExcel;

    private $company;

    private $role;

    /**
     * @var UserManager
     */
    private $candidateManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $emails;

    private array $messages;

    private string $sendMail;

    private $profile;

    private TwigSwiftMailer $mailer;

    private $params;

    private UserPasswordEncoderInterface $passwordEncoder;

    private UserGroup $group;

    /**
     * @param UserManagerInterface $userManager
     * @param EntityManagerInterface $entityManager
     * @param TwigSwiftMailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param PasswordGenerator $passwordGenerator
     * @param ParameterBagInterface $params
     */
    public function __construct(

        EntityManagerInterface $entityManager,
        TwigSwiftMailer $mailer,
        UserPasswordEncoderInterface $passwordEncoder,
        ParameterBagInterface $params
    ) {

        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->emails = [];
        $this->messages = [];
        $this->mailer = $mailer;
        $this->params = $params;
    }

    /**
     * @param $archivo
     *
     * @return $this
     *
     * @throws Exception
     */
    public function initializeArchivo($archivo, $company, $sendMail, $role, UserGroup $group): self
    {
        $inputFileType = IOFactory::identify($archivo);

        $objReader = IOFactory::createReader($inputFileType);
        $this->objPHPExcel = $objReader->load($archivo);
        $this->company = $this->entityManager->getRepository(Company::class)->find($company);
        $this->role = $this->entityManager->getRepository(Role::class)->find($role);
        $this->sendMail = $sendMail;
        $this->group = $group;
        return $this;
    }

    public function procesarExcel(): array
    {
        $worksheet = $this->objPHPExcel->getSheet($this->objPHPExcel->getActiveSheetIndex());

        $users = $this->readExcel($worksheet);

        foreach ($users as $user) {
            $this->persist($user);
        }
        $this->entityManager->flush();
        // $this->exportarExcel($users);
        return ['messages' => $this->messages, 'cant_users' => count($users), 'users' => $users];
    }

    public function readExcel(Worksheet $worksheet): array
    {
        $users = [];

        /* Itero por cada una de las filas asociadas */
        foreach ($worksheet->getRowIterator() as $row) {
            if (($indice = $row->getRowIndex()) < 4) {
                continue;
            }

            /* Compruebo que ya la cedula del trabajador no exista en base de datos */

            $name = $worksheet->getCell('A' . $indice)->getValue();
            $apellidos = $worksheet->getCell('B' . $indice)->getValue();
            $cedula = $worksheet->getCell('C' . $indice)->getValue();
            $email = $worksheet->getCell('D' . $indice)->getValue();


            /* Si no hay nombre ni apellidos ni cedula lo tomo como fin de linea y retorno el array de trabajadores*/

            if ($this->checkIfNull($cedula, $indice) && $this->checkIfNull($name, $indice) && $this->checkIfNull($email, $indice)) {
                return $users;
            }

            $user = null;

            if (!$this->checkIfNull($cedula, $indice, true, 'Cédula')) {
                $user = $this->checkIfAlreadyExist($cedula, $indice, 'cédula', 'findOneByCedula');
            }

            $users[$cedula] = $this->convertRowToUserObjectinDatosPersonales($row, $user);
        }

        return $users;
    }

    /**
     * @param $value
     * @param null   $indice
     * @param bool   $show_message
     * @param string $message_attribute
     */
    public function checkIfNull($value, $indice = null, $show_message = false, $message_attribute = ''): bool
    {
        if (null === $value || '' === $value) {
            if ($show_message) {
                $this->messages[] = sprintf('La fila %s del documento contiene el valor: %s en blanco.', $indice - 1, $message_attribute);
            }

            return true;
        }

        return false;
    }

    /**
     * @param $value
     * @param $indice
     * @param string $message_attribute
     * @param string $find_attribute
     */
    public function checkIfAlreadyExist($value, $indice, $message_attribute = '', $find_attribute = '')
    {
        $user = $this->entityManager->getRepository(User::class)->$find_attribute($value);
        if (null !== $user) {
            return $user;
        }

        return null;
    }

    public function convertRowToUserObjectinDatosPersonales(Row $row, $user): User
    {
        $candidate = $user ?? new User();
        $columns = $this->arrayColumnValuesDatosPersonales();

        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);

        foreach ($cellIterator as $cell) {
            if (null !== $cell) {
                $column = $cell->getColumn();
                $value = trim($cell->getCalculatedValue());

                $method = $columns[$column];
                $candidate->$method($value);
                $this->setExtraFields($candidate);
            }
        }

        return $candidate;
    }

    public function arrayColumnValuesDatosPersonales(): array
    {
        return $columns = [
            'A' => 'setName',
            'B' => 'setfirstName',
            'C' => 'setCedula',
            'D' => 'setEmail',
        ];
    }

    public function setExtraFields(User $user)
    {
        $user->setUsername($user->getCedula());
        $user->addRolesObject($this->role);
        $user->setCompany($this->company);
        $user->addUserGroup($this->group);
        $this->group->addUser($user);

        $code = new Code();
        $code->setCode(uniqid())
            ->setBook($this->group->getCourse())
            ->setCompany($this->company)
            ->setStarDate($this->group->getStartDate())
            ->setUnlimited(true)
            ->setUser($user)
            ;

        $this->entityManager->persist($code);
    }

    /**
     * @param $candidate
     */
    public function persist(User $user): void
    {

        $user->setPlainPassword($user->getCedula());
        $user->setPassword($user->getCedula());
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        if ('true' == $this->sendMail) {
            $this->mailer->sendWelcomeEmailMessage($user);
        }
        $this->entityManager->persist($user);
    }

    // public function exportarExcel($users)
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $filteredCandidatos = [];

    //     /** @var User $user */
    //     foreach ($users as $user) {
    //         if (null !== $user->getSimplePassword()) {
    //             $filteredCandidatos[] = $user;
    //         }
    //     }

    //     /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $sheet->setCellValue('A3', 'Nombre');
    //     $sheet->setCellValue('B1', 'Usuario');
    //     $sheet->setCellValue('C1', 'Contraseña');

    //     $sheet->getColumnDimension('A')->setAutoSize(true);
    //     $sheet->getColumnDimension('B')->setAutoSize(true);
    //     $sheet->getColumnDimension('C')->setAutoSize(true);

    //     $sheet->setTitle('Carga masiva users');

    //     /** @var User $user */
    //     $key = 1;
    //     foreach ($filteredCandidatos as $user) {
    //         ++$key;
    //         $sheet->setCellValue("A${key}", $user->getFirstName().' '.$user->getLastName());
    //         $sheet->setCellValue("B${key}", $user->getUsername());
    //         $sheet->setCellValue("C${key}", $user->getSimplePassword());
    //     }

    //     // Create your Office 2007 Excel (XLSX Format)
    //     $writer = new Xlsx($spreadsheet);

    //     // In this case, we want to write the file in the public directory
    //     $publicDirectory = $this->params->get('kernel.project_dir').'/public';
    //     // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
    //     $excelFilepath = $publicDirectory.'/CargaMasivaCandidatos.xlsx';

    //     // Create the file
    //     $writer->save($excelFilepath);

    //     // Return a text response to the browser saying that the excel was succesfully created
    //     return new Response('Excel generated succesfully');
    // }
}
