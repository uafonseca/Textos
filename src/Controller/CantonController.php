<?php

namespace App\Controller;

use App\Entity\Canton;
use App\Form\CantonType;
use App\Repository\CantonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/canton")
 */
class CantonController extends AbstractController
{
    /**
     * @Route("/", name="canton_index", methods={"GET"})
     */
    public function index(CantonRepository $cantonRepository): Response
    {
        return $this->render('canton/index.html.twig', [
            'cantons' => $cantonRepository->findAll(),
        ]);
    }
	
	
	/**
     * @param \Symfony\Component\HttpFoundation\Request $request
	 * @Route("/all", name="canton_all")
	 */
    public function finAll(CantonRepository $cantonRepository, Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $canton = $em->getRepository(Canton::class)->findBy(['provincia'=>$id]);
        $arr = [];
        foreach($canton as $c){
            $arr[] = [
                'id' => $c->getId(),
                'name' => $c->getNombre(),
            ];

        }
        return new JsonResponse($arr);
    }

    /**
     * @Route("/new", name="canton_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $canton = new Canton();
        $form = $this->createForm(CantonType::class, $canton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($canton);
            $entityManager->flush();

            return $this->redirectToRoute('canton_index');
        }

        return $this->render('canton/new.html.twig', [
            'canton' => $canton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="canton_show", methods={"GET"})
     */
    public function show(Canton $canton): Response
    {
        return $this->render('canton/show.html.twig', [
            'canton' => $canton,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="canton_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Canton $canton): Response
    {
        $form = $this->createForm(CantonType::class, $canton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('canton_index');
        }

        return $this->render('canton/edit.html.twig', [
            'canton' => $canton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="canton_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Canton $canton): Response
    {
        if ($this->isCsrfTokenValid('delete'.$canton->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($canton);
            $entityManager->flush();
        }

        return $this->redirectToRoute('canton_index');
    }
}
