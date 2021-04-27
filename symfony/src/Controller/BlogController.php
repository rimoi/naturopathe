<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Enum\ArticleEnum;
use App\Repository\ArticleRepository;
use App\Controller\BaseController;
use App\Services\VisitManager;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/blog", name="blog_")
 */
class BlogController extends BaseController
{
    /**
     * @Route("/", name="index", options={"sitemap" = {"priority" = 0.7, "changefreq" = "weekly"}})
     */
    public function index(ArticleRepository $articleRepository)
    {
        $articles =  $articleRepository->findBy(['archived' => false, 'type' => ArticleEnum::BLOG], ['id' => 'desc']);

        return $this->render('blog/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/show/{slug}", name="show")
     */
    public function show(Article $article, VisitManager $visitManager)
    {
        if ($article->getArchived()) {
            $this->flashError('Article non trouvé !');
            return $this->redirectToRoute('blog_index');
        }
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);

        $visitManager->addVisit($article, $this->getUser());

        return $this->render('blog/show.html.twig', compact('article', 'user'));
    }
}
