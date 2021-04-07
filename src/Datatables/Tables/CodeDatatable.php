<?php


namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Code;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class CodeDatatable
 * @package App\Datatables\Tables
 */
class CodeDatatable extends AbstractDatatable
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
            ->add('code', Column::class, [
                'title' => 'Código',
                'class_name' => 'text-uppercase'
            ])
            ->add('book.title', Column::class, [
                'title' => 'Libro',
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
