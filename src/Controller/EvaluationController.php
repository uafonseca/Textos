<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Question;
use App\Entity\Unit;
use App\Form\EvaluationType;
use App\Form\QuestionType;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evaluation")
 */
class EvaluationController extends AbstractController
{
    /**
     * @Route("/", name="evaluation_index", methods={"GET"})
     */
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    /**
     * @param \App\Entity\Unit $unit
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/table/{uuid}", name="evaluation_table", methods={"GET"}, options={"expose" = true})
     */
    public function evaluationsByUnit(Unit $unit): Response{
        $em = $this->getDoctrine()->getManager();
        return $this->render('evaluation/table.html.twig',[
            'evaluations' => $em->getRepository(Evaluation::class)->findBy([
                'unit' => $unit
            ])
        ]);
    }

    /**
     * @param \App\Entity\Evaluation $evaluation
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add-question/{uuid}", name="evaluation_add_question", methods={"GET","POST"}, options={"expose" = true})
     */
    public function addQuestion(Evaluation $evaluation,Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class,$question,[
            'action' => $this->generateUrl('evaluation_add_question',[
                'uuid'=>$evaluation->getUuid()
            ])
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            /** @var \App\Entity\Choice $choice */
            foreach ($question->getChoices() as $choice){
                $choice->setQuestion($question);
            }

            /** @var \App\Entity\SingleQuestion $singleQuestion */
            foreach ($question->getSingleQuestions() as $singleQuestion){
                $singleQuestion->setQuestion($question);
            }
            $em->persist($question);
            $evaluation->addQuestion($question);
            $em->flush();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Pregunta guardada correctamente'
            ]);
        }
        return $this->render('evaluation/add-question.html.twig',[
            'evaluation' => $evaluation,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="evaluation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $evaluation = new Evaluation();
        $entityManager = $this->getDoctrine()->getManager();
        if (null != $request->query->get('unit')) {
            $evaluation->setUnit($entityManager->getRepository(Unit::class)->find($request->query->get('unit')));
        }
        $form = $this->createForm(EvaluationType::class, $evaluation,[
            'action' => $this->generateUrl('evaluation_new',[
                'unit' => $request->query->get('unit')
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos guardados'
            ]);
        }

        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evaluation_show", methods={"GET"})
     */
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evaluation $evaluation): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evaluation_index');
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evaluation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Evaluation $evaluation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evaluation_index');
    }
}
