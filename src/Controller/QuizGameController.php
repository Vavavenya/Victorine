<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 13.05.2018
 * Time: 14:49
 */

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\AnswerType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AnswerRepository;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class QuizGameController extends Controller
{
    /**
     * @Route("/quiz/{id}/{slug}", name="quiz_game")
     */
    public function QuizPage($id, $slug,Request $request)
    {
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);
        $question= $quiz->getQuestion()[$slug];
        if (!$question) {
            return $this->render('recovery/success.html.twig');
        }
        $allanswer=$question->getAnswers();
            $allplayer= $question->getPlayers();
            if ($allplayer->isEmpty() == false) {
                foreach ($allplayer as $oneplayer) {
                    if ($oneplayer->getUser()->getId() == $this->getUser()->getId() ) {
                        $slug++;
                        return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug));
                    }
                }
            }
            $task=new Answer();
        $form = $this->createForm(AnswerType::class, array('id' => $id,'slug' => $slug));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityObject = $form->get('users')->getData()->getId();
            $time='15:15';

            foreach ($allanswer as $answer){
                    if ($answer->getId()==$entityObject) {
                        break;
                    }
            }

            $player=new Player();
            $player->setQuiz($quiz)
                    ->setQuestion($question)
                    ->setAnswer($answer)
                    ->setTime(\DateTime::createFromFormat('H:i', $time))
                    ->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            $slug++;
            return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug));

        }
        //главная страница
        return $this->render('quiz/quizquestion.html.twig',
            array('form' => $form->createView(),'quiz' => $quiz,'question' => $question));
    }
}