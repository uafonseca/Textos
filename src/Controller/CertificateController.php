<?php

namespace App\Controller;

use App\Entity\Certificate;
use App\Entity\Evaluation;
use App\Form\CertificateType;
use App\Repository\CertificateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/certificate")
 */
class CertificateController extends AbstractController
{
    /**
     * @Route("/", name="certificate_index", methods={"GET"})
     */
    public function index(CertificateRepository $certificateRepository): Response
    {
        return $this->render('certificate/index.html.twig', [
            'certificates' => $certificateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="certificate_new", methods={"GET","POST"})
     */
    public function new(Request $request, Evaluation $evaluation): Response
    {
        $certificate = new Certificate();
        $form = $this->createForm(CertificateType::class, $certificate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($certificate);
            $entityManager->flush();

            return new JsonResponse([]);
        }

        return $this->render('certificate/new.html.twig', [
            'certificate' => $certificate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="certificate_show", methods={"GET"})
     */
    public function show(Certificate $certificate): Response
    {
        return $this->render('certificate/show.html.twig', [
            'certificate' => $certificate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="certificate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Certificate $certificate): Response
    {
        $form = $this->createForm(CertificateType::class, $certificate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('certificate_index');
        }

        return $this->render('certificate/edit.html.twig', [
            'certificate' => $certificate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="certificate_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Certificate $certificate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$certificate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($certificate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('certificate_index');
    }
}
