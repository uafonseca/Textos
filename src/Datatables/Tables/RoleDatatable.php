<?php


namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Book;
use App\Entity\Role;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class RoleDatatable
 * @package App\Datatables\Tables
 */
class RoleDatatable extends AbstractDatatable
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
			->add('rolename', Column::class, [
				'title' => 'Título',
			])
			->add(null, ActionColumn::class, [
				'title' => $this->translator->trans('sg.datatables.actions.title'),
				'actions' => [
					TableActions::edit('role_edit'),
					TableActions::delete('role_delete')
				],
			])
		;
	}

    /**
     * @return string
     */
	public function getEntity()
	{
		return Role::class;
	}

    /**
     * @return string
     */
	public function getName()
	{
		return 'book-datatable';
	}
}
