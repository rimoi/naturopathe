<?php


namespace App\EventListener;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ExceptionListener
{
    private $mailer;
    private $twig;
    private $parameterBag;

    public function __construct(MailerInterface $mailer, Environment $twig, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameterBag = $parameterBag;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof HttpExceptionInterface) {
            $message = sprintf(
                'Erreur Server : <br/><br/> <b>message </b>: <code>%s</code> <br /><b>Line:</b> <code>%s</code> <br /> <b>File</b>: <code>%s</code>',
                $exception->getMessage(),
                $exception->getLine(),
                $exception->getFile()
            );
           // $this->sendEmail($message);
        }
    }

    private function sendEmail(string $message): void
    {
        $email = (new Email())
            ->from($this->parameterBag->get('app.email.from'))
            ->to($this->parameterBag->get('app.email.to'))
            ->subject('Il ya une erreur 500 qu\'elle à été détecter sur votre site ! ')
            ->html($message)
        ;

        $this->mailer->send($email);
    }
}