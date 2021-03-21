<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends BaseController
{
    /**
     * @Route("/submit-exception", name="exception", methods={"POST"})
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $exception = $request->request->all();
        $message = sprintf(
            'Erreur Server : <br/><br/> <b>message </b>: <code>%s</code> <br /> code : <code>%s</code> <br /><b>Line:</b> <code>%s</code> <br /> <b>File</b>: <code>%s</code>',
            $exception['_status_text'],
            $exception['_status_code'],
            $exception['_line'],
            $exception['_file']
        );

        $email = (new Email())
            ->from($this->getParameter('app.email.from'))
            ->to($this->getParameter('app.email.to'))
            ->subject('Il ya une erreur 500 qu\'elle à été détecter sur votre site ! ')
            ->html($message)
        ;

        $mailer->send($email);

        $this->flashSuccess("Merci d'avoir signalé l'erreur !");

        return $this->redirectToRoute('home');
    }

}
