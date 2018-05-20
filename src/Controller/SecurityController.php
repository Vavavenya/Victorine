<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 10:10
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        //check for login
        if ($this->getUser()) {
            throw $this->createNotFoundException(
                'no access'
            );
        }
        //we receive a login error
        $error = $authenticationUtils->getLastAuthenticationError();

        //last username
        $lastUsername = $authenticationUtils->getLastUsername();

        //login page
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}