<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /** @Route("/", name="home", options={"expose"=true}) */
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(SearchType::class, null, ['request' => $request]);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->get('type')) {
            $articles = $articleRepository->getArticleByCategory($request);

            return $this->render('home/search.html.twig', [
                'articles' => $articles,
                'form' => $form->createView()
            ]);
        }

        return $this->render('home/home.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route({"fr": "/recherche", "ar": "/search"}, name="search", methods={"GET", "POST"}, options={"expose"=true})
     */
//    public function searchArticle(Request $request, $articles): Response
//    {
//        return $this->render('home/search.html.twig', [
//            'articles' => $articles,
//        ]);
//    }
}
