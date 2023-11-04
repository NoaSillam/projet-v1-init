<?php

namespace App\Controller;

use App\Entity\TypeDevis;
use App\Form\TypeDevisType;
use App\Repository\TypeDevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type_devis')]
class TypeDevisController extends AbstractController
{
    #[Route('/', name: 'app_type_devis_index', methods: ['GET'])]
    public function index(TypeDevisRepository $typeDevisRepository): Response
    {
        return $this->render('type_devis/index.html.twig', [
            'type_devis' => $typeDevisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeDevi = new TypeDevis();
        $form = $this->createForm(TypeDevisType::class, $typeDevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeDevi);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_devis/new.html.twig', [
            'type_devi' => $typeDevi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_devis_show', methods: ['GET'])]
    public function show(TypeDevis $typeDevi): Response
    {
        return $this->render('type_devis/show.html.twig', [
            'type_devi' => $typeDevi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeDevis $typeDevi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeDevisType::class, $typeDevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_devis/edit.html.twig', [
            'type_devi' => $typeDevi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_devis_delete', methods: ['POST'])]
    public function delete(Request $request, TypeDevis $typeDevi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeDevi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeDevi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
