<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\Evaluation1Type;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/baba')]
class BabaController extends AbstractController
{
    #[Route('/', name: 'baba_index', methods: ['GET'])]
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('baba/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'baba_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(Evaluation1Type::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('baba_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('baba/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'baba_show', methods: ['GET'])]
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('baba/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    #[Route('/{id}/edit', name: 'baba_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evaluation $evaluation): Response
    {
        $form = $this->createForm(Evaluation1Type::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('baba_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('baba/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'baba_delete', methods: ['POST'])]
    public function delete(Request $request, Evaluation $evaluation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evaluation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('baba_index', [], Response::HTTP_SEE_OTHER);
    }
}
