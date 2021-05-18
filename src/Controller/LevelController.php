<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/level")
 */
class LevelController extends AbstractController
{
    /**
     * @Route("/", name="level_index", methods={"GET"})
     * @param LevelRepository $levelRepository
     * @return Response
     */
    public function index(LevelRepository $levelRepository): Response
    {
        return $this->render('level/index.html.twig', [
            'levels' => $levelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="level_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $level = new Level();
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $level->setCompany($this->getCompany());
            $entityManager->persist($level);
            $entityManager->flush();

            return $this->redirectToRoute('level_index');
        }

        return $this->render('level/new.html.twig', [
            'level' => $level,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("show/{id}", name="level_show", methods={"GET"})
     * @param Level $level
     * @return Response
     */
    public function show(Level $level): Response
    {
        return $this->render('level/show.html.twig', [
            'level' => $level,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="level_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Level $level
     * @return Response
     */
    public function edit(Request $request, Level $level): Response
    {
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('level_index');
        }

        return $this->render('level/edit.html.twig', [
            'level' => $level,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("remove/{id}", name="level_delete")
     * @param Request $request
     * @param Level $level
     * @return Response
     */
    public function delete(Request $request, Level $level): Response
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($level);
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

    public function getCompany(): Company
    {
        return $this->getUser()->getCompany();
    }
}
