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
    /** @Route("/", name="home", options={"expose"=true}) */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['archived' => false, 'type' => ArticleEnum::TECHNIQUE]);

        return $this->render('home/home.html.twig', [
            'articles' => $articles
        ]);
    }


//    public function searchArticle(Request $request, $articles): Response
//    {
//        return $this->render('home/search.html.twig', [
//            'articles' => $articles,
//        ]);
//    }
}
