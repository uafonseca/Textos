<?php

namespace App\Controller;

use App\Entity\SchoolStage;
use App\Form\SchoolStageType;
use App\Repository\SchoolStageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/school/stage")
 */
class SchoolStageController extends AbstractController
{
    /**
     * @Route("/", name="school_stage_index", methods={"GET"})
     */
    public function index(SchoolStageRepository $schoolStageRepository): Response
    {
        return $this->render('school_stage/index.html.twig', [
            'school_stages' => $schoolStageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="school_stage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $schoolStage = new SchoolStage();
        $form = $this->createForm(SchoolStageType::class, $schoolStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schoolStage);
            $entityManager->flush();

            return $this->redirectToRoute('school_stage_index');
        }

        return $this->render('school_stage/new.html.twig', [
            'school_stage' => $schoolStage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="school_stage_show", methods={"GET"})
     */
    public function show(SchoolStage $schoolStage): Response
    {
        return $this->render('school_stage/show.html.twig', [
            'school_stage' => $schoolStage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="school_stage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SchoolStage $schoolStage): Response
    {
        $form = $this->createForm(SchoolStageType::class, $schoolStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('school_stage_index');
        }

        return $this->render('school_stage/edit.html.twig', [
            'school_stage' => $schoolStage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="school_stage_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SchoolStage $schoolStage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schoolStage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($schoolStage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('school_stage_index');
    }
}
