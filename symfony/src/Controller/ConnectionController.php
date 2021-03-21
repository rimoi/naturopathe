<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;

use Symfony\Component\HttpFoundation\Request;
use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class ConnectionController extends BaseController
{
    /**
     * @Route("/connection", name="connection")
     */
    public function login(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('connection/login.html.twig',[
            'registrationForm' => $form->createView(),
        ]);
    }
}
