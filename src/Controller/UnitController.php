<?php

namespace App\Controller;

use App\Datatables\Tables\UnitDatatable;
use App\Entity\Company;
use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\UnitRepository;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/unit")
 */
class UnitController extends AbstractController
{

    /** DatatableFactory */
    private $datatableFactory;

    /** DatatableResponse */
    private $datatableResponse;

    /**
	 * UserController constructor.
	 *
	 * @param DatatableFactory  $datatableFactory
	 * @param DatatableResponse $datatableResponse
	 */
	public function __construct (DatatableFactory $datatableFactory, DatatableResponse $datatableResponse)
	{
		$this->datatableFactory = $datatableFactory;
		$this->datatableResponse = $datatableResponse;
	}

    /**
     * @Route("/", name="unit_index", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $datatable = $this->datatableFactory->create(UnitDatatable::class);
    	
    	$datatable->buildDatatable ([
    		'url' => $this->generateUrl ('unit_index')
	    ]);
    	
    	if ($request->isXmlHttpRequest () && $request->isMethod ('POST')){
    		$this->datatableResponse->setDatatable($datatable);
		    $this->datatableResponse->getDatatableQueryBuilder();

        return $this->datatableResponse->getResponse();
	    }
    	
        return $this->render('unit/index.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/new", name="unit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $unit = new Unit();
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $unit->setCompany($this->getCompany());

            $entityManager->persist($unit);

            /** @var Activity $activity */
            foreach ($unit->getActivities() as $activity){
                $activity->setUnit($unit);
            }

            $entityManager->flush();

            return $this->redirectToRoute('unit_index');
        }

        return $this->render('unit/new.html.twig', [
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="unit_show", methods={"GET"})
     */
    public function show(Unit $unit): Response
    {
        return $this->render('unit/show.html.twig', [
            'unit' => $unit,
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="unit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Unit $unit): Response
    {
        $form = $this->createForm(UnitType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Activity $activity */
            foreach ($unit->getActivities() as $activity){
                $activity->setUnit($unit);
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('unit_index');
        }

        return $this->render('unit/edit.html.twig', [
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="unit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Unit $unit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($unit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('unit_index');
    }



    public function getCompany():Company
    {
        return $this->getUser()->getCompany();
    }
}
