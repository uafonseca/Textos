<?php


namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Code;
use App\Entity\Mail;
use App\Entity\MailResponse;
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
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class CodeDatatable
 * @package App\Datatables\Tables
 */
class MailResponseListDatatable extends AbstractDatatable
{
    private $vich;
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        $translator,
        RouterInterface $router,
        EntityManagerInterface $em,
        Environment $twig
    ) {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
        $this->uniqueId = uniqid();
        
    }

    /**
     * @return \Closure
     */
    public function getLineFormatter()
    {
        return function ($row) {
            /** @var MailResponse $response */
            $response = $this->getEntityManager()->getRepository('App:MailResponse')->find($row['id']);
            $row['book'] = $response->getMail()->getUserGroup()->getCourse()->getTitle();
            $row['uuid'] = $response->getMail()->getUuid();
            if (null !== $response->getAttached()) {
                $row['attached'] = '<a class="btn-pill btn-sm btn btn-primary" download href="'.$this->vich->asset($response->getAttached(), 'imagenFile').'"> <i class="fa fa-download"></i> </a>';
            } else {
                $row['attached'] = '';
            }
            
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

        $this->vich = $options['vich'];

        $this->options->set([
            'classes' => Style::BOOTSTRAP_4_STYLE,
            'individual_filtering' => false,
            'order_cells_top' => true,
            // 'dom' => 'Blfrtip',
        ]);
        $this->extensions->set([
            'responsive' => true,
        ]);

        $this->features->set([
            'processing' => true,
        ]);

        $this->columnBuilder
        
            ->add('uuid', VirtualColumn::class, [
                'title' => 'uuid',
                'visible' => false,
            ])
            ->add('book', VirtualColumn::class, [
                'title' => 'Curso',
                'visible' => $this->authorizationChecker->isGranted('ROLE_USER'),
            ])
            ->add('mail.subject', Column::class, [
                'title' => 'Asunto',
                'visible' => true,
            ])
            ->add('createdAt', DateTimeColumn::class, [
                'title' => 'Fecha de envío',
                'visible' => true,
                
                'date_format' => 'D-MM-yy hh:mm a'
            ])
            ->add('attached', VirtualColumn::class, [
                'title' => 'Adjunto',
                'visible' => true,
            ])
            ->add('evaluation', Column::class, [
                'title' => 'Nota',
                'visible' => true,
            ])
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    array(
                        'route' => 'mail_show',
                        'route_parameters' => array(
                            'uuid' => 'uuid',
                        ),
                        'icon' => 'fa fa-eye cortex-table-action-icon',
                        // 'confirm_message' => 'Are you sure?',
                        'attributes' => function ($row) {
                            return [
                                'class' => 'action-view text-success',
                                'data-tippy-content' => 'Ver tarea',
                                'title' => 'Evaluar',
                                'data-url' => $this->router->generate('mail_show', [
                                    'uuid' => $row['uuid']
                                ])
                                ];
                        },
                        'render_if' => function ($row) {
                            return  $this->authorizationChecker->isGranted('ROLE_USER');
                        },
                    ),
                ],
            ])
        ;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return MailResponse::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mail-response-datatable';
    }
}
