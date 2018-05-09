<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 18:12
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmailVerificationController extends Controller
{

    /**
     * @Route("/mail/{slug}", name="mail_verification")
     */
    public function mailVerification(String $slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $slug]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$slug
            );
        }

        $user->setToken('');
        $user->setIsActive(true);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('registration/email.html.twig');
    }

}