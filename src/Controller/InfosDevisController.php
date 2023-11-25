<?php

namespace App\Controller;

use App\Entity\InfosDevis;
use App\Entity\Personne;
use App\Entity\TrancheFiscal;
use App\Form\InfosDevisType;
use App\Repository\InfosDevisRepository;
use App\Repository\TrancheFiscalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/infos_devis')]
class InfosDevisController extends AbstractController
{
    #[Route('/', name: 'app_infos_devis_index', methods: ['GET'])]
    public function index(InfosDevisRepository $infosDevisRepository, EntityManagerInterface $entityManager): Response
    {
      /*  $results = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonne(2);

        // Affichez les résultats pour le débogage
        dd($results);*/


        return $this->render('infos_devis/index.html.twig', [
            'infos_devis' => $infosDevisRepository->findAll(),
        ]);
    }







    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
 /*       $nbPersonneId = $request->request->get('infos_devis')['nbPersonne'] ?? null;
        // Récupérer la région depuis la requête
        $regionId = $request->request->get('infos_devis')['Region'] ?? null;*/
        $nbPersonneId = $request->request->get('nbPersonne') ?? null;
        $regionId = $request->request->get('Region') ?? null;


        if ($nbPersonneId) {
            // Récupérer les tranches fiscales en fonction du nombre de personnes
           // $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonne($nbPersonneId);
// Votre méthode new dans le contrôleur
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $regionId);

            // Transformez les données des tranches fiscales en un format approprié pour la réponse JSON
            foreach ($tranchesFiscales as $tranche) {
                $tranchesFiscalesData[] = [
                    'value' => $tranche->getId(),
                    'label' => $tranche->getDebut() . ' - ' . $tranche->getFin(),
                ];
            }
        } else {
            // Si le nombre de personnes n'est pas sélectionné, récupérer toutes les tranches fiscales
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findAll();
        }

        // ... (le reste de votre code)
        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // ... (votre logique pour traiter le formulaire)
            $entityManager->persist($infosDevi);
            $entityManager->flush();
            // Vérifiez si c'est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Renvoyez une réponse JSON avec les données appropriées
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
            }

            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
            return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
            return new JsonResponse(['success' => true]);
        }

        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
        return  $this->render('infos_devis/new.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/updateTranches', name: 'app_infos_devis_tranches', methods: ['GET', 'POST'])]
    public function updateTranches(Request $request, TrancheFiscalRepository $trancheFiscalRepository)
    {

        try {
        $nbPersonneId = $request->query->get('nbPersonneId');
        $regionId = $request->query->get('regionId');

        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
        // Votre méthode updateTranches dans le contrôleur
        $tranchesFiscales = $trancheFiscalRepository->findByNbPersonneByRegions($nbPersonneId, $regionId);

       // dd($nbPersonneId, $regionId);
        $tranchesFiscalesData = [];

        foreach ($tranchesFiscales as $tranche) {
            // $label = $tranche->getDebut();

            // Ajoutez ' - fin' si la valeur de 'fin' n'est pas null
            if ($tranche->getFin() !== null) {
                $label = $tranche->getDebut().' - ' . $tranche->getFin();
            } else {
                $label = ' > ' . $tranche->getDebut();
            }

            $tranchesFiscalesData[] = [
                'value' => $tranche->getId(),
                'label' => $label,
            ];
        }

        return new JsonResponse($tranchesFiscalesData);
        } catch (\Exception $e) {
            // Log the exception
            $this->get('logger')->error('Error fetching tranches: ' . $e->getMessage());

            // Renvoyer une réponse d'erreur au format JSON par exemple
            return $this->json(['error' => 'Internal Server Error'], 500);
        }

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


        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
        /*       $nbPersonneId = $request->request->get('infos_devis')['nbPersonne'] ?? null;
               // Récupérer la région depuis la requête
               $regionId = $request->request->get('infos_devis')['Region'] ?? null;*/
        $nbPersonneId = $request->request->get('nbPersonne') ?? null;
        $regionId = $request->request->get('Region') ?? null;


        if ($nbPersonneId) {
            // Récupérer les tranches fiscales en fonction du nombre de personnes
            // $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonne($nbPersonneId);
// Votre méthode new dans le contrôleur
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $regionId);

            // Transformez les données des tranches fiscales en un format approprié pour la réponse JSON
            foreach ($tranchesFiscales as $tranche) {
                $tranchesFiscalesData[] = [
                    'value' => $tranche->getId(),
                    'label' => $tranche->getDebut() . ' - ' . $tranche->getFin(),
                ];
            }
        } else {
            // Si le nombre de personnes n'est pas sélectionné, récupérer toutes les tranches fiscales
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findAll();
        }

        // ... (le reste de votre code)
        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // ... (votre logique pour traiter le formulaire)
            $entityManager->persist($infosDevi);
            $entityManager->flush();
            // Vérifiez si c'est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Renvoyez une réponse JSON avec les données appropriées
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
            }

            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
            return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
            return new JsonResponse(['success' => true]);
        }

        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
        return  $this->render('infos_devis/edit.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
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
