<?php

namespace App\Controller;

use App\Entity\Menage;
use App\Form\MenageType;
use App\Repository\MenageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menage')]
class MenageController extends AbstractController
{
    #[Route('', name: 'app_menage_index', methods: ['GET'])]
    public function index(MenageRepository $menageRepository): Response
    {
        return $this->render('menage/index.html.twig', [
            'menages' => $menageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_menage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menage = new Menage();
        $form = $this->createForm(MenageType::class, $menage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menage);
            $entityManager->flush();

            return $this->redirectToRoute('app_menage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menage/new.html.twig', [
            'menage' => $menage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_menage_show', methods: ['GET'])]
    public function show(Menage $menage): Response
    {
        return $this->render('menage/show.html.twig', [
            'menage' => $menage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_menage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menage $menage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenageType::class, $menage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_menage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menage/edit.html.twig', [
            'menage' => $menage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_menage_delete', methods: ['POST'])]
    public function delete(Request $request, Menage $menage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_menage_index', [], Response::HTTP_SEE_OTHER);
    }
}
