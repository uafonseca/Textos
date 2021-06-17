<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 6:47 PM
 */

namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\CourseVsit;
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
class VisitDatatable extends AbstractDatatable
{

    /**
     * Undocumented function
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $securityToken
     * @param [type] $translator
     * @param RouterInterface $router
     * @param EntityManagerInterface $em
     * @param Environment $twig
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $securityToken, $translator, RouterInterface $router, EntityManagerInterface $em, Environment $twig)
    {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
        $this->uniqueId = uniqid();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getLineFormatter()
    {
        return function ($row) {
            $visit = $this->getEntityManager()->getRepository(CourseVsit::class)->find($row['id']);
            $row['momment'] = $visit->getMoment()->format('d-m-Y h:m a');
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
            'dom' => 'Blfrtip',
        ]);
     
        $this->features->set([
            'processing' => true,
        ]);

        
        $columns = ['1','2','3'];

        $this->extensions->set(array(
            'buttons' => [
                'create_buttons' => [
                    TableActions::buttonPRINT($columns),
                ],
            ],
        ));

        $this->columnBuilder
            ->add('uuid', Column::class, [
                'title' => 'uuid',
                'visible' => false,
            ])
            ->add('course.title', Column::class, [
                'title' => 'Curso',
                
            ])
            ->add('user.name', Column::class, [
                'title' => 'Usuario',
                
            ])
            ->add('momment', VirtualColumn::class, [
                'title' => 'Fecha de visita',
            ])
            ;
    }

    public function getEntity()
    {
        return CourseVsit::class;
    }

    public function getName()
    {
        return 'visit';
    }
}
