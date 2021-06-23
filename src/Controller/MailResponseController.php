<?php

namespace App\Controller;

use App\Datatables\Tables\MailResponseDatatable;
use App\Datatables\Tables\MailResponseListDatatable;
use App\Entity\Book;
use App\Entity\Mail;
use App\Entity\MailResponse;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Form\MailResponseType;
use App\Repository\MailResponseRepository;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/mail/response")
 */
class MailResponseController extends AbstractController
{
    private EventDispatcherInterface $dispatcher;

    /** @var DatatableFactory */
    private $datatableFactory;

    /** @var DatatableResponse */
    private $datatableResponse;

    private UploaderHelper $vich;


    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        UploaderHelper $vich
    ) {

        $this->dispatcher = $eventDispatcher;
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->vich = $vich;
    }
    

    /**
     * Undocumented function
     *
     * @param Mail $mail
     * @param Request $request
     * @return Response
     * 
     * @Route("/{uuid}", name="mail_response_index", methods={"GET","POST"})
     */
    public function index(Mail $mail, Request $request): Response
    {
        $datatable = $this->datatableFactory->create(MailResponseDatatable::class);

        $datatable->buildDatatable([
            'url' => $this->generateUrl('mail_response_index',[
                'uuid' => $mail->getUuid()
            ]),
            'vich' => $this->vich
        ]);

        if($request->isXmlHttpRequest() && $request->isMethod('POST')){
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            

            return $this->datatableResponse->getResponse();
        }
        return $this->render('mail_response/index.html.twig', [
            'datatable'=>$datatable
        ]);
    }

  /**
   * Undocumented function
   *
   * @param User $user
   * @param Book $book
   * @param Request $request
   * @return Response
   * 
   * @Route("/list/{uuid}/{userGroup}", name="mail_response_list", methods={"GET","POST"})
   * @ParamConverter("userGroup", class="App:UserGroup")
   */
    public function listByUser(User $user, UserGroup $userGroup, Request $request): Response
    {
        $datatable = $this->datatableFactory->create(MailResponseListDatatable::class);

        $datatable->buildDatatable([
            'url' => $this->generateUrl('mail_response_list',[
                'uuid' => $user->getUuid(),
                'userGroup' => $userGroup->getId()
            ]),
            'group' => $userGroup->getCourse()
        ]);

        if($request->isXmlHttpRequest() && $request->isMethod('POST')){
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            

            return $this->datatableResponse->getResponse();
        }
        return $this->render('mail_response/list.html.twig', [
            'datatable'=>$datatable,
            'user' => $user,
            'userGroup' => $userGroup
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
     * Undocumented function
     *
     * @param Request $request
     * @param MailResponse $mailResponse
     * @return Response
     * 
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

    /**
     * Undocumented function
     *
     * @param MailResponse $response
     * @param Request $request
     * @return Response
     * 
     * @Route("/evaluate/{uuid}", name="mail_response_evaluate", methods={"GET","POST"})
     */
    public function evaluate(MailResponse $response, Request $request):Response{
        $form = $this->createFormBuilder($response,[
            'action' => $this->generateUrl('mail_response_evaluate',[
                'uuid'=>$response->getUuid()
            ])
        ])
        ->add('evaluation',null,[
            'required' => true,
            'label'=>'Nota'
        ])->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos guardados'
            ]);
        }

        return $this->render('mail_response/_form_evaluation.html.twig',[
            'form' => $form->createView(),
            'response' => $response,
            'uniq' => $request->query->get('uniq')
        ]);
    }


    /**
     * Undocumented function
     *
     * @param MailResponse $response
     * @param Request $request
     * @return Response
     * 
     * @Route("/check/{uuid}", name="mail_response_check", options={"expose" = true}, methods={"GET","POST"})
     */
    public function exist(MailResponse $response, Request $request):Response{
        return new JsonResponse([
            'type' => 'success',
            'data' => $response->getEvaluation()!=null
        ]);
    }
}
