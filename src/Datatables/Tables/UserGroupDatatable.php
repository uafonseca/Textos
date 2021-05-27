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
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class UserDatatable
 * @package App\Datatables\Tables
 */
class UserGroupDatatable extends AbstractDatatable
{

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
            ->add(null, ActionColumn::class, [
                'title' => 'Detalle',
                'width' => '3%',
                'actions' => [
                    [
                        'icon' => 'fa fa-plus-circle text-success fa-icono cortex-table-action-icon',
                        'attributes' => function ($row) {
                            return [
                                'class' => 'show-details',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-tippy-content' => 'Ver accesos',
                                'data-path' => $this->router->generate('users_group_datatable', [
                                    'uuid' => $row['uuid'],
                                ]),
                            ];
                        },
                    ],
                ],
            ])
            ->add('course.title', Column::class, [
				'title' => 'Nombre del curso',
			])
            ->add('groupName', Column::class, [
				'title' => 'Nombre del grupo',
			])
            ->add('startDate', DateTimeColumn::class, [
				'title' => 'Fecha de inicio',
                'date_format' => 'DD/MM/yyyy'
			])
            ->add('modality.name', Column::class, [
				'title' => 'Modalidad',
			])
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    TableActions::edit('user_group_edit'),
                    TableActions::delete('users_group_remove'),
                    TableActions::mailSend('mail_new'),
                    TableActions::default('mail_index','fa-envelope text-danger','show-mail', 'Ver listado'),
                    
                    
                ],
            ])
        ;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return UserGroup::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user-group-datatable';
    }
}
