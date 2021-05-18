<?php

namespace App\Controller;

use App\Datatables\Tables\CategoryDatatable;
use App\Entity\Category;
use App\Entity\Company;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{


      /** @var DatatableFactory */
	private $datatableFactory;
	
	/** @var DatatableResponse */
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
     * @Route("/", name="category_index", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function index(Request $request): Response
    {

        $datatable = $this->datatableFactory->create(CategoryDatatable::class);
    	
    	$datatable->buildDatatable ([
    		'url' => $this->generateUrl ('category_index')
	    ]);
    	
    	if ($request->isXmlHttpRequest () && $request->isMethod ('POST')){
    		$this->datatableResponse->setDatatable($datatable);
		    $this->datatableResponse->getDatatableQueryBuilder();

        return $this->datatableResponse->getResponse();
	    }
    	
        return $this->render('category/index.html.twig', [
            'datatable' => $datatable
        ]);

    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $category->setCompany($this->getCompany());
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{uuid}", name="category_show", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="category_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="category_delete", methods={"DELETE","GET"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function delete(Request $request, Category $category): Response
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            return new JsonResponse([
                'type' => 'error',
                'message' => "El elemento que desea eliminar se encuantra en uso.",
            ]);
        }

        return new JsonResponse([
            'type' => 'success',
            'message' => 'Datos eliminados',
            'no_reload' => true
        ]);
    }

    public function getCompany():Company
    {
        return $this->getUser()->getCompany();
    }
}
