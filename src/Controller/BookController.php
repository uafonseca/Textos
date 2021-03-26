<?php

namespace App\Controller;

use App\Datatables\Tables\BookDatatable;
use App\Entity\Book;
use App\Entity\Company;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\CodeRepository;
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

    /**
     * UserController constructor.
     *
     * @param DatatableFactory  $datatableFactory
     * @param DatatableResponse $datatableResponse
     * @param CodeRepository $CodeRepository
     */
    public function __construct(
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        CodeRepository $codeRepository
    ) {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->codeRepository = $codeRepository;
    }

    /**
     * @Route("/", name="book_index", methods={"GET", "POST"})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
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
     * @Route("/new", name="book_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
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
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function show(Book $book): Response
    {
        if(null != $code = $this->codeRepository->isBookActive($book,$this->getUser())){
            return $this->render('book/show.html.twig', [
                'book' => $book,
            ]);
        }
        return $this->redirectToRoute('book_activate',[
            'uuid' => $book->getUuid(),
        ]);
    }

     /**
     * @Route("/activate/{uuid}", name="book_activate", methods={"GET","POST"}, options={"expose" = true})
     * 
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function activateBook(Book $book, Request $request){

        if($request->isXmlHttpRequest()){
            $key = $request->request->get('code');
            $loggedUser = $this->getUser();
            $code = $this->codeRepository->findCode($book,$key);
            if($code){
                $em = $this->getDoctrine ()->getManager ();

                $code->setUser($loggedUser);
                $em->flush ();
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

        return $this->render('book/activate.html.twig',[
            'book' => $book
        ]);
    }

    /**
     * @Route("/{uuid}/edit", name="book_edit", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
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
