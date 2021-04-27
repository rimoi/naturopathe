<?php

namespace App\Controller;

use App\Enum\ArticleEnum;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /** @Route("/", name="home", options={"expose"=true, "sitemap"=true}) */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['archived' => false, 'type' => ArticleEnum::TECHNIQUE]);

        $firstArticle = null;
        if (count($articles) % 2 !== 0) {
            $firstArticle = array_shift($articles);
        }

        return $this->render('home/home.html.twig', [
            'articles' => $articles,
            'first_article' => $firstArticle
        ]);
    }
}
