<?php

namespace App\Controller;

use App\Entity\Certificate;
use App\Entity\Evaluation;
use App\Form\CertificateType;
use App\Repository\CertificateRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/certificate")
 */
class CertificateController extends AbstractController
{

    	
	/**
	 * pdf
	 *
	 * @var mixed
	 */
	private $pdf;
	
	/**
	 * kernel
	 *
	 * @var mixed
	 */
	private $kernel;
    
    /**
     * Method __construct
     *
     * @param Pdf $pdf [explicite description]
     * @param KernelInterface $kernel [explicite description]
     *
     * @return void
     */
    public function __construct(Pdf $pdf, KernelInterface $kernel){
		$this->pdf = $pdf;
        $this->kernel = $kernel;
	}
    /**
     * @Route("/", name="certificate_index", methods={"GET"})
     */
    public function index(CertificateRepository $certificateRepository): Response
    {
        return $this->render('certificate/index.html.twig', [
            'certificates' => $certificateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="certificate_new", methods={"GET","POST"})
     */
    public function new(Request $request, Evaluation $evaluation): Response
    {
        if($evaluation->getCertificateObj()){
            $certificate = $evaluation->getCertificateObj();
        }else{ 
            $certificate = new Certificate();
        }
            
        $form = $this->createForm(CertificateType::class, $certificate,[
            'action' => $this->generateUrl('certificate_new',[
                'id' => $evaluation->getId()
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $evaluation->setCertificateObj($certificate);
            $certificate->setEvaluation($evaluation);
            $entityManager->persist($certificate);
            $entityManager->flush();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos guardados correctamente',
            ]);
        }

        return $this->render('certificate/new.html.twig', [
            'certificate' => $certificate,
            'form' => $form->createView(),
        ]);
    }

        
    /**
     * Method generate
     *
     * @param Certificate $certificate [explicite description]
     *
     * @return void
     * 
     * @Route("/generate/{id}", name="certificate_generate", methods={"GET"})
     */
    public function generate(Certificate $certificate){
        $web_uploads_Path = $this->kernel->getProjectDir() . '/public/uploads/';
        $path = 'pdf/';
        $documento_nombre = 'reporte.pdf';


        $this->pdf->generateFromHtml(
            $this->render(
                'certificate/generate.html.twig', [
                    'certificate' => $certificate,
                ]
            )->getContent(),
            $web_uploads_Path . $path . $documento_nombre,
            ['encoding' => 'utf-8'],
            true);

        return $this->render('pdf_templates/iframe.html.twig', [
            'pdf' => '/uploads/' . $path . $documento_nombre,
        ]);
    }

    /**
     * @Route("/{id}", name="certificate_show", methods={"GET"})
     */
    public function show(Certificate $certificate): Response
    {
        return $this->render('certificate/show.html.twig', [
            'certificate' => $certificate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="certificate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Certificate $certificate): Response
    {
        $form = $this->createForm(CertificateType::class, $certificate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('certificate_index');
        }

        return $this->render('certificate/edit.html.twig', [
            'certificate' => $certificate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="certificate_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Certificate $certificate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$certificate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($certificate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('certificate_index');
    }
}
