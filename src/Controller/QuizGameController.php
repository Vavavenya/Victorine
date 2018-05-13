<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 13.05.2018
 * Time: 14:49
 */

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuizGameController extends Controller
{
    /**
     * @Route("/quiz/{id}/{slug}", name="quiz_game")
     */
    public function homePage($id, $slug)
    {
        $quiz = $this->getDoctrine()
        ->getRepository(Quiz::class)
        ->find($id);
        $question= $this->getDoctrine()
            ->getRepository(Question::class)
            //->find($id)
            ->findOneBy(['quiz' => $id,
                'id' => $slug,
                ]);
        $answer=$question->getAnswers();
        if (!$quiz) {
            throw $this->createNotFoundException(
                'No user found for name '.$quiz
            );
        }
        //главная страница
        return $this->render('quiz/quizquestion.html.twig',
            array('quiz' => $quiz,'question' => $question,'answer' => $answer));
    }
}