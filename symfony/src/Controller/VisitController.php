<?php

namespace App\Controller;

use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/visit")
 */
class VisitController extends AbstractController
{
    /**
     * @Route("/", name="visit_index", methods={"GET"})
     */
    public function index(VisitRepository $visitRepository): Response
    {
        return $this->render('visit/index.html.twig', [
            'visits' => $visitRepository->getGroupByUser(),
        ]);
    }

    /**
     * @Route("/new", name="visit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $visit = new Visit();
        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            return $this->redirectToRoute('visit_index');
        }

        return $this->render('visit/new.html.twig', [
            'visit' => $visit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visit_show", methods={"GET"})
     */
    public function show(Visit $visit): Response
    {
        return $this->render('visit/show.html.twig', [
            'visit' => $visit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="visit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Visit $visit): Response
    {
        $form = $this->createForm(VisitType::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visit_index');
        }

        return $this->render('visit/edit.html.twig', [
            'visit' => $visit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Visit $visit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($visit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('visit_index');
    }
}
