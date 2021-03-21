<?php

namespace App\Controller;


use App\Controller\BaseController;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends BaseController
{
    /**
     * @Route("/contact", name="contact", options={"sitemap" = true})
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(new Address($this->getParameter('app.email.from'), ''))
                ->to($this->getParameter('app.email.to'))
                ->subject('Vous avez recu un nouveau message de votre Site')
                ->htmlTemplate('contact/template.html.twig')
                ->context(compact('contact'))
            ;

            $mailer->send($email);

            $this->flashSuccess('Votre email à bien été envoyé !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'error_registration' =>$this->getErrors($form),
        ]);
    }
}
