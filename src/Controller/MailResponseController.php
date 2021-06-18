<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Entity\MailResponse;
use App\Form\MailResponseType;
use App\Repository\MailResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mail/response")
 */
class MailResponseController extends AbstractController
{
    /**
     * @Route("/", name="mail_response_index", methods={"GET"})
     */
    public function index(MailResponseRepository $mailResponseRepository): Response
    {
        return $this->render('mail_response/index.html.twig', [
            'mail_responses' => $mailResponseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{uuid}", name="mail_response_new", methods={"GET","POST"})
     */
    public function new(Request $request, Mail $mail): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        if(null != $response = $entityManager->getRepository(MailResponse::class)->findByMailAndUser($mail, $this->getUser())){
            $mailResponse = $response;
        }else{
            $mailResponse = new MailResponse();
        }

        $form = $this->createForm(MailResponseType::class, $mailResponse,[
            'action' => $this->generateUrl('mail_response_new',[
                'uuid' => $mail->getUuid()
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailResponse
                ->setUser($this->getUser())
                ->setMail($mail);
            $this->getUser()->addMailResponse($mailResponse);
            $mail->addMailResponse($mailResponse);
            $entityManager->persist($mailResponse);
            $entityManager->flush();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos enviados'
            ]);
        }

        return $this->render('mail_response/new.html.twig', [
            'mail_response' => $mailResponse,
            'mail' => $mail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mail_response_show", methods={"GET"})
     */
    public function show(MailResponse $mailResponse): Response
    {
        return $this->render('mail_response/show.html.twig', [
            'mail_response' => $mailResponse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mail_response_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MailResponse $mailResponse): Response
    {
        $form = $this->createForm(MailResponseType::class, $mailResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mail_response_index');
        }

        return $this->render('mail_response/edit.html.twig', [
            'mail_response' => $mailResponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mail_response_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MailResponse $mailResponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mailResponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mailResponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mail_response_index');
    }
}
