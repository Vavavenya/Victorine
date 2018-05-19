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
        //проверка на залогиненость
        if ($this->getUser()) {
            throw $this->createNotFoundException(
                'no access'
            );
        }
        $user = new User();

        //форма для регистрации
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //инкодим пороль
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // сохраняем юзера
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //генерим письмо для верификации аккауната и отправляем
            $URL = 'http://quiz.home/register/mail/' . $user->getToken();
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('Quiz@lol.com')
                ->setTo($user->getEmail())
                ->setBody($URL);
            $mailer->send($message);

            //страница успешной регистрации и отправки сообщения
           // return $this->render('registration/emailsendmessage.html.twig');
        }

        //странциа регистрации
        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Route("/register/mail/{slug}", name="mail_verification")
     */
    public function mailVerification(String $slug)
    {
        //получаем пользователя по токену из ссылки
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $slug]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$slug
            );
        }

        //обнуляем токен и даем юзеру роль
        $user->setToken('');
        $user->setRoles('ROLE_USER');
        $entityManager->persist($user);
        $entityManager->flush();

        //страница успешной верификации
        return $this->render('registration/emailversuccess.html.twig');
    }
}