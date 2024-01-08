<?php

namespace App\Controller;

use App\Entity\DevisRenoGlobal;
use App\Entity\TrancheFiscal;
use App\Form\DevisRenoGlobalType;
use App\Repository\DevisRenoGlobalRepository;
use App\Repository\TrancheFiscalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis/reno/global')]
class DevisRenoGlobalController extends AbstractController
{
    #[Route('/', name: 'app_devis_reno_global_index', methods: ['GET'])]
    public function index(DevisRenoGlobalRepository $devisRenoGlobalRepository): Response
    {
        return $this->render('devis_reno_global/index.html.twig', [
            'devis_reno_globals' => $devisRenoGlobalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_devis_reno_global_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devisRenoGlobal = new DevisRenoGlobal();
        $tranchesFiscalesData = [];
        $nbPersonneId = $request->request->get('nbPersonne') ?? null;
        $regionId = $request->request->get('Region') ?? null;

        if ($nbPersonneId) {
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


        $form = $this->createForm(DevisRenoGlobalType::class, $devisRenoGlobal, ['tranchesFiscales' => $tranchesFiscales , 'is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($devisRenoGlobal->getProprieter() === "Proprietaire" && $devisRenoGlobal->getResidencePrincipale() === "Oui" && $devisRenoGlobal->getTypeChauffage() != 'electricite' ) {
                $devisRenoGlobal->setValidations(true);
            } else {
                $devisRenoGlobal->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }
            if($devisRenoGlobal->getTypeChauffage() != 'electricite')
            {
                $devisRenoGlobal->setValidationCEE(true);
            }
            else{
                $devisRenoGlobal->setValidationCEE(false);
            }
            $entityManager->persist($devisRenoGlobal);
            $entityManager->flush();
            // Vérifiez si c'est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Renvoyez une réponse JSON avec les données appropriées
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_devis_reno_global_index')]);
            }
            return $this->redirectToRoute('app_devis_reno_global_index', [], Response::HTTP_SEE_OTHER);
        }
        // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
            return new JsonResponse(['success' => true]);
        }


        return $this->render('devis_reno_global/new.html.twig', [
            'devis_reno_global' => $devisRenoGlobal,
            'form' => $form,
        ]);
    }

    private function getTrancheFiscalesAsStringReno(?TrancheFiscal $trancheFiscal): string
    {
        if (!$trancheFiscal) {
            return 'N/A';
        }

        $tranchesAsString = [];

        foreach ($trancheFiscal->getTranches() as $tranche) {
            if ($tranche->getFin() !== null) {
                $tranchesAsString[] = $tranche->getDebut() . '€ - ' . $tranche->getFin() . '€';
            } else {
                $tranchesAsString[] = '> ' . $tranche->getDebut() . '€';
            }
        }

        return implode("\r\n", $tranchesAsString);
    }


    #[Route('/updateTranchesReno', name: 'app_infos_devis_tranches_reno', methods: ['GET', 'POST'])]
    public function updateTranchesReno(Request $request, TrancheFiscalRepository $trancheFiscalRepository)
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



    #[Route('/{id}', name: 'app_devis_reno_global_show', methods: ['GET'])]
    public function show(DevisRenoGlobal $devisRenoGlobal): Response
    {
        return $this->render('devis_reno_global/show.html.twig', [
            'devis_reno_global' => $devisRenoGlobal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_reno_global_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DevisRenoGlobal $devisRenoGlobal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DevisRenoGlobalType::class, $devisRenoGlobal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_reno_global_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis_reno_global/edit.html.twig', [
            'devis_reno_global' => $devisRenoGlobal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_reno_global_delete', methods: ['POST'])]
    public function delete(Request $request, DevisRenoGlobal $devisRenoGlobal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devisRenoGlobal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devisRenoGlobal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_reno_global_index', [], Response::HTTP_SEE_OTHER);
    }
}
