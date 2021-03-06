<?php

namespace App\Controller;

use App\Entity\Article;
use App\Enum\ArticleEnum;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/article", name="article_")
 */
class ArticleController extends BaseController
{
    /**
     * @Route("/type/blog", name="index_blog", methods={"GET"})
     * @Route("/type/technique", name="index_technique", methods={"GET"})
     */
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        if( $request->get('_route') === 'article_index_technique') {
            return $this->render('article/list_technique.html.twig', [
                'articles' => $articleRepository->findBy(['type' => ArticleEnum::TECHNIQUE, 'archived' => false], ['id' => 'DESC'])
            ]);
        }

        return $this->render('article/list_blog.html.twig', [
            'articles' => $articleRepository->findBy(['type' => ArticleEnum::BLOG, 'archived' => false], ['id' => 'DESC'])
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$type = $request->get('type')) {
            throw $this->createNotFoundException('Type l\'article inconnue !');
        }

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article, ['show' => $type === ArticleEnum::TECHNIQUE]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setType($type);
            $entityManager->persist($article);
            $entityManager->flush();

            $this->flashSuccess('Article créer avec succès !');

            return $this->redirectToRoute('article_index_'.$type);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        if (!$type = $request->get('type')) {
            throw $this->createNotFoundException('Type l\'article inconnue !');
        }

        $form = $this->createForm(ArticleType::class, $article, ['show' => $type === ArticleEnum::TECHNIQUE]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setType($type);
            $this->getDoctrine()->getManager()->flush();

            $this->flashSuccess('Article modifié avec succès');

            return $this->redirectToRoute('article_index_'.$type);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if (!$type = $request->get('type')) {
            throw $this->createNotFoundException('Type de l\'article inconnue !');
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $article->setArchived(1);
            $entityManager->flush();
            $this->flashSuccess('Article supprimé avec succès !');
        }


        return $this->redirectToRoute('article_index_'.$type);
    }
}
