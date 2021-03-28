<?php

namespace App\Controller;

use App\Enum\ArticleEnum;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class PratiqueController extends AbstractController
{
    /**
     * @Route("/pratiques", name="pratiques")
     */
    public function index(Request $request, ArticleRepository $articleRepository, CacheInterface $cache)
    {
        $articles = $articleRepository->findBy(['type' => ArticleEnum::TECHNIQUE, 'archived' => false]);
//        $articles = $cache->get('pratiques', function () use ($articleRepository) {
//            return $articleRepository->findBy(['type' => ArticleEnum::TECHNIQUE, 'archived' => false]);
//        });

        return $this->render('pratique/index.html.twig', [
            'articles' => $articles,
            'id' => $request->get('id')
        ]);
    }
}
