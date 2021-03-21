<?php

namespace App\Controller;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends BaseController
{
    /**
     * @Route("/about", name="about", options={"sitemap" = true})
     */
    public function index()
    {
        return $this->render('about/index.html.twig');
    }
}
