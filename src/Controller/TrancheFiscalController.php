<?php

namespace App\Controller;

use App\Entity\TrancheFiscal;
use App\Form\TrancheFiscalType;
use App\Repository\TrancheFiscalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tranche_fiscal')]
class TrancheFiscalController extends AbstractController
{
    #[Route('/', name: 'app_tranche_fiscal_index', methods: ['GET'])]
    public function index(TrancheFiscalRepository $trancheFiscalRepository): Response
    {
        return $this->render('tranche_fiscal/index.html.twig', [
            'tranche_fiscals' => $trancheFiscalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tranche_fiscal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trancheFiscal = new TrancheFiscal();
        $form = $this->createForm(TrancheFiscalType::class, $trancheFiscal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trancheFiscal);
            $entityManager->flush();

            return $this->redirectToRoute('app_tranche_fiscal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tranche_fiscal/new.html.twig', [
            'tranche_fiscal' => $trancheFiscal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tranche_fiscal_show', methods: ['GET'])]
    public function show(TrancheFiscal $trancheFiscal): Response
    {
        return $this->render('tranche_fiscal/show.html.twig', [
            'tranche_fiscal' => $trancheFiscal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tranche_fiscal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrancheFiscal $trancheFiscal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrancheFiscalType::class, $trancheFiscal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tranche_fiscal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tranche_fiscal/edit.html.twig', [
            'tranche_fiscal' => $trancheFiscal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tranche_fiscal_delete', methods: ['POST'])]
    public function delete(Request $request, TrancheFiscal $trancheFiscal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trancheFiscal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trancheFiscal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tranche_fiscal_index', [], Response::HTTP_SEE_OTHER);
    }
}
