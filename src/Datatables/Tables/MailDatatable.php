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
class MailDatatable extends AbstractDatatable
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
            /** @var Mail $mail */
            $mail = $this->getEntityManager()->getRepository('App:Mail')->find($row['id']);

            if (null !== $mail->getAttached()) {
                $row['file'] = '<a class="btn-pill btn-sm btn btn-primary" download href="'.$this->vich->asset($mail->getAttached(), 'imagenFile').'"> <i class="fa fa-download"></i>  </a>';
            } else {
                $row['file'] = '';
            }
            $user = $this->securityToken->getToken()->getUser();
            $evaluation = '<span class="text-info">NA</span>';
            /** @var MailResponse $response */
            foreach($mail->getMailResponses() as $response){
                if ($response->getUser() === $user){
                    if (null != $response->getEvaluation()){
                        $evaluation = $response->getEvaluation();
                    }
                }
            }
            if($mail->getHomework() && gettype($evaluation) === 'string'){
                $evaluation = '<span class="text-danger">Pendiente</span>';
            }
            $row['nota'] = $evaluation;
            $row['context'] = TableActions::truncate($mail->getContext(), 100);
            $row['course'] = $mail->getUserGroup()->getCourse()->getTitle();
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
            'classes' => 'table table-bordered table-hover',
            'individual_filtering' => false,
            'order_cells_top' => true,
            // 'dom' => 'Bfrtip',
        ]);

        $this->extensions->set([
            'responsive' => true,
        ]);



        $this->features->set([
            'processing' => true,
        ]);
        if($this->authorizationChecker->isGranted('ROLE_PROFESOR') || $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')){
            $this->columnBuilder
            ->add(null, ActionColumn::class, [
                'title' => 'Detalles',
                'width' => '3%',
                'actions' => [
                    [
                        'icon' => 'fa fa-plus-circle text-success fa-icono cortex-table-action-icon',
                        'attributes' => function ($row) {
                            return [
                                'class' => 'show-details',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'data-tippy-content' => 'Ver detalles',
                                'data-path' => $this->router->generate('mail_response_index', [
                                    'uuid' => $row['uuid'],
                                ]),
                            ];
                        },
                        'render_if' => function ($row) {
                            return $row['homework'] && $this->authorizationChecker->isGranted('ROLE_PROFESOR');
                        },
                    ],
                ],
            ]);
        }
        $this->columnBuilder
            ->add('uuid', Column::class, [
                'title' => 'uuid',
                'visible' => false,
            ])
            ->add('homework', Column::class, [
                'title' => 'homework',
                'visible' => false,
            ])
            ->add('course', VirtualColumn::class, [
                'title' => 'Curso',
                'visible' => $this->authorizationChecker->isGranted('ROLE_USER'),
            ])
            ->add('createdAt', DateTimeColumn::class, [
                'title' => 'Fecha',
                'visible' => true,
                'width' => '15%',
                'date_format' => 'D-MM-yy hh:mm'
            ])
            ->add('subject', Column::class, [
                'title' => 'Asunto',
                'visible' => true,
            ])
            ->add('context', VirtualColumn::class, [
                'title' => 'Contenido',
                'visible' => true,
            ])
            ->add('nota', VirtualColumn::class, [
                'title' => 'Nota',
                'visible' => true,
            ])
            ->add('file', VirtualColumn::class, [
                'title' => 'Adjunto',
                'visible' => true,
            ])
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'visible' => $this->authorizationChecker->isGranted('ROLE_USER'),
                'actions' => [
                    array(
                        'route' => 'mail_response_new',
                        'route_parameters' => array(
                            'uuid' => 'uuid',
                        ),
                        'icon' => 'fa fa-paper-plane cortex-table-action-icon',
                        'attributes' => function($row){
                            return [
                                'class' => 'action-show text-success',
                                'data-tippy-content' => 'Enviar respuesta',
                                'title' => 'Enviar respuesta',
                                'data-url' => $this->router->generate('mail_response_check-response', [
                                    'uuid' => $row['uuid']
                                ])
                            ];
                        },
                        'render_if' => function ($row) {
                            return $row['homework'] && $this->authorizationChecker->isGranted('ROLE_USER');
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
        return Mail::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mail-datatable';
    }
}
