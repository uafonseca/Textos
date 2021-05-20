<?php

namespace App\Controller;

use App\Datatables\Tables\UserGroupDatatable;
use App\Datatables\Tables\UsersGroupDatatable;
use App\Entity\Role;
use App\Entity\UserGroup;
use App\Form\UserGroupType;
use App\Services\ExcelManagerService;
use DateTime;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserGroupController extends AbstractController
{

    /** @var DatatableFactory */
    private $datatableFactory;

    /** @var DatatableResponse */
    private $datatableResponse;

    private ExcelManagerService $excelManager;

    public function __construct(
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        ExcelManagerService $excelManager
    ) {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->excelManager = $excelManager;
    }


    /**
     * @Route("/user/group", name="user_group")
     */
    public function index(Request $request): Response
    {
        $datatable = $this->datatableFactory->create(UserGroupDatatable::class);
        $datatable->buildDatatable([
            'url' => $this->generateUrl('user_group')
        ]);


        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $this->datatableResponse->setDatatable($datatable);
            $this->datatableResponse->getDatatableQueryBuilder();

            return $this->datatableResponse->getResponse();
        }
        return $this->render('user_group/index.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     *  @Route("/user/group/create", name="user_group_create")
     */
    public  function create(Request $request)
    {
        $group = new UserGroup();
        $form = $this->createForm(UserGroupType::class, $group, [
            'edit' => false,
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $file = $form->get('file')->getData();

            $role = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['rolename' => 'ROLE_USER']);

            $excel = $this->excelManager->initializeArchivo($file->getImagenFile(), $this->getUser()->getCompany()->getId(), true, $role, $group);

            $result = $excel->procesarExcel();

            if (isset($result['messages']) && is_array($result['messages']) && count($result['messages']) > 0) {
                foreach ($result['messages'] as $message) {
                    $this->addFlash('error', $message);
                }
            }

            $this->addFlash('info', 'Se cargaron ' . $result['cant_users'] . ' usuarios en el grupo');

            $em->persist($group);
            $em->flush();

            return $this->redirect($this->generateUrl('user_group'));
        }

        return $this->render('user_group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }


    /**
     *  @Route("/user/group/edit/{uuid}", name="user_group_edit")
     */
    public  function edit(UserGroup $group, Request $request)
    {
        $form = $this->createForm(UserGroupType::class, $group, [
            'edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            $this->addFlash('success', 'Datos guardados correctamente');

            $em->persist($group);
            $em->flush();

            return $this->redirect($this->generateUrl('user_group'));
        }

        return $this->render('user_group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/user/group/users/{uuid}", name="users_group_datatable")
     */
    public function loadUsersByGroup(UserGroup $group, Request $request)
    {
        $datatable = $this->datatableFactory->create(UsersGroupDatatable::class);
        $datatable->buildDatatable([
            'url' => $this->generateUrl('users_group_datatable', [
                'uuid' => $group->getUuid(),
            ])
        ]);

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $this->datatableResponse->setDatatable($datatable);
            $qb = $this->datatableResponse->getDatatableQueryBuilder();

            $qb
                ->getQb()
                ->join('user.userGroups', 'userGroup')
                ->where('userGroup =:group')
                ->setParameter('group', $group);


            return $this->datatableResponse->getResponse();
        }
        return $this->render('user_group/usersDatatable.html.twig', [
            'datatable' => $datatable
        ]);
    }


    /**
     * @Route("/user/group/remove/{uuid}", name="users_group_remove")
     */
    public function removeUserGroup(UserGroup $group)
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($group);
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
            'no_reload' => true
        ]);
    }
}
