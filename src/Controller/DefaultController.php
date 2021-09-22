<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 3:17 PM
 */

namespace App\Controller;

use Amp\Http\Client\Request;
use App\Entity\Terms;
use App\Repository\BookRepository;
use App\Repository\CompanyRepository;
use App\Repository\SlideRepository;
use App\Repository\TermsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home_page")
     * @param BookRepository $bookRepository
     * @param SlideRepository $slideRepository
     * @return Response
     */
	public function home(BookRepository $bookRepository,SlideRepository $slideRepository): Response
	{
	
		return $this->render('home.html.twig',[
			'books' => $bookRepository->getBoksByLimit(8),
            'count' => $bookRepository->getTotalBooks(),
            'slides' => $slideRepository->findAll()
		]);
	}

    /**
     * @Route("/app_cpmpany_name", name="app_cpmpany_name")
     * @param CompanyRepository $repository
     * @return Response
     */
	public function getCompanyName(CompanyRepository $repository): Response
    {
		$all = $repository->findAll();

		$company = '';
		if (count ($all) > 0) $company = $all[0];

		return new Response($company, 200);
	}
       /**
	 * Undocumented function
	 *
	 * @param Request $request
	 * @param TermsRepository $termsRepository
	 * @return Response
	 * 
	 * 
	 * @Route("/terms-and-conditions", name="terms-and-conditions", options={"expose" = true})
	 * 
	 */
	public function showDialogs(HttpFoundationRequest $request, TermsRepository $termsRepository):Response{
		$all = $termsRepository->findAll ();
		$terms =  new Terms();
		if (count ($all) > 0){
			$terms =  $all[0];
		}
		if(!$terms instanceof Terms){
			return new Response('Estos datos no se han configurado aún.!!!');
		}
		return $this->render('partials/dialog-template.htm.twig',[
			'title' => $request->query->get('type') === 'terms' ? 'Términos y condiciones' : 'Política de privacidad',
			'body'=> $request->query->get('type') === 'terms' ? $terms->getTerms() : $terms->getPrivacy()
		]);
	}
}
