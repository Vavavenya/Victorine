<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 10:03
 */

namespace App\Controller;

use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VictorineTableController extends Controller
{
    /**
     * @Route("/admin/VictorineTable", name="Victorine_Table")
     */
    public function VictorineTable()
    {
    	 	$quiz = $this->getDoctrine()
    	->getRepository(Quiz::class)
    	->findAll();

    	return $this->render('admin/VictorineTable.html.twig',array('quiz' => $quiz));
    }
  }