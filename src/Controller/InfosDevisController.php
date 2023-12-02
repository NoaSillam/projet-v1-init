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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

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


    #[Route('/generate-excel', name: 'app_generate_excel', methods: ['GET'])]
    public function generateExcelAction(EntityManagerInterface $entityManager)
    {
        $infos_devis = $entityManager->getRepository(InfosDevis::class)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajoutez les en-têtes de colonne
        $columnHeaders = ['Nom', 'Prénom', 'Mail', 'Tél.', 'Personnes dans le Foyer', 'Numéro Fiscal', 'Tranche Fiscale', 'Région', 'Propriété', 'Surface (m²)', 'Chauffage', 'Résidence', 'Aide', 'Prime'/* ... autres en-têtes ... */];
        foreach ($columnHeaders as $colIdx => $header) {
            $colRef = chr(65 + $colIdx); // Convertit l'index en lettre (A, B, C, ...)
            $sheet->setCellValue($colRef . '1', $header);
            // Définir la largeur de la colonne à 30 pour la colonne C, sinon 20
            $width = ($colRef === 'C') ? 30 : 15;
            $sheet->getColumnDimension($colRef)->setWidth($width);
            // Appliquer le style au titre de la colonne
            $sheet->getStyle($colRef . '1')->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '000000'], // Fond noir
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'], // Texte blanc
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Centrer horizontalement
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Centrer verticalement
                ],
            ]);
        }

        // Ajoutez les données
        $rowIdx = 2; // Commencez à la deuxième ligne après les en-têtes
        foreach ($infos_devis as $infos_devi) {
            $sheet->setCellValue('A' . $rowIdx, $infos_devi->getNom());
            $sheet->setCellValue('B' . $rowIdx, $infos_devi->getPrenom());
            $sheet->setCellValue('C' . $rowIdx, $infos_devi->getMail());
            $sheet->setCellValue('D' . $rowIdx, '0'.$infos_devi->getTelephone());
            $nbPersonne = $infos_devi->getNbPersonne() ? $infos_devi->getNbPersonne()->getNbPersonne() : 'N/A';
            $sheet->setCellValue('E' . $rowIdx, $nbPersonne);
            $sheet->setCellValue('F' . $rowIdx, $infos_devi->getNumFiscal());
            $sheet->setCellValue('G' . $rowIdx, $infos_devi->getTrancheFiscal());
            $sheet->setCellValue('H' . $rowIdx, $infos_devi->getRegions() ? $infos_devi->getRegions()->getNom() : 'N/A');
            $sheet->setCellValue('I' . $rowIdx, $infos_devi->getProprieter());
            $sheet->setCellValue('J' . $rowIdx, $infos_devi->getSurfaceHabitable());
            $sheet->setCellValue('K' . $rowIdx, $infos_devi->getTypeChauffage());
            $sheet->setCellValue('L' . $rowIdx, $infos_devi->getResidencePrincipale());
            $sheet->setCellValue('M' . $rowIdx, $infos_devi->isValidations());
            $totalAide = 0;
            if ($infos_devi->isValidations()) {
                foreach ($infos_devi->getTrancheFiscal()->getTranches() as $tranche) {
                    $totalAide += ($tranche->getAide() !== null) ? $tranche->getAide() : 0;
                }
            }
            $sheet->setCellValue('N' . $rowIdx, $totalAide);
            // Ajoutez d'autres colonnes en fonction de votre modèle
            // ...

            $rowIdx++;
        }

// Appliquer le style de tableau à la plage de données
        $dataRange = 'A1:' . $colRef . ($rowIdx - 1);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
            ],
            // Ajoutez d'autres styles si nécessaire
        ];

        $sheet->getStyle($dataRange)->applyFromArray($styleArray);

        // Créez le fichier Excel dans un fichier temporaire
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Retournez la réponse pour télécharger le fichier
        $response = new Response(file_get_contents($tempFile));
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename=devis_client.xlsx');

        // Supprimez le fichier temporaire après l'envoi
        register_shutdown_function(function () use ($tempFile) {
            unlink($tempFile);
        });

        return $response;
    }

    private function getTrancheFiscalesAsString(?TrancheFiscal $trancheFiscal): string
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


    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
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

        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales , 'is_edit' => false]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Ajoutez la logique pour mettre à jour le champ "validations"
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui") {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }

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





    #[Route('/new_client', name: 'app_infos_devis_new_client', methods: ['GET', 'POST'])]
    public function newClient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
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

        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales , 'is_edit' => false]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Ajoutez la logique pour mettre à jour le champ "validations"
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui") {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }

            $entityManager->persist($infosDevi);
            $entityManager->flush();
            // Vérifiez si c'est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Renvoyez une réponse JSON avec les données appropriées
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
            }

            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
            return $this->redirectToRoute('accueil_user', [], Response::HTTP_SEE_OTHER);
        }

        // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
            return new JsonResponse(['success' => true]);
        }

        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
        return  $this->render('infos_devis/new_client.html.twig', [
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
        $isEdit = true;
        // ... (le reste de votre code)
        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales,  'is_edit' => $isEdit]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajoutez la logique pour mettre à jour le champ "validations"
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui") {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }

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
