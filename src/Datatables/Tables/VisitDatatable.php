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

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $securityToken, $translator, RouterInterface $router, EntityManagerInterface $em, Environment $twig)
    {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
        $this->uniqueId = uniqid();
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
            ->add('moment', DateTimeColumn::class, [
                'title' => 'Fecha de visita',
                'date_format' => 'D-MM-yy hh:mm a',
            ])
            ;
    }

    public function getEntity()
    {
        return CourseVsit::class;
    }

    public function getName()
    {
        return 'visit-datatable';
    }
}
