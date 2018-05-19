<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 10.05.2018
 * Time: 11:46
 */

namespace App\Controller;


use App\Entity\Quiz;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class HomePageController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function homePage()
    {
        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAll();
        if (!$quiz) {
        throw $this->createNotFoundException(
        'No quiz found'
        );
        }
        //главная страница
        return $this->render('homepage/homepage.html.twig', array('quiz' => $quiz,'leaders' => $quiz[0]->getLeaders() ));
    }
}