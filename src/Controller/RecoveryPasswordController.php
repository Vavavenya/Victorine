<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 20:04
 */

namespace App\Controller;

use App\Form\RecoveryPasswordEmailType;
use App\Form\RecoveryPasswordType;
use App\Entity\User;
use App\MyClass\MailSender;
use App\MyClass\TokenEditor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RecoveryPasswordController extends Controller
{
    /**
     * @Route("/recovery", name="recovery_password")
     */
    public function recoveryPassword(Request $request, \Swift_Mailer $mailer)
    {
        //verification login
        if ($this->getUser()) {
            throw $this->createNotFoundException(
                'no access'
            );
        }

        //email input form
        $form = $this->createForm(RecoveryPasswordEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the user to assign him a token on which the router will be available
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneByEmail($form->get('Email')->getData());

            if (!$user) {
                throw $this->createNotFoundException(
                    'No user found by email'.$form->get('Email')->getData()
                );
            }

            //generate token
            $tokeneditor=new TokenEditor();
            $token = $tokeneditor->getToken();
            $user->setToken($token);

            //assign a token
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //generate and send messages to the mail
            $mailsender=new MailSender();
            $url = 'http://quiz.home/recovery/' . $user->getToken();
            $mailsender->setText($url);
            $mailsender-> setSendTo($form->get('Email')->getData());
            $mailsender->sendMessage($mailer);

            //page of successful message sending
            return $this->render('recovery/emailsendmessage.html.twig');
        }

        //page with email form
        return $this->render(
            'recovery/email.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/recovery/{slug}", name="recovery_password_end")
     */
    public function recoveryPasswordForm( Request $request, UserPasswordEncoderInterface $passwordEncoder,String $slug)
    {
        //get the user token from the link
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $slug]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found by token '.$slug
            );
        }

        //a form for entering a new password
        $form = $this->createForm(RecoveryPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //encode password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setToken('');

            //save the password and reset the token
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //successful password change page
            return $this->render('recovery/success.html.twig');
        }

        //password change page
        return $this->render(
            'recovery/recoverypassword.html.twig',
            array('form' => $form->createView()));
    }


}