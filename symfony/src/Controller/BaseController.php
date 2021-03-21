<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseController extends AbstractController
{
    private $flashyNotifier;
    /** @var EntityManagerInterface */
    protected $em;
    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(FlashyNotifier $flashyNotifier, EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->flashyNotifier = $flashyNotifier;
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function flashSuccess(string $message)
    {
        $this->flashyNotifier->success($message);
    }

    public function flashError(string $message)
    {
        $this->flashyNotifier->error($message);
    }

    protected function getErrors($form)
    {
        $errors = [];

        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}
