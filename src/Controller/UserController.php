<?php

namespace App\Controller;

use App\Datatables\Tables\UserDatatable;
use App\Entity\Role;
use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserPromoteType;
use App\Repository\UserRepository;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /** @var DatatableFactory */
    private $datatableFactory;

    /** @var DatatableResponse */
    private $datatableResponse;

    /**
     * UserController constructor.
     *
     * @param DatatableFactory  $datatableFactory
     * @param DatatableResponse $datatableResponse
     */
    public function __construct(DatatableFactory $datatableFactory, DatatableResponse $datatableResponse)
    {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
    }


    /**
     * @Route("/", name="user_index", methods={"GET","POST"})
     *  @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function index(Request $request): Response
    {
        $userDatatable = $this->datatableFactory->create(UserDatatable::class);

        $userDatatable->buildDatatable([
            'url' => $this->generateUrl('user_index')
        ]);

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $this->datatableResponse->setDatatable($userDatatable);
            $this->datatableResponse->getDatatableQueryBuilder();

            return $this->datatableResponse->getResponse();
        }

        return $this->render('user/index.html.twig', [
            'datatable' => $userDatatable
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     *  @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}/promote", name="user_promote", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     * 
     */
    public function promote(Request $request, User $user): Response
    {
        $form = $this->createForm(UserPromoteType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Role $role */
            foreach ($user->getRolesObject() as $role) {
                $role->addUser($user);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/promote.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * IsGranted("ROLE_SUPER_ADMIN")
     *  
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @param User $user
     *
     * @Route("/{uuid}/profile", name="user_profile", methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profile(User $user, Request $request)
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_profile', [
                'uuid' => $user->getUuid()
            ]);
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard", name="user_dashboard", methods={"POST","GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function userDashboard(){
        return $this->render('user/dashboard.html.twig', [
            
        ]);
    }
}
