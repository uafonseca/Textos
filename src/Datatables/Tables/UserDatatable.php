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
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class UserDatatable
 * @package App\Datatables\Tables
 */
class UserDatatable extends AbstractDatatable
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
			->add('name', Column::class, [
				'title' => 'Nombre',
			])
			->add('firstName', Column::class, [
				'title' => 'Apellidos',
			])
			->add('username', Column::class, [
				'title' => 'Usuario',
			])
			->add('email', Column::class, [
				'title' => 'Correo',
			])
			->add(null, ActionColumn::class, [
				'title' => $this->translator->trans('sg.datatables.actions.title'),
				'actions' => [
					[
						'route' => 'user_promote',
						'route_parameters' => array_merge(array(
							'uuid' => 'uuid'
						)),
						'icon' => 'fa fa-cog cortex-table-action-icon',
						'attributes' => [
							'style' => "color:  green;",
							'data-tippy-content' => 'Promover'
						]
					]
				],
			]);
	}

    /**
     * @return string
     */
	public function getEntity()
	{
		return User::class;
	}

    /**
     * @return string
     */
	public function getName()
	{
		return 'user-datatable';
	}
}
