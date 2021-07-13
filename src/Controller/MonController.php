<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Form\ApprenantType;
use App\Repository\ApprenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mon')]
class MonController extends AbstractController
{
    #[Route('/', name: 'mon_index', methods: ['GET'])]
    public function index(ApprenantRepository $apprenantRepository): Response
    {
        return $this->render('mon/index.html.twig', [
            'apprenants' => $apprenantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'mon_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $apprenant = new Apprenant();
        $form = $this->createForm(ApprenantType::class, $apprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($apprenant);
            $entityManager->flush();

            return $this->redirectToRoute('mon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mon/new.html.twig', [
            'apprenant' => $apprenant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mon_show', methods: ['GET'])]
    public function show(Apprenant $apprenant): Response
    {
        return $this->render('mon/show.html.twig', [
            'apprenant' => $apprenant,
        ]);
    }

    #[Route('/{id}/edit', name: 'mon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Apprenant $apprenant): Response
    {
        $form = $this->createForm(ApprenantType::class, $apprenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mon/edit.html.twig', [
            'apprenant' => $apprenant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mon_delete', methods: ['POST'])]
    public function delete(Request $request, Apprenant $apprenant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$apprenant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($apprenant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mon_index', [], Response::HTTP_SEE_OTHER);
    }
}
