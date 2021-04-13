<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 3:17 PM
 */

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CompanyRepository;
use App\Repository\SlideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
			'books' => $bookRepository->findAll(),
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
		$all = $repository->findAll ();

		$name = '';
		if (count ($all) > 0) $name = $all[0];

		return new Response($name->getName (), 200);
	}
}
