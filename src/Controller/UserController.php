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

class UserController extends Controller
{
    /**
     * @Route("/admin")
     */
    public function admin()
    {
        mail('borya.bobroff2014@yandex.ru', 'My Subject', 'hi lol');
        return new Response('<html><body>Admin page!</body></html>');
    }
}