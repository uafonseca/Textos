<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\Invitation;
use App\Entity\User;
use App\Entity\UserGroup;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return Response
     * 
     * @Route("/add-new-code", name="add-new-code", options={"expose" = true})
     */
    public function activate(Request $request):Response{
        $uuid = $request->query->get('uuid');

        if($uuid != null && $request->isXmlHttpRequest()){
            
            $em = $this->getDoctrine()->getManager();
            
            if (null != $group = $em->getRepository(UserGroup::class)->findOneBy(['uuid'=> $uuid])){
                /** @var User $user */
                $user = $this->getUser();
                $exist = $em->getRepository(Invitation::class)->findByUser($user, $group);
                if(null != $exist){
                    return new JsonResponse([
                        'type' => 'error',
                        'message' => 'Este código ha sido usado anteriormente'
                    ]);
                }
                $invitation = new Invitation();
                $invitation
                ->setUser($user)
                ->setUserGroup($group)
                ->setAcept(true);
                $em->persist($invitation);
    
                $user->addUserGroup($group);
                $group->addUser($user);
    
                $code = new Code();
                $code->setCode(uniqid())
                    ->setBook($group->getCourse())
                    ->setStarDate($group->getStartDate())
                    ->setUnlimited(true)
                    ->setUser($user)
                    ->setFree(true)
                    ;
                $em->persist($code);
                $em->flush();
    
                $this->addFlash('success','Invitación aceptada');
                $url = $this->generateUrl('user_dashboard');
                return new JsonResponse([
                    'type' => 'success',
                    'url' => $url
                ]);
            }else{
                return new JsonResponse([
                    'type' => 'error',
                    'message' => 'Este código no es correcto'
                ]);
            }
        }
        return $this->render('invitation/activate.html.twig');
    }
}