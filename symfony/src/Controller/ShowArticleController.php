<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;

class ShowArticleController extends BaseController
{
    /**
     * @Route("/annonce/{slug}", name="show_article")
     */
    public function index(Article $article)
    {
        return $this->render('show_article/index.html.twig', [
            'article' => $article,
        ]);
    }
}
