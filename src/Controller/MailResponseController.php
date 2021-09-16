<?php

namespace App\Controller;

use App\Datatables\Tables\MailResponseDatatable;
use App\Datatables\Tables\MailResponseListDatatable;
use App\Datatables\Tables\NoRecivedDatatable;
use App\Datatables\Tables\RecivedDatatable;
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
use Knp\Snappy\Pdf;
use Symfony\Component\HttpKernel\KernelInterface;

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

    /** @var Pdf */
    private $pdf;

    /** @var KernelInterface */
    private $kernel;


    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        UploaderHelper $vich,
        Pdf $pdf, KernelInterface $kernel
    ) {

        $this->dispatcher = $eventDispatcher;
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->vich = $vich;
        $this->pdf = $pdf;
        $this->kernel = $kernel;
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
     * @return Response
     * 
     * @Route("/print/{uuid}", name="mail_response_print", options={"expose" = true}, methods={"GET","POST"})
     */
    public function print(MailResponse $response):Response{
        $web_uploads_Path = $this->kernel->getProjectDir() . '/public/uploads/';
        $path = 'pdf/';
        $documento_nombre = 'trabajo.pdf';

        $this->pdf->generateFromHtml(
            $this->render(
                'mail/print.html.twig', [
                    'response' => $response,
                ]
            )->getContent(),
            $web_uploads_Path . $path . $documento_nombre,
            ['encoding' => 'utf-8'],
            true);
            
            return $this->render('pdf_templates/iframe.html.twig', [
                'pdf' => '/uploads/' . $path . $documento_nombre,
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

    /**
     * Undocumented function
     *
     * @param Mail $mail
     * @param Request $request
     * @return Response
     * 
     * @Route("/response-check/{uuid}", name="mail_response_check-response", options={"expose" = true}, methods={"GET","POST"})
     */
    public function existResponse(Mail $mail, Request $request):Response{
        $em = $this->getDoctrine()->getManager();
        $exist = false;
        $nota = 0;
        if( null != $response = $em->getRepository(MailResponse::class)->findByMailAndUser($mail, $this->getUser())){
            $exist = true;
            $nota = $response->getEvaluation();
        }
        return new JsonResponse([
            'type' => 'success',
            'data' => $exist,
            'nota' => ($nota != null && $nota > 0)
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param UserGroup $userGroup
     * @return Response
     * 
     * @Route("/recived-homework/{uuid}", name="recived-homework", options={"expose" = true}, methods={"GET","POST"})
     */
    public function recivedHomework(Request $request, UserGroup $userGroup):Response{
        $datatable = $this->datatableFactory->create(RecivedDatatable::class);
        $datatable->buildDatatable([
            'url' => $this->generateUrl('recived-homework',[
                'uuid' => $userGroup->getUuid()
            ]),
            'vich' => $this->vich
        ]);

        if($request->isXmlHttpRequest() && $request->isMethod('POST')){
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            $qb
            ->getQb()
            ->join('mailresponse.mail','mail')
            ->join('mail.userGroup', 'userGroup')
            ->where('userGroup =:g AND mail.homework =:t')
            ->setParameter('t',true)
            ->setParameter('g',$userGroup)
            ;   

            return $this->datatableResponse->getResponse();
        }

        return $this->render('mail/default_table.html.twig',[
            'datatable' => $datatable
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param UserGroup $userGroup
     * @return Response
     * 
     * @Route("/no-recived-homework/{uuid}", name="no-recived-homework", options={"expose" = true}, methods={"GET","POST"})
     */
    public function noRecivedHomeWork(Request $request, UserGroup $userGroup):Response{
        $datatable = $this->datatableFactory->create(NoRecivedDatatable::class);
        $datatable->buildDatatable([
            'url' => $this->generateUrl('no-recived-homework',[
                'uuid' => $userGroup->getUuid()
            ]),
        ]);

        if($request->isXmlHttpRequest() && $request->isMethod('POST')){
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            $qb
            ->getQb()
            ->select('user, mail2')
            ->join('user.userGroups', 'groups')
            ->join('groups.mails', 'mail')
            ->join('user.mailResponses', 'response')
            ->join('response.mail', 'mail2')
            ->where('groups =:g')
            ->andWhere('mail2.userGroup !=:g')
            ->setParameter('g',$userGroup)
            ;   

            return $this->datatableResponse->getResponse();
        }

        return $this->render('mail/default_table.html.twig',[
            'datatable' => $datatable
        ]);
    }
}
