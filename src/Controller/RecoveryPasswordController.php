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
        //форма для ввода email
        $form = $this->createForm(RecoveryPasswordEmailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //получаем пользователя что бы присвоить ему токен, по которому будет доступен роут
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneByEmail($form->get('Email')->getData());
            if (!$user) {
                throw $this->createNotFoundException(
                    'No user found by email'.$form->get('Email')->getData()
                );
            }
            //генерим токен(сделать в отдельный класс)
            $token = str_replace("/", "", password_hash(  rand(0, 10000) , PASSWORD_DEFAULT));
            $user->setToken($token);
            //присваиваем токен
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            //генерим и отправляем сообщения на почту
            $URL = 'http://quiz.home/recovery/' . $user->getToken();
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('Quiz@lol.com')
                ->setTo( $form->get('Email')->getData())
                ->setBody($URL);
            $mailer->send($message);
            //страница успешного отправления сообщения
            return $this->render('recovery/emailsendmessage.html.twig');
        }
        //страница с формой email
        return $this->render(
            'recovery/email.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/recovery/{slug}", name="recovery_password_end")
     */
    public function recoveryPasswordForm( Request $request, UserPasswordEncoderInterface $passwordEncoder,String $slug)
    {
        //получаем пользователя по токену из ссылки
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $slug]);

        //форма для ввода нового пороля
        $form = $this->createForm(RecoveryPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //инкодим пороль
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setToken('');
            //сохраняем пороль и обнуляем токен
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //страница успешной смены пороля
            return $this->render('recovery/success.html.twig');
        }

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found by token '.$slug
            );
        }

        //страница смены пороля
        return $this->render(
            'recovery/recoverypassword.html.twig',
            array('form' => $form->createView()));
    }


}