<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 20:04
 */

namespace App\Controller;

use App\Form\lol;
use App\Form\RecoveryPasswordEmailType;
use App\Form\RecoveryPasswordType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\Mapping as ORM;

class RecoveryPasswordController extends Controller
{
    /**
     * @Route("/recovery", name="recovery_password")
     */
    public function recoveryPassword(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser()->getUserName();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByUsername($user);
            /*->findAll();

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for name '.$user
            );
        }
        for($i=0; $i<count($user);$i++){
            echo '<pre>';
            echo $user[$i]->getUserName();
            echo '</pre>';
        };*/


        $token = str_replace("/", "", password_hash(  rand(0, 10000) , PASSWORD_DEFAULT));

        $this->getUser()->setToken($token);

        $entityManager = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $URL = 'http://quiz.home/recovery/' . $this->getUser()->getToken();



        $form = $this->createForm(RecoveryPasswordEmailType::class, $user);
        $form->handleRequest($request);

            //форма не валидная, исправить
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            return $this->render('recovery/success.html.twig');
        }
        return $this->render(
            'recovery/email.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/recovery/{slug}", name="recovery_password_end")
     */
    public function recoveryPasswordForm( Request $request, UserPasswordEncoderInterface $passwordEncoder,String $slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $slug]);
        $form = $this->createForm(RecoveryPasswordType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the use


//вернуть страницу суксесса

            return $this->render('recovery/success.html.twig');
        }
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$slug
            );
        }
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render(
            'recovery/recoverypassword.html.twig',
            array('form' => $form->createView()));
    }


}