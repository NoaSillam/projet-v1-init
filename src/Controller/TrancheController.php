<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Regions;
use App\Entity\Tranche;
use App\Form\TrancheType;
use App\Repository\TrancheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tranche')]
class TrancheController extends AbstractController
{
    #[Route('', name: 'app_tranche_index', methods: ['GET'])]
    public function index(TrancheRepository $trancheRepository): Response
    {
        return $this->render('tranche/index.html.twig', [
            'tranches' => $trancheRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tranche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tranche = new Tranche();
        $form = $this->createForm(TrancheType::class, $tranche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tranche);
            $entityManager->flush();

            return $this->redirectToRoute('app_tranche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tranche/new.html.twig', [
            'tranche' => $tranche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tranche_show', methods: ['GET'])]
    public function show(Tranche $tranche): Response
    {
        return $this->render('tranche/show.html.twig', [
            'tranche' => $tranche,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tranche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tranche $tranche, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrancheType::class, $tranche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tranche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tranche/edit.html.twig', [
            'tranche' => $tranche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tranche_delete', methods: ['POST'])]
    public function delete(Request $request, Tranche $tranche, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tranche->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tranche);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tranche_index', [], Response::HTTP_SEE_OTHER);
    }
}
