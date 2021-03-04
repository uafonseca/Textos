<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PromoteUserCommand extends Command
{
    protected static $defaultName = 'app:promote-user';
    protected static $defaultDescription = 'Promover usuarios Ej. app:promote-user -u admin -r ROLE_ADMIN';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->setDefinition(
                new InputDefinition([
                    new InputOption('user', 'u', InputOption::VALUE_REQUIRED),
                    new InputOption('role', 'r', InputOption::VALUE_REQUIRED),

                ])
            );;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getOption('user');
        $rolename = $input->getOption('role');

        if ($username && $username) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

            $role = $this->entityManager->getRepository(Role::class)->findOneBy(['rolename' => $rolename]);

            if($user && $role){
                $user->addRolesObject($role);
                $io->success('Usuario promovido correctamente');
            }else{
                $io->error('El usuario o el rol no se han encontrado');    
            }
        }

        if (!$username) {
            $io->error('Debe especificar un nombre de usuario');
        }
        if (!$rolename) {
            $io->error('Debe especificar un rol');
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
