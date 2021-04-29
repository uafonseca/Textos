<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\AnswerQuestion;
use App\Entity\Choice;
use App\Entity\ChoiceAnswer;
use App\Entity\Evaluation;
use App\Entity\Question;
use App\Entity\SingleQuestion;
use App\Entity\Unit;
use App\Exception\AnswerRewriteException;
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
    public function evaluationsByUnit(Unit $unit): Response
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('evaluation/table.html.twig', [
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
    public function addQuestion(Evaluation $evaluation, Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question, [
            'action' => $this->generateUrl('evaluation_add_question', [
                'uuid' => $evaluation->getUuid()
            ]),
            'edit' => false
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \App\Entity\Choice $choice */
            foreach ($question->getChoices() as $choice) {
                $choice->setQuestion($question);
            }

            /** @var \App\Entity\SingleQuestion $singleQuestion */
            foreach ($question->getSingleQuestions() as $singleQuestion) {
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
        return $this->render('evaluation/add-question.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param \App\Entity\Question $question
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/questions/{id}/edit", name="edit-question", methods={"GET","POST"})
     */
    public function editQuestions(Question $question, Request $request): Response
    {
        $form = $this->createForm(QuestionType::class, $question, [
            'edit' => true,
            'action' => $this->generateUrl('edit-question', [
                'id' => $question->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \App\Entity\Choice $choice */
            foreach ($em->getRepository(Choice::class)->findBy([
                'question' => $question
            ]) as $choice) {
                $choice->setQuestion(null);
            }
            foreach ($question->getChoices() as $choice) {
                $choice->setQuestion($question);
            }


            foreach ($em->getRepository(SingleQuestion::class)->findBy([
                'question' => $question
            ]) as $singleQuestion) {
                $singleQuestion->setQuestion(null);
            }
            /** @var \App\Entity\SingleQuestion $singleQuestion */
            foreach ($question->getSingleQuestions() as $singleQuestion) {
                $singleQuestion->setQuestion($question);
            }
            $em->persist($question);
            $em->flush();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Pregunta guardada correctamente'
            ]);
        }
        return $this->render('evaluation/edit-question.html.twig', [
            'question' => $question,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param \App\Entity\Question $question
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/remove/question/{id}", name="remove-question", methods={"GET","POST"})
     */
    public function removeQuestion(Question $question): Response
    {
        $em = $this->getDoctrine()->getManager();
        $question->getEvaluation()->removeQuestion($question);
        $em->remove($question);
        $em->flush();
        return new JsonResponse([
            'type' => 'success',
            'message' => 'Pregunta eliminada correctamente'
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
        $form = $this->createForm(EvaluationType::class, $evaluation, [
            'action' => $this->generateUrl('evaluation_new', [
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
     * @Route("/{uuid}", name="evaluation_show", methods={"GET"})
     * @throws \App\Exception\AnswerRewriteException
     */
    public function show(Evaluation $evaluation): Response
    {
        /** @var \App\Entity\User $loggedUser */
        $loggedUser = $this->getUser();

        $exist = false;
        $userAnswer = null;
        /** @var \App\Entity\Answer $answer */
        foreach ($evaluation->getAnswers() as $answer)
            if ($answer->getOwner() === $loggedUser && $answer->getEndDate()) {
                throw new AnswerRewriteException();
            } else if ($answer->getOwner() === $loggedUser) {
                $exist = true;
                $userAnswer = $answer;
            }

        if (!$exist) {
            $userAnswer = $this->buildAnswer($evaluation);
        }

        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
            'answer' => $userAnswer,
            'exist' => $exist
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/save-answer", name="save-answer", methods={"GET","POST"}, options={"expose" = true})
     */
    public function saveAnswer(Request $request): Response
    {
        $choicesAnswers = $request->request->get('choicesAnswers');
        $choicesValues = $request->request->get('choicesValues');
        $choicesTextAnswers = $request->request->get('choicesTextAnswers');
        $choicesTextValues = $request->request->get('choicesTextValues');

        $em = $this->getDoctrine()->getManager();
        foreach ($choicesAnswers as $key => $id){
            /** @var ChoiceAnswer $choiceObj */
            $choiceObj = $em->getRepository(ChoiceAnswer::class)->find($id);
            $choiceObj->setIsSelected($choicesValues[$key] != 0);
        }
        foreach ($choicesTextAnswers as $key => $id){
            /** @var ChoiceAnswer $choiceObj */
            $choiceObj = $em->getRepository(ChoiceAnswer::class)->find($id);
            $choiceObj->setSingleAnswerText($choicesTextValues[$key]);
        }
        $answer = $em->getRepository(Answer::class)->find($request->request->get('ANSWER_ID'));
        $answer->setAttemptsMade($answer->getAttemptsMade() + 1);
        $em->flush();
        return new JsonResponse([
            'type' => 'success',
            'message' => 'Datos enviados'
        ]);
    }

    /**
     * @param \App\Entity\Evaluation $evaluation
     * @return \App\Entity\Answer
     */
    public function buildAnswer(Evaluation $evaluation): Answer
    {
        /** @var \App\Entity\User $loggedUser */
        $loggedUser = $this->getUser();
        $userAnswer = new Answer();
        $userAnswer
            ->setOwner($loggedUser)
            ->setStartDate(new \DateTime())
            ->setCompany($loggedUser->getCompany())
            ->setOwner($loggedUser)
            ->setEvaluation($evaluation);
        $evaluation->addAnswer($userAnswer);
        $loggedUser->addAnswer($userAnswer);
        $em = $this->getDoctrine()->getManager();
        $em->persist($userAnswer);

        /** @var Question $question */
        foreach ($evaluation->getQuestions() as $question) {
            $answerQuestion = new AnswerQuestion();
            $answerQuestion
                ->setQuestion($question)
                ->setAnswer($userAnswer)
                ->setCompany($userAnswer->getCompany());
            $em->persist($answerQuestion);
            $userAnswer->addAnswerQuestion($answerQuestion);
            foreach ($question->getChoices() as $choice) {
                $choiceAnswer = new ChoiceAnswer();
                $choiceAnswer
                    ->setChoice($choice)
                    ->setAnswerQuestion($answerQuestion);
                $answerQuestion->addChoicesAnswer($choiceAnswer);
            }
            foreach ($question->getSingleQuestions() as $singleQuestion) {
                $choiceAnswer = new ChoiceAnswer();
                $choiceAnswer
                    ->setSingleAnswer($singleQuestion)
                    ->setAnswerQuestion($answerQuestion);
                $answerQuestion->addChoicesAnswer($choiceAnswer);
                $em->persist($answerQuestion);
            }
        }
        $em->flush();

        return $userAnswer;
    }

    /**
     * @Route("/{uuid}/edit", name="evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evaluation $evaluation): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation, [
            'action' => $this->generateUrl('evaluation_edit', [
                'uuid' => $evaluation->getUuid()
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Datos modificados correctamente',
            ]);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="evaluation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Evaluation $evaluation): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($evaluation);
        $entityManager->flush();

        return new JsonResponse([
            'type' => 'success',
            'message' => 'Datos eliminados',
        ]);
    }
}
