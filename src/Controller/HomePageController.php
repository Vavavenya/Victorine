<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 10.05.2018
 * Time: 11:46
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomePageController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function homePage()
    {
        return $this->render('homepage/homepage.html.twig');
    }
}