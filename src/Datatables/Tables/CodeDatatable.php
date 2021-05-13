<?php


namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Code;
use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Style;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

/**
 * Class CodeDatatable
 * @package App\Datatables\Tables
 */
class CodeDatatable extends AbstractDatatable
{

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $securityToken, $translator, RouterInterface $router, EntityManagerInterface $em, Environment $twig)
    {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
        $this->uniqueId = uniqid();
    }

    /**
     * @return \Closure
     */
    public function getLineFormatter()
    {
        return function ($row) {
            /** @var Code $code */
            $code = $this->getEntityManager()->getRepository(Code::class)->find($row['id']);
            $row['user'] = $code->getUser() ? $code->getUser()->getName() : '';
            $row['status'] = $code->getEndDate() < new \DateTime('now') ? '<span class="text-success">Activado</span>' : '<span class="text-warning">Vencido</span>';
            return $row;
        };
    }

    /**
     * @param array $options
     * @throws \Exception
     */
    public function buildDatatable(array $options = [])
    {
        $this->ajax->set([
            'url' => $options['url'],
            'method' => 'POST',
        ]);

        $this->options->set([
            'classes' => Style::BOOTSTRAP_4_STYLE,
            'individual_filtering' => false,
            'order_cells_top' => true,
            'dom' => 'Blfrtip',
        ]);
        if (isset($options['with_user']) && $options['with_user'] === true) {
            $columns = ['1','2','3', '4', '5', '6'];
        } else {
            $columns = ['1','2','3', '4'];
        }

        $this->extensions->set(array(
            'buttons' => [
                'create_buttons' => [
                    TableActions::buttonPRINT($columns),
                ],
            ],
        ));

        $this->features->set([
            'processing' => true,
        ]);

        $this->columnBuilder
            ->add('uuid', Column::class, [
                'title' => 'uuid',
                'visible' => false,
            ])
            ->add('code', Column::class, [
                'title' => 'Código',
                'class_name' => 'text-uppercase'
            ]);

        if (isset($options['with_user']) && $options['with_user'] === true) {
            $this->columnBuilder
                ->add('user', VirtualColumn::class, [
                    'title' => 'Usuario',
                ])
                ->add('status', VirtualColumn::class, [
                    'title' => 'Estado',
                ]);

        }
        $this->columnBuilder
            ->add('book.title', Column::class, [
                'title' => 'Curso',
            ])
            ->add('starDate', DateTimeColumn::class, [
                'title' => 'Fecha de activación',
            ])
            ->add('endDate', DateTimeColumn::class, [
                'title' => 'Fecha de fin',
            ])
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    TableActions::delete('code_delete')
                ],
            ]);
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return Code::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'code-datatable';
    }
}
