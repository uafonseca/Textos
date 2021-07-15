<?php

namespace App\Controller;

use App\Datatables\Tables\GlobalLinkDatatable;
use App\Entity\GlobalLink;
use App\Form\GlobalLinkType;
use App\Repository\GlobalLinkRepository;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * @Route("/global/link")
 */
class GlobalLinkController extends AbstractController
{

    private DatatableFactory $datatableFactory;

    private DatatableResponse $datatableResponse;

    private UploaderHelper $vich;

    /**
     * Undocumented function
     *
     * @param DatatableFactory $datatableFactory
     * @param DatatableResponse $datatableResponse
     */
    public function __construct(DatatableFactory $datatableFactory, DatatableResponse $datatableResponse, UploaderHelper $vich)
    {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->vich = $vich;
    }


    /**
     * @Route("/", name="global_link_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $datatable = $this->datatableFactory->create(GlobalLinkDatatable::class);
        $datatable->buildDatatable([
            'url' => $this->generateUrl('global_link_index'),
            'vich' => $this->vich,
        ]);

        if($request->isXmlHttpRequest() && $request->isMethod('POST')){
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            if($this->isGranted('ROLE_USER')){
                $qb
                ->getQb()
                ->where('globallink.destination =:d')
                ->setParameter('d', 'Estudiantes');
            }
            return $this->datatableResponse->getResponse();
        }
        return $this->render('global_link/index.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/new", name="global_link_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $globalLink = new GlobalLink();
        $form = $this->createForm(GlobalLinkType::class, $globalLink,[
            'edit' =>false
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($globalLink);
            $entityManager->flush();

            return $this->redirectToRoute('global_link_index');
        }

        return $this->render('global_link/new.html.twig', [
            'global_link' => $globalLink,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="global_link_show", methods={"GET"})
     */
    public function show(GlobalLink $globalLink): Response
    {
        return $this->render('global_link/show.html.twig', [
            'global_link' => $globalLink,
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="global_link_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GlobalLink $globalLink): Response
    {
        $form = $this->createForm(GlobalLinkType::class, $globalLink,[
            'edit' =>true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('global_link_index');
        }

        return $this->render('global_link/edit.html.twig', [
            'global_link' => $globalLink,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="global_link_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GlobalLink $globalLink): Response
    {
        if ($this->isCsrfTokenValid('delete'.$globalLink->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($globalLink);
            $entityManager->flush();
        }

        return $this->redirectToRoute('global_link_index');
    }
}
