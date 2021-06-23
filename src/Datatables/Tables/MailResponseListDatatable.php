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
class MailResponseListDatatable extends AbstractDatatable
{
    private $book;
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
        
            $row['book'] = $this->book->getId();
            
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

        $this->book = $options['group'];

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
            ->add('mail.subject', Column::class, [
                'title' => 'Asunto',
                'visible' => true,
            ])
            ->add('createdAt', DateTimeColumn::class, [
                'title' => 'Fecha de envío',
                'visible' => true,
                
                'date_format' => 'D-MM-yy hh:mm a'
            ])
            ->add('evaluation', Column::class, [
                'title' => 'Nota',
                'visible' => true,
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
