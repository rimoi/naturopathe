<?php

namespace App\Controller;


use App\Controller\BaseController;
use App\Repository\LivreOrRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends BaseController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, LivreOrRepository $livreOrRepository, TranslatorInterface $translator)
    {
        if ($request->getLocale() !== 'fr') {
            $request->setLocale('fr');
            $translator->setLocale('fr');
             return $this->redirectToRoute('admin', ['_locale' => 'fr']);
        }

        return $this->render('admin/index.html.twig', ['comments' => $livreOrRepository->findBy(['enabled' => false], ['id' => 'DESC'])]);
    }
}
