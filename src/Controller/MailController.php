<?php

namespace App\Controller;

use App\AppEvents;
use App\Entity\Mail;
use App\Entity\UserGroup;
use App\Event\MailEvent;
use App\Form\MailType;
use App\Repository\MailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/mail")
 */
class MailController extends AbstractController
{

    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher
    ) {

        $this->dispatcher = $eventDispatcher;
    }

    /**
     * @Route("/", name="mail_index", methods={"GET"})
     */
    public function index(MailRepository $mailRepository): Response
    {
        return $this->render('mail/index.html.twig', [
            'mails' => $mailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{uuid}", name="mail_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserGroup $userGroup): Response
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail,[
            'action' => $this->generateUrl('mail_new',[
                'uuid' => $userGroup->getUuid(),
            ])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($mail);

            foreach ($userGroup->getUsers() as $user){
                $user->addMailsReceived($mail);
                $mail->addRecipient($user);
            }

            $mail->setSender($this->getUser());

            $entityManager->flush();

            $this->dispatcher->dispatch(new MailEvent($mail), AppEvents::SEND_MAIL_GROUP);

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Mensaje enviado'
            ]);
        }

        return $this->render('mail/new.html.twig', [
            'mail' => $mail,
            'form' => $form->createView(),
            'group' => $userGroup
        ]);
    }

    /**
     * @Route("/{id}", name="mail_show", methods={"GET"})
     */
    public function show(Mail $mail): Response
    {
        return $this->render('mail/show.html.twig', [
            'mail' => $mail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mail_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mail $mail): Response
    {
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mail_index');
        }

        return $this->render('mail/edit.html.twig', [
            'mail' => $mail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mail_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mail $mail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mail_index');
    }
}
