<?php
/**
 * Created by PhpStorm.
 * Name:  Ubel Angel Fonseca Cedeño
 * Email: ubelangelfonseca@gmail.com
 * Date:  29/4/21
 * Time:  10:18
 */

namespace App\Twig;


use App\Entity\Evaluation;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EvaluationsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getPoints', [$this, 'getPoints']),
        ];
    }

    /**
     * @param \App\Entity\Evaluation $evaluation
     * @return string[]
     */
    public function getPoints(Evaluation $evaluation): array
    {
        $points = 0;
        $max = $evaluation->getPercentage();
        foreach ($evaluation->getAnswers() as $answer) {
            foreach ($answer->getAnswerQuestions() as $answerQuestion) {
                foreach ($answerQuestion->getChoicesAnswers() as $choicesAnswer)
                    if ($choicesAnswer->getIsSelected()){
                        if ($choicesAnswer->getChoice()->getIsCorrect())
                            $points += $choicesAnswer->getChoice()->getValue();
                    }

            }
        }

        return [
            'result' => "$points/$max",
            'status' => $points >= $max ? '<span class="text-success">APROBADO</span>' : '<span class="text-danger">NO APROBADO</span>'
        ];
    }
}