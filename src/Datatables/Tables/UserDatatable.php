<?php
	/**
	 * Created by PhpStorm.
	 * User: Ubel
	 * Date: 17/02/2021
	 * Time: 6:47 PM
	 */
	
	namespace App\Datatables\Tables;
	
	
	use App\Entity\User;
	use Sg\DatatablesBundle\Datatable\AbstractDatatable;
	use Sg\DatatablesBundle\Datatable\Column\Column;
	use Sg\DatatablesBundle\Datatable\Style;
	
	class UserDatatable extends AbstractDatatable
	{
		public function buildDatatable (array $options = [])
		{
			$this->ajax->set ([
				'url' => $options[ 'url' ],
				'method' => 'POST',
			]);
			
			$this->options->set ([
				'classes' => Style::BOOTSTRAP_4_STYLE,
				'individual_filtering' => false,
				'order_cells_top' => true,
			]);
			$this->extensions->set (array (
				'responsive' => true,
			));
			$this->features->set ([
				'processing' => true,
			]);
			$this->columnBuilder
				->add ('uuid', Column::class, [
					'title' => 'uuid',
//					'visible' => false,
				])
//				->add('grado', VirtualColumn::class, [
//					'title' => 'Grado/Curso',
//				])
//				->add('nombre', Column::class, [
//					'title' => 'Nombre',
//				])
//				->add('uri', Column::class, [
//					'title' => 'Url',
//				])
//				->add(null, ActionColumn::class, [
//					'title' => $this->translator->trans('sg.datatables.actions.title'),
//					'actions' => [
//						TableActions::edit('acceso.edit'),
//						TableActions::delete('acceso.remove'),
//						[
//							'route' => 'redirect_accesos',
//							'route_parameters' => array_merge(array(
//								'uuid' => 'uuid'
//							)),
//							'icon' => 'fa fa-play cortex-table-action-icon',
//							'attributes' => [
//								'target' => '_blank',
//								'style' => "color:  green;",
//								'data-tippy-content' => 'Abrir'
//							]
//						]
//					],
//				])
			;
		}
		
		public function getEntity ()
		{
			return User::class;
		}
		
		public function getName ()
		{
			return 'user-datatable';
		}
	}