<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\SchoolStage;
use App\Form\SchoolStageType;
use App\Repository\SchoolStageRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param SchoolStageRepository $schoolStageRepository
     * @return Response
     */
    public function index(SchoolStageRepository $schoolStageRepository): Response
    {
        return $this->render('school_stage/index.html.twig', [
            'school_stages' => $schoolStageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="school_stage_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $schoolStage = new SchoolStage();
        $form = $this->createForm(SchoolStageType::class, $schoolStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $schoolStage->setCompany($this->getCompany());
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
     * @Route("show/{id}", name="school_stage_show", methods={"GET"})
     * @param SchoolStage $schoolStage
     * @return Response
     */
    public function show(SchoolStage $schoolStage): Response
    {
        return $this->render('school_stage/show.html.twig', [
            'school_stage' => $schoolStage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="school_stage_edit", methods={"GET","POST"})
     * @param Request $request
     * @param SchoolStage $schoolStage
     * @return Response
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
     * @Route("remove/{id}", name="school_stage_delete")
     * @param Request $request
     * @param SchoolStage $schoolStage
     * @return Response
     */
    public function delete(Request $request, SchoolStage $schoolStage): Response
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($schoolStage);
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
