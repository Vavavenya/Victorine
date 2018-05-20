<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 13.05.2018
 * Time: 14:49
 */

namespace App\Controller;

use App\Entity\Leaders;

use App\Entity\Player;
use App\Entity\Quiz;
use App\Form\AnswerType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class QuizGameController extends Controller
{
    /**
     * @Route("/quiz/{id}/{slug}", name="quiz_game", requirements={"id"="\d+","slug"="\d+"})
     */
    public function QuizPage($id, $slug,Request $request)
    {
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);
        if (!$quiz) {
            throw $this->createNotFoundException('No quiz found' );
        };
        if ($quiz->getIsActive() == false) {
            throw $this->createNotFoundException('quiz was disable' );
        };

        $question= $quiz->getQuestion()[$slug];
        if (!$question) {
            return $this->redirectToRoute('quiz_leader', array('id' => $id));
        }

        $allanswer=$question->getAnswers();
        $allplayer= $question->getPlayers();
        $allleader= $quiz->getLeaders();

        //if the user has not played create a field for him
        if ($allplayer->isEmpty() == true) {
            $leader = new Leaders();
            $leader->setQuiz($quiz)
                ->setUser($this->getUser())
                ->setName($this->getUser()->getUsername());
        }

        //find the field of user
        foreach ($allleader as $leader) {
            if ($leader->getUser()->getId() == $this->getUser()->getId()) {
                break;
            }
        }

        //if the user has already passed the question then go to the next
        if ($allplayer->isEmpty() == false) {
            foreach ($allplayer as $oneplayer) {
                if ($oneplayer->getUser()->getId() == $this->getUser()->getId() ) {
                        $slug++;
                        return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug));
                }
            }
        }

        $time=date('H:i:s');
        $form = $this->createForm(AnswerType::class, null , ['id' => $question->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityObject = $form->get('answers')->getData()->getId();

            //find answer
            foreach ($allanswer as $answer){
                    if ($answer->getId()==$entityObject) {
                        break;
                    }
            }

            if ($form->get('next')->isClicked()) {
                $player=new Player();
                $player->setQuiz($quiz)
                    ->setQuestion($question)
                    ->setAnswer($answer)
                    ->setStartTime(\DateTime::createFromFormat('H:i:s', $time))
                    ->setEndTime(\DateTime::createFromFormat('H:i:s', $time))
                    ->setUser($this->getUser());

                if ($player->getAnswer()->getIsRight()==true) {
                    $leader->setAnswered(($leader->getAnswered()+1))
                        ->setCorrect(($leader->getCorrect()+1));
                } else {
                    $leader->setAnswered(($leader->getAnswered()+1));
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($player);
                $entityManager->persist($leader);
                $entityManager->flush();

                return $this->render('quiz/questioniscorrect.html.twig',
                    array('answer' => $answer));
            }

            $slug++;
            return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug));

        }
        //main page
        return $this->render('quiz/quizquestion.html.twig',
            array('form' => $form->createView(),'quiz' => $quiz,'question' => $question));
    }

    /**
     * @Route("/leader/{id}", name="quiz_leader", requirements={"id"="\d+"})
     */
    public function QuizLeader($id)
    {

        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);
        if (!$quiz) {
            throw $this->createNotFoundException('No quiz found' );
        };
        if ($quiz->getIsActive() == false) {
            throw $this->createNotFoundException('quiz was disable' );
        };

        //main page
        return $this->render('quiz/quizleader.html.twig',
            array('leaders' => $quiz->getLeaders(),'quiz' => $quiz, 'user' =>$this->getUser()));
    }
}