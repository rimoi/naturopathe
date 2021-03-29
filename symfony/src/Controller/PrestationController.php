<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrestationController extends AbstractController
{
    /** @Route("/prestation", name="prestation") */
    public function index()
    {
        return $this->render('prestation/index.html.twig');
    }

    /** @Route("/tarif", name="tarif") */
    public function tarif()
    {
        return $this->render('prestation/tarif.html.twig');
    }
}
