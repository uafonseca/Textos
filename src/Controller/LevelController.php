<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/{id}", name="level_show", methods={"GET"})
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
     * @Route("/{id}", name="level_delete", methods={"DELETE"})
     * @param Request $request
     * @param Level $level
     * @return Response
     */
    public function delete(Request $request, Level $level): Response
    {
        if ($this->isCsrfTokenValid('delete'.$level->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($level);
            $entityManager->flush();
        }

        return $this->redirectToRoute('level_index');
    }

    public function getCompany():Company
    {
        return $this->getUser()->getCompany();
    }
}
