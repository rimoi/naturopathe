<?php

namespace App\Controller;

use App\Entity\LivreOr;
use App\Form\LivreOrType;
use App\Repository\LivreOrRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/livre/or")
 */
class LivreOrController extends BaseController
{
    /**
     * @Route("/", name="livre_or_index", methods={"GET", "POST"}, options={"expose": true})
     */
    public function index(Request $request, LivreOrRepository $livreOrRepository): Response
    {

        $livreOr = new LivreOr();
        $form = $this->createForm(LivreOrType::class, $livreOr);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            $data = $request->request->all();
            $erreur = 1;
            if ($data && ($data[$form->getName()] ?? false)) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($livreOr);
                $entityManager->flush();

                $erreur = 0;
            }

            return new JsonResponse(['erreur' => $erreur]);
        }

        return $this->render('livre_or/index.html.twig', [
            'livre_ors' => $livreOrRepository->findBy(['enabled' => true, 'archived' => false], ['id' => 'DESC']),
            'form' => $form->createView(),
            'livre_or' => $livreOr
        ]);
    }

    /**
     * @Route("/admin/list", name="livre_or_admin", methods={"GET"})
     */
    public function listAdmin(LivreOrRepository $livreOrRepository): Response
    {
        return $this->render('livre_or/admin.html.twig', [
            'livre_ors' => $livreOrRepository->findBy(['archived' => false], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/admin/updateOrDelete", name="livre_or_update", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function update(Request $request, LivreOrRepository $livreOrRepository): JsonResponse
    {
        $error = 1;
        if ($id = $request->get('id')) {
            $comment = $livreOrRepository->find($id);
            $type = $request->get('type');
            if ($type === 'deleted') {
                $comment->setArchived(true);
            } else {
                $comment->setEnabled(true);
            }

            $this->getDoctrine()->getManager()->flush();
            $error = 0;
        }

        return $this->json(compact('error'));
    }

    /**
     * @Route("/admin/new", name="livre_or_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $livreOr = new LivreOr();
        $form = $this->createForm(LivreOrType::class, $livreOr, ['published' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livreOr);
            $entityManager->flush();
            $this->flashSuccess('Commentaire crée');

            return $this->redirectToRoute('livre_or_admin');
        }

        return $this->render('livre_or/new.html.twig', [
            'livre_or' => $livreOr,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="livre_or_edit", methods={"GET","POST"})
     */
    public function edit(LivreOr $livreOr, Request $request): Response
    {
        $form = $this->createForm(LivreOrType::class, $livreOr, ['published' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livreOr);
            $entityManager->flush();
            $this->flashSuccess('Commentaire modifiées ');

            return $this->redirectToRoute('livre_or_admin');
        }

        return $this->render('livre_or/new.html.twig', [
            'livre_or' => $livreOr,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="livre_or_show", methods={"GET"})
     */
    public function show(LivreOr $livreOr): Response
    {
        return $this->render('livre_or/show.html.twig', [
            'livre_or' => $livreOr,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="livre_or_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LivreOr $livreOr): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreOr->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livreOr);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livre_or_index');
    }
}
