<?php

namespace App\Controller;

use App\AppEvents;
use App\Datatables\Tables\UserDatatable;
use App\Datatables\Tables\UsersGroupDatatable;
use App\Entity\Book;
use App\Entity\Mail;
use App\Entity\MailResponse;
use App\Entity\Role;
use App\Entity\Terms;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Event\UserEvent;
use App\Form\User1Type;
use App\Form\UserCreationType;
use App\Form\UserPromoteType;
use App\Repository\BookRepository;
use App\Repository\TermsRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /** @var DatatableFactory */
    private $datatableFactory;

    /** @var DatatableResponse */
    private $datatableResponse;

    /** @var BookRepository */
    private $bookRepository;

    private EventDispatcherInterface $dispatcher;

    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserController constructor.
     *
     * @param DatatableFactory $datatableFactory
     * @param DatatableResponse $datatableResponse
     * @param BookRepository $bookRepository
     */
    public function __construct(
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        BookRepository $bookRepository,
        EventDispatcherInterface $eventDispatcher,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->bookRepository = $bookRepository;
        $this->dispatcher = $eventDispatcher;
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/", name="user_index", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @return Response
     * @throws Exception
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
            'usersDatatable' => $userDatatable
        ]);
    }

    /**
     * @Route("/control", name="user_control", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function userControl(Request $request): Response
    {
        $userDatatable = $this->datatableFactory->create(UserDatatable::class);

        $userDatatable->buildDatatable([
            'url' => $this->generateUrl('user_control'),
            'details' => true,
        ]);

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $this->datatableResponse->setDatatable($userDatatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();
            $qb
            ->getQb()
            ->join('user.student', 'estudiante')
            ->where('estudiante is not NULL')
            ;   

            return $this->datatableResponse->getResponse();
        }

        return $this->render('user/index.html.twig', [
            'usersDatatable' => $userDatatable
        ]);
    }



    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserCreationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($user->getPlainPassword());
            $password = $this->passwordEncoder->encodePassword ($user, $user->getPassword());
			$user->setPassword ($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->dispatcher->dispatch(new UserEvent($user), AppEvents::SEND_DATA_REQUEST);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @param User $user
     * @return Response
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
     * @param Request $request
     * @param User $user
     * @return Response
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
     * @Route("remove/{uuid}", name="user_delete")
     * IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
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
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
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
     * Undocumented function
     *
     * @return Response
     * 
     * @Route("/students", name="students_list", methods={"POST","GET"})
     */
    public function listStudents():Response{
        $loggedUser = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->allUsers($loggedUser);

        return $this->render('user/list.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/dashboard", name="user_dashboard", methods={"POST","GET"},options={"expose" = true})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function userDashboard(): Response
    {
        $loggedUser = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if($this->isGranted('ROLE_PROFESOR')){
            $groups = $em->getRepository(UserGroup::class)->findByUser($loggedUser);

            $users = $em->getRepository(User::class)->allUsers($loggedUser);
    
            $mailsSend = $em->getRepository(Mail::class)->findBySender($loggedUser);
    
            $mailsRecieved = $em->getRepository(MailResponse::class)->findToUser($loggedUser);
        }else{
            $groups = $em->getRepository(Book::class)->getBooks($loggedUser);
    
            $users= [];

            $mailsSend = $em->getRepository(MailResponse::class)->findBy([
                'User' => $loggedUser
            ]);
    
            $mailsRecieved = $em->getRepository(Mail::class)->findByRecieved($loggedUser);
        }

        

        return $this->render('user/dashboard.html.twig', [
            'books' => $this->bookRepository->getBooks($loggedUser),
            'groups' => count($groups),
            'users' => count($users),
            'mailsSend' => count($mailsSend),
            'mailsRecieved' => count($mailsRecieved)
        ]);
    }
    /**
     * @Route("/capacitador/dashboard", name="capacitador_dashboard", methods={"POST","GET"},options={"expose" = true})
     * @IsGranted("ROLE_CAPACITADOR_EXTERNO")
     */
    public function capacitadorDashboard(): Response
    {
        $loggedUser = $this->getUser();

        return $this->render('user/dashboard.html.twig', [
            'books' => $this->bookRepository->getBooks($loggedUser)
        ]);
    }


    /**
     * Undocumented function
     *
     * @return Response
     * @Route("/my-class", name="my-class", methods={"POST","GET"},options={"expose" = true})
     * @IsGranted("ROLE_PROFESOR")
     */
    public function myClass():Response{
        $loggedUser = $this->getUser();

        return $this->render('user/my-class.html.twig',[
            'books' => $this->bookRepository->getMyBooks($loggedUser)
        ]);
    }


    /**
     * Undocumented function
     *
     * @param UserGroup $userGroup
     * @return Response
     * 
     * @Route("/class/{uuid}", name="show_class", methods={"POST","GET"}, options={"expose" = true})
     * @IsGranted("ROLE_PROFESOR")
     */
    public function visitClass(UserGroup $userGroup, Request $request):Response{

        $usersDatatable = $this->datatableFactory->create(UsersGroupDatatable::class);
        $usersDatatable->buildDatatable([
            'url' => $this->generateUrl('show_book_users', [
                'id' => $userGroup->getCourse()->getId()
            ]),
            'actions' => 'hide'
        ]);

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $this->datatableResponse->setDatatable($usersDatatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();

            $qb
                ->getQb()
                ->join('user.userGroups', 'userGroup')
                ->where('userGroup.course =:book')
                ->setParameter('book', $userGroup->getCourse());


            return $this->datatableResponse->getResponse();
        }

        return $this->render('user/class.html.twig',[
            'group' => $userGroup,
            'userDatatable' => $usersDatatable
        ]);
    }
 
}
