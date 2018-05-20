<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 10:03
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends Controller
{
    /**
     * @Route("/admin", name="Admin_page")
     */
    public function admin()
    {
    	return $this->render('admin/AdminPage.html.twig');
    }
  }