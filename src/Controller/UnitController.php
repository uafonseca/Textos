<?php

namespace App\Controller;

use App\Datatables\Tables\UnitDatatable;
use App\Entity\Activity;
use App\Entity\Company;
use App\Entity\Unit;
use App\Form\ActivityFormType;
use App\Form\UnitType;
use App\Repository\ActivityRepository;
use App\Repository\UnitRepository;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param Request $request
     * @return Response
     * @throws Exception
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
     * @param Request $request
     * @return Response
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
     * @Route("/show/{uuid}", name="unit_show", methods={"GET"})
     * @param Unit $unit
     * @return Response
     */
    public function show(Unit $unit): Response
    {
        return $this->render('unit/show.html.twig', [
            'unit' => $unit,
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="unit_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Unit $unit
     * @return Response
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
     * @param Request $request
     * @param Unit $unit
     * @return Response
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


    /**
     * @Route("/activities/{uuid}", name="unit_activities", methods={"POST","GET"})
     * @param Unit $unit
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addActivities(Unit $unit, Request $request)
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

        return $this->render('unit/add_activity.html.twig', [
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/create-activity/{uuid}", name="create-activity", methods={"POST","GET"}, options={"expose" = true})
     * @param Unit $unit
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function createActivity(Unit $unit,Request $request)
    {
        $activity = new Activity();
        $activity->setUnit($unit);
        $type = $request->query->get('type');
        

        $form = $this->createForm(ActivityFormType::class, $activity,[
            'action' => $this->generateUrl('create-activity',[
                'uuid' => $unit->getUuid(),
                'type' =>$type
            ]),
            'type' =>$type
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $activity->setType($type);

            $em = $this->getDoctrine ()->getManager ();

            $em->persist($activity);

            $unit->addActivity($activity);

            $em->flush ();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos guardados'
            ]);
        }

        return $this->render('activity/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update-activity/{id}", name="update-activity", methods={"POST","GET"}, options={"expose" = true})
     * @param Activity $activity
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function updateActivity(Activity $activity, Request $request)
    {
        
        $form = $this->createForm(ActivityFormType::class, $activity,[
            'action' => $this->generateUrl('update-activity',[
                'id' => $activity->getId(),
            ]),
            'type' => $activity->getType()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine ()->getManager ();

            $em->persist($activity);


            $em->flush ();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos guardados'
            ]);
        }

        return $this->render('activity/update.html.twig',[
            'form' => $form->createView(),
            'activity' => $activity
        ]);
    }

    /**
     * @Route("/all-activites/{uuid}", name="all-activites", methods={"POST","GET"}, options={"expose" = true})
     * @param Unit $unit
     * @param ActivityRepository $activityRepository
     * @return Response
     */
    public function loadActivities(Unit $unit, ActivityRepository $activityRepository)
    {    
        $all = $activityRepository->findBy([
            'unit' => $unit
        ]);

        return $this->render('activity/list.html.twig',[
            'all' => $all
        ]);
    }

    /**
     * @Route("/remove-activity/{id}", name="remove-activity", methods={"POST","GET"}, options={"expose" = true})
     * @param Activity $activity
     * @return JsonResponse
     */
    public function removeActivity(Activity $activity){
        $em = $this->getDoctrine ()->getManager ();

        $em->remove($activity);
        $em->flush ();

        return new JsonResponse([
            'type' => 'success',
            'message' => 'Recuso eliminado'
        ]);
    }

    /**
     * @return \App\Entity\Company
     */
    public function getCompany():Company
    {
        return $this->getUser()->getCompany();
    }

    /**
     * @param \App\Entity\Unit $unit
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/details/{uuid}", name="show_details", methods={"POST","GET"})
     */
    public function showDetails(Unit $unit): Response
    {
        return $this->render('unit/show_details.html.twig',[
            'unit' => $unit
        ]);
    }
}
