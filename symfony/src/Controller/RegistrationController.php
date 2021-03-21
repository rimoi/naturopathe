<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\Login;
use MercurySeries\FlashyBundle\FlashyNotifier;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class RegistrationController extends BaseController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, FlashyNotifier $notifier, Login $login): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $password =  $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($password);
            $user->setCreatedAt(new \DateTime());
            $user->setRoles(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // force connect
            $login->forceConnection($request, $user);


            $notifier->success('Bienvenue '.$user->nickname().' !', '');

            return $this->redirectToRoute('home');
        }

        return $this->render('connection/login.html.twig', [
            'registrationForm' => $form->createView(),
            'error_registration' =>$this->getErrors($form),
        ]);
    }
}
