<?php


namespace App\Datatables\Tables;

use App\Entity\GlobalLink;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Style;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class GlobalLinkDatatable extends AbstractDatatable
{

    private UploaderHelper  $vich;

    public function getLineFormatter()
    {
        return function($row){
            /** @var GlobalLink $link */
            $link = $this->getEntityManager()->getRepository(GlobalLink::class)->find($row['id']);
            $row['to'] = $link->getDestination() === 'Ambos' ? 'Estudiantes y profesores' : $link->getDestination();
            $row['image'] = '<img style="width: 200px; height: 100px;" src="'.$this->vich->asset($link->getImagen(), 'imagenFile').'" class="img-bordered img-responsive"/>';
            return $row;
        };
    }

    public function buildDatatable(array $options = [])
    {
        $this->ajax->set([
            'url' => $options['url'],
            'method' => 'POST',
        ]);

        $this->vich = $options['vich'];

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
            'title' => 'Nombre del enlace',
        ])
        ->add('image', VirtualColumn::class, [
            'title' => 'Imágen',
        ])
        ->add('to', VirtualColumn::class, [
            'title' => 'Rol',
        ])
        ->add(null, ActionColumn::class, [
            'title' => $this->translator->trans('sg.datatables.actions.title'),
            'actions' => [
                [
                    'route' => 'global_link_edit',
                    'route_parameters' => array(
                        'uuid' => 'uuid',
                    ),
                    'icon' => 'fa fa-edit cortex-table-action-icon',
                    'attributes' => array(
                        'class' => 'action-export text-success',
                        'data-tippy-content' => 'Editar',
                    ),
                ]
            ]
            ])
        ;
    }

    public function getEntity()
    {
        return GlobalLink::class;
    }

    public function getName()
    {
        return 'global-link';
    }
}
