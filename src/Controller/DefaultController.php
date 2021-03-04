<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 3:17 PM
 */

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
	/**
	 * @Route("/", name="app_home_page")
	 */
	public function home(BookRepository $bookRepository): Response
	{
		return $this->render('home.html.twig',[
			'books' => $bookRepository->findAll()
		]);
	}
}
