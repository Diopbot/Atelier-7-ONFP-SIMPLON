<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Categorie1Type;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/terra')]
class TerraController extends AbstractController
{
    #[Route('/', name: 'terra_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('terra/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'terra_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(Categorie1Type::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('terra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terra/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'terra_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('terra/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'terra_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(Categorie1Type::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('terra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terra/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'terra_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('terra_index', [], Response::HTTP_SEE_OTHER);
    }
}
