<?php

namespace App\Controller;

use App\Entity\InfosDevis;
use App\Form\InfosDevisType;
use App\Repository\InfosDevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/infos_devis')]
class InfosDevisController extends AbstractController
{
    #[Route('/', name: 'app_infos_devis_index', methods: ['GET'])]
    public function index(InfosDevisRepository $infosDevisRepository): Response
    {
        return $this->render('infos_devis/index.html.twig', [
            'infos_devis' => $infosDevisRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();

        $form = $this->createForm(InfosDevisType::class, $infosDevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vous devez d'abord persister les entités "Tranche" associées à "InfosDevis"
            foreach ($infosDevi->getTranche() as $tranche) {
                $entityManager->persist($tranche);
            }

            // Puis vous persistez l'entité "InfosDevis"
            $entityManager->persist($infosDevi);
            $entityManager->flush();

            return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('infos_devis/new.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}', name: 'app_infos_devis_show', methods: ['GET'])]
    public function show(InfosDevis $infosDevi): Response
    {
        return $this->render('infos_devis/show.html.twig', [
            'infos_devi' => $infosDevi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_infos_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InfosDevis $infosDevi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InfosDevisType::class, $infosDevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('infos_devis/edit.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_infos_devis_delete', methods: ['POST'])]
    public function delete(Request $request, InfosDevis $infosDevi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$infosDevi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($infosDevi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
