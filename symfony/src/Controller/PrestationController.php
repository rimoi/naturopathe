<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrestationController extends AbstractController
{
    /** @Route("/prestation", name="prestation", options={"sitemap"=true}) */
    public function index()
    {
        return $this->render('prestation/index.html.twig');
    }

    /** @Route("/tarif", name="tarif", options={"sitemap"=true}) */
    public function tarif()
    {
        return $this->render('prestation/tarif.html.twig');
    }
}
