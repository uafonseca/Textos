<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 6:47 PM
 */

namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\User;
use App\Entity\UserGroup;
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
 * Class UserDatatable
 * @package App\Datatables\Tables
 */
class UsersGroupDatatable extends AbstractDatatable
{
    private $group;
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $securityToken, $translator, RouterInterface $router, EntityManagerInterface $em, Environment $twig)
    {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
        $this->uniqueId = uniqid();
    }

    public function getLineFormatter()
    {
        return function ($row) {
            /** @var User $user */
            $user = $this->getEntityManager()->getRepository('App:User')->find($row['id']);
            $row['group'] = $this->group ? $this->group->getId() : null;
            $row['homework'] = $user->getMailResponses()->count();

            return $row;
        };
    }


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
        ]);
        $this->extensions->set(array(
            'responsive' => true,
        ));
        $this->features->set([
            'processing' => true,
        ]);
        
        if (isset($options['group'])) {
            $this->group = $options['group'];
        }

        $this->columnBuilder
            ->add('uuid', Column::class, [
                'title' => 'uuid',
                'visible' => false,
            ])
            ->add('id', Column::class, [
                'title' => 'id',
                'visible' => false,
            ])
            ->add('group', VirtualColumn::class, [
                'title' => 'group',
                'visible' => false,
            ])
            ->add('name', Column::class, [
                'title' => 'Nombre',
            ])
            ->add('firstName', Column::class, [
                'title' => 'Apellidos',
            ])
            ->add('email', Column::class, [
                'title' => 'Email',
            ])
             ->add('homework', VirtualColumn::class, [
                'title' => 'Tareas recibidas',
            ])
            ;
        if (!isset($options['actions'])) {
            $this->columnBuilder
                ->add(null, ActionColumn::class, [
                    'title' => $this->translator->trans('sg.datatables.actions.title'),
                    'actions' => [
                        TableActions::delete('user_delete'),
                        TableActions::default('show_status', 'fa-print text-warning', 'action-export', 'Ver', [
                            'id' => 'id',
                            'userGroup' => 'group',
                        ]),
                        array(
                            'route' => 'mail_response_list',
                            'route_parameters' => array(
                                'uuid' => 'uuid',
                                'userGroup' => 'group',
                            ),
                            'icon' => 'fa fa-edit cortex-table-action-icon',
                            'attributes' => array(
                                'class' => 'action-export text-success',
                                'data-tippy-content' => 'Ver notas',
                                'title' => 'Ver notas',
                            ),
                            'render_if' => function ($row) {
                                return !$this->authorizationChecker->isGranted('ROLE_USER');
                            },
                        ),
                    ],
                ]);
        }
    }

    public function getEntity()
    {
        return User::class;
    }

    public function getName()
    {
        return 'users-datatable';
    }
}
