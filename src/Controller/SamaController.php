<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sama')]
class SamaController extends AbstractController
{
    #[Route('/', name: 'sama_index', methods: ['GET'])]
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('sama/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'sama_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('sama_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sama/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'sama_show', methods: ['GET'])]
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('sama/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    #[Route('/{id}/edit', name: 'sama_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evaluation $evaluation): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sama_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sama/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'sama_delete', methods: ['POST'])]
    public function delete(Request $request, Evaluation $evaluation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evaluation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sama_index', [], Response::HTTP_SEE_OTHER);
    }
}
