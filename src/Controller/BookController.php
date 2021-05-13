<?php

namespace App\Controller;

use App\Datatables\Tables\BookDatatable;
use App\Entity\Activity;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Level;
use App\Entity\SchoolStage;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\CodeRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{


    /** @var DatatableFactory */
    private $datatableFactory;

    /** @var DatatableResponse */
    private $datatableResponse;

    /** @var CodeRepository */
    private $codeRepository;

    /** @var BookRepository */
    private $bookRepository;

    /**
     * UserController constructor.
     *
     * @param DatatableFactory $datatableFactory
     * @param DatatableResponse $datatableResponse
     * @param CodeRepository $codeRepository
     * @param BookRepository $bookRepository
     */
    public function __construct(
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        CodeRepository $codeRepository,
        BookRepository $bookRepository
    )
    {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->codeRepository = $codeRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("/", name="book_index", methods={"GET", "POST"})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function index(Request $request): Response
    {

        $datatable = $this->datatableFactory->create(BookDatatable::class);

        $datatable->buildDatatable([
            'url' => $this->generateUrl('book_index')
        ]);

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $this->datatableResponse->setDatatable($datatable);
            $this->datatableResponse->getDatatableQueryBuilder();

            return $this->datatableResponse->getResponse();
        }

        return $this->render('book/index.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/list", name="book_list", methods={"GET","POST"}, options={"expose" = true})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $categories = $request->request->get('categories');
            $stages = $request->request->get('stages');
            $levels = $request->request->get('levels');

            $books = $this->bookRepository->findByFilters($categories, $stages, $levels);

            return $this->render('book/ajax-list.html.twig',[
                'books' => $books
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('book/list.html.twig', [
            'default' => $this->bookRepository->getBoksByLimit(),
            'categories' => $em->getRepository(Category::class)->findByCompany($this->getUser() ? $this->getUser()->getCompany() : null),
            'stages' => $em->getRepository(SchoolStage::class)->findByCompany($this->getUser() ? $this->getUser()->getCompany() : null),
            'levels' => $em->getRepository(Level::class)->findByCompany($this->getUser() ? $this->getUser()->getCompany() : null),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     *
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book, [
            'edit' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $book->setCompany($this->getCompany());
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="book_show", methods={"GET"})
     *
     * @param Book $book
     * @return Response
     * @throws NonUniqueResultException
     */
    public function show(Book $book): Response
    {
        $loggedUser = $this->getUser();
        if (!$loggedUser) {
            return $this->render('book/annony.html.twig', [
                'book' => $book,
            ]);
        }
        if (null != $code = $this->codeRepository->isBookActive($book, $this->getUser())) {
            return $this->render('book/show.html.twig', [
                'book' => $book,
            ]);
        }
        return $this->redirectToRoute('book_activate', [
            'uuid' => $book->getUuid(),
        ]);
    }

    /**
     * @Route("/resource/{id}", name="show_resource", methods={"GET","POST"}, options={"expose" = true})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Activity $activity
     * @return Response
     */
    public function showActivity(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', [
            'activity' => $activity
        ]);
    }

    /**
     * @Route("/activate/{uuid}", name="book_activate", methods={"GET","POST"}, options={"expose" = true})
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Book $book
     * @param Request $request
     * @return JsonResponse|Response
     * @throws NonUniqueResultException
     */
    public function activateBook(Book $book, Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $key = $request->request->get('code');
            /** @var \App\Entity\User $loggedUser */
            $loggedUser = $this->getUser();
            /** @var \App\Entity\Code $code */
            $code = $this->codeRepository->findCode($book, $key);

            if ($code) {

                if ($code->getEndDate() < new \DateTime('now'))
                {
                    return new JsonResponse([
                        'type' => 'error',
                        'message' => 'El periodo de vigencia del código ha expirado'
                    ]);
                }

                if ($loggedUser->getCodes()->contains($code))
                    return new JsonResponse([
                        'type' => 'error',
                        'message' => 'No puede activar dos veces el mismo código'
                    ]);

                $em = $this->getDoctrine()->getManager();

                $code->setUser($loggedUser);
                $em->flush();
                return new JsonResponse([
                    'type' => 'success',
                    'message' => 'El libro se ha activado correctamente'
                ]);
            }
            return new JsonResponse([
                'type' => 'error',
                'message' => 'El código de activación no es correcto'
            ]);
        }

        return $this->render('book/activate.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="book_edit", methods={"GET","POST"})
     *
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book, [
            'edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     *
     * @IsGranted("ROLE_SUPER_ADMIN")
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }


    public function getCompany(): Company
    {
        return $this->getUser()->getCompany();
    }
}
