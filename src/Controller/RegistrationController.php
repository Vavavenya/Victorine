<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 16:45
 */

namespace App\Controller;


use App\Form\UserType;
use App\Entity\User;
use App\MyClass\MailSender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        //check for login
        if ($this->getUser()) {
            throw $this->createNotFoundException(
                'no access'
            );
        }
        $user = new User();

        //registration form
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //encode  password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // save user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //generate email to verify your account and send
            $url = 'http://quiz.home/register/mail/' . $user->getToken();
            $mailsender=new MailSender();
            $mailsender->setText($url);
            $mailsender-> setSendTo($form->get('email')->getData());
            $mailsender->sendMessage($mailer);

            //page of successful registration and sending a message
            return $this->render('registration/emailsendmessage.html.twig');
        }

        //registration page
        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/register/mail/{slug}", name="mail_verification")
     */
    public function mailVerification(String $slug)
    {
        //get the user token from the link
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $slug]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for token '.$slug
            );
        }

        //reset the token and give the user the role
        $user->setToken('');
        $user->setRoles('ROLE_USER');
        $entityManager->persist($user);
        $entityManager->flush();

        //successful verification page
        return $this->render('registration/emailversuccess.html.twig');
    }
}