<?php

namespace App\Controller;

use App\Datatables\Tables\VisitDatatable;
use App\Entity\Book;
use App\Entity\User;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class VisitController extends AbstractController
{

     /** @var DatatableFactory */
     private $datatableFactory;

     /** @var DatatableResponse */
     private $datatableResponse;
 
 
     public function __construct(
         DatatableFactory $datatableFactory,
         DatatableResponse $datatableResponse
     ) {
         $this->datatableFactory = $datatableFactory;
         $this->datatableResponse = $datatableResponse;
     }

     

    /**
     * @Route("/visit/{id}/{user}", name="visit_index")
     * @ParamConverter("id", class="App:Book")
     */
    public function index(Book $book, User $user, Request $request): Response
    {
        $datatable = $this->datatableFactory->create(VisitDatatable::class);
        $datatable->buildDatatable([
            'url' => $this->generateUrl('visit_index',[
                'id' => $book->getId(),
                'user' => $user->getId()
            ])
        ]);

        if($request->isXmlHttpRequest() && $request->isMethod('POST')){
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            $qb
                ->getQb()
                ->where('coursevsit.course =:course')
                ->setParameter('course',$book)
                ;


            return $this->datatableResponse->getResponse();
        }
        
        return $this->render('visit/index.html.twig', [
            'datatable' => $datatable
        ]);
    }
}
