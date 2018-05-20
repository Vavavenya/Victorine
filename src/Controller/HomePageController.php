<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 10.05.2018
 * Time: 11:46
 */

namespace App\Controller;


use App\Entity\Quiz;
use App\MyClass\CollectionCounter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class HomePageController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function homePage()
    {
        //take all the quizzes and update their leaderboard
        $entityManager = $this->getDoctrine()->getManager();
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAll();

        if (!$quiz) {
        throw $this->createNotFoundException( 'No quiz found');
        }

        foreach ($quiz as $onequiz) {
            $bestplayer=$onequiz->getLeaders()[0]->getUser()->getUsername();
            $collectioncounter=new CollectionCounter();
            $collectioncounter->setObject($onequiz);

            //save leaders
            $onequiz  ->setNumPlayers($collectioncounter->SizeObject());
            $entityManager->persist($onequiz);
            $entityManager->flush();
        }

        //homepage
        return $this->render('homepage/homepage.html.twig', array('quiz' => $quiz));
    }
}