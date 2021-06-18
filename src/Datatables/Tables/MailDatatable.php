<?php


namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Code;
use App\Entity\Mail;
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
            $mail = $this->getEntityManager()->getRepository('App:Mail')->find($row['id']);

            if (null !== $mail->getAttached()) {
                $row['file'] = '<a class="btn-pill btn-sm btn btn-primary" download href="'.$this->vich->asset($mail->getAttached(), 'imagenFile').'"> <i class="fa fa-download"></i> '.$mail->getAttached()->getOriginalName().' </a>';
            } else {
                $row['file'] = '';
            }

            // if (null != $homework = $mail->getHomework() === true) {
            //     $row['homework'] = $homework;
            // } else {
            //     $row['homework'] = false;
            // }
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
            ->add('homework', Column::class, [
                'title' => 'homework',
                'visible' => false,
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
            ->add('context', Column::class, [
                'title' => 'Contenido',
                'visible' => true,
            ])
            ->add('file', VirtualColumn::class, [
                'title' => 'Adjunto',
                'visible' => true,
            ])
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    array(
                        'route' => 'mail_response_new',
                        'route_parameters' => array(
                            'uuid' => 'uuid',
                            'type' => 'testtype',
                            '_format' => 'html',
                            '_locale' => 'es',
                        ),
                        'icon' => 'fa fa-paper-plane cortex-table-action-icon',
                        // 'confirm_message' => 'Are you sure?',
                        'attributes' => array(
                            'class' => 'action-show text-success',
                            'data-tippy-content' => 'Enviar respuesta',
                            'title' => 'Enviar respuesta',
                        ),
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
