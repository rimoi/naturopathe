<?php

namespace App\Controller;


use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class WhoAreWeController extends BaseController
{
    /**
     * @Route("qui-suis-je", name="who_are_we", options={"sitemap" = true})
     */
    public function index()
    {
        return $this->render('who_are_we/index.html.twig');
    }
}
