<?php

namespace App\Controller;

use App\Entity\Invitation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class InvitationController extends AbstractController
{

    public function __construct(){
		
	}

    /**
     * Undocumented function
     *
     * @return Response
     * 
     * @Route("/invitations", name="invitations_index")
     */
    public function index():Response
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $invitations = $em->getRepository(Invitation::class)->findBy(['user'=>$user]);
        return $this->render('invitation/index.html.twig',[
            'invitations' => $invitations
        ]);
    }
}