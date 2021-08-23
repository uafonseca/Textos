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

/**
 * Class CodeDatatable
 * @package App\Datatables\Tables
 */
class RecivedDatatable extends AbstractDatatable
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
            $mail = $this->getEntityManager()->getRepository('App:MailResponse')->find($row['id']);

            if (null !== $mail->getAttached()) {
                $row['attached'] = '<a class="btn-pill btn-sm btn btn-primary" download href="'.$this->vich->asset($mail->getAttached(), 'imagenFile').'"> <i class="fa fa-download"></i> </a>';
            } else {
                $row['attached'] = '';
            }
            $row['context'] = TableActions::truncate($mail->getContext(), 100);
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

        $this->features->set([
            'processing' => true,
        ]);

        $this->columnBuilder
        
            ->add('uuid', Column::class, [
                'title' => 'uuid',
                'visible' => false,
            ])
            ->add('User.name', Column::class, [
                'title' => 'Usuario',
                'visible' => true,
            ])
            ->add('createdAt', DateTimeColumn::class, [
                'title' => 'Fecha de envío',
                'visible' => true,
                'width' => '15%',
                'date_format' => 'D-MM-yy hh:mm a'
            ])
            ->add('context', VirtualColumn::class, [
                'title' => 'Contenido',
                'visible' => true,
            ])
            ->add('evaluation', Column::class, [
                'title' => 'Nota',
                'visible' => true,
            ])
            ->add('attached', VirtualColumn::class, [
                'title' => 'Adjunto',
                'visible' => true,
            ])
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    array(
                        'route' => 'mail_response_evaluate',
                        'route_parameters' => array(
                            'uuid' => 'uuid',
                        ),
                        'icon' => 'fa fa-edit cortex-table-action-icon',
                        // 'confirm_message' => 'Are you sure?',
                        'attributes' => function ($row) {
                            return [
                                'class' => 'action-evaluate text-success',
                                'data-tippy-content' => 'Evaluar',
                                'title' => 'Evaluar',
                                'data-url' => $this->router->generate('mail_response_check', [
                                    'uuid' => $row['uuid']
                                ])
                                ];
                        },
                        'render_if' => function ($row) {
                            return  $this->authorizationChecker->isGranted('ROLE_ADMIN') || $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN');
                        },
                       
                    ),
                    TableActions::export('mail_response_print')
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
