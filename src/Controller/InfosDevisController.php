<?php

namespace App\Controller;

use App\Entity\ArticleNewsletter;
use App\Entity\InfosDevis;
use App\Entity\Personne;
use App\Entity\TrancheFiscal;
use App\Entity\UserArticle;
use App\Form\InfosDevisRenoType;
use App\Form\InfosDevisType;
use App\Repository\InfosDevisRepository;
use App\Repository\TrancheFiscalRepository;
use App\Repository\UserNewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

#[Route('/infos_devis')]
class InfosDevisController extends AbstractController
{
    #[Route('', name: 'app_infos_devis_index', methods: ['GET'])]
    public function index(InfosDevisRepository $infosDevisRepository): Response
    {
        $infosDevis = $infosDevisRepository->findAllExceptRenoGlobal();

        return $this->render('infos_devis/index.html.twig', [
            'infos_devis' => $infosDevis,
        ]);
    }

    #[Route('/reno', name: 'app_infos_devis_index_reno', methods: ['GET'])]
    public function reno(InfosDevisRepository $infosDevisRepository): Response
    {
        $infosDevis = $infosDevisRepository->findExceptRenoGlobal();

        return $this->render('infos_devis/indexReno.html.twig', [
            'infos_devis' => $infosDevis,
        ]);
    }

    #[Route('/generate-excel', name: 'app_generate_excel', methods: ['GET'])]
    public function generateExcelAction(EntityManagerInterface $entityManager)
    {
        $infos_devis = $entityManager->getRepository(InfosDevis::class)->findAllExceptRenoGlobal();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajoutez les en-têtes de colonne
        $columnHeaders = ['Nom', 'Prénom', 'Mail', 'Tél.', 'Personnes dans le Foyer', 'Numéro Fiscal', 'Tranche Fiscale', 'Région', 'Propriété', 'Surface (m²)', 'Chauffage', 'Résidence', 'Aide', 'Date de Construction', 'Installation', 'Prime', 'Ménages'/* ... autres en-têtes ... */];
        foreach ($columnHeaders as $colIdx => $header) {
            $colRef = chr(65 + $colIdx); // Convertit l'index en lettre (A, B, C, ...)
            $sheet->setCellValue($colRef . '1', $header);
            // Définir la largeur de la colonne à 30 pour la colonne C, sinon 20
            $width = ($colRef === 'C' || $colRef === 'O'|| $colRef === 'N'|| $colRef === 'Q') ? 35 : 15;
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
            $sheet->setCellValue('N' . $rowIdx, $infos_devi->getDateConstruct());
            if($infos_devi->getInstallations() == 'pacAirEau')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Eau');
            }else if($infos_devi->getInstallations() == 'pacAirAir')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Air');
            }else if($infos_devi->getInstallations() == 'BTD')
            {
                $sheet->setCellValue('O' . $rowIdx, 'BTD');
            }else if($infos_devi->getInstallations() == 'ITE')
            {
                $sheet->setCellValue('O' . $rowIdx, 'ITE');
            }else if($infos_devi->getInstallations() == 'pacAirEauIte')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Eau + ITE');
            }else if($infos_devi->getInstallations() == 'pacAirAirIte')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Air + ITE');
            }else if($infos_devi->getInstallations() == 'pacAirAirBTD')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Air + BTD');
            }else if($infos_devi->getInstallations() == 'pacAirEauBTD')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Eau + BTD');
            }else if($infos_devi->getInstallations() == 'pacAirAirBTDITE')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Air + BTD + ITE');
            } else if($infos_devi->getInstallations() == 'pacAirEauBTDITE')
            {
                $sheet->setCellValue('O' . $rowIdx, 'PAC Air/Eau + BTD + ITE');
            }else if($infos_devi->getInstallations() == 'ITEBTD')
            {
                $sheet->setCellValue('O' . $rowIdx, 'ITE + BTD');
            }

            $dateActuelle = new \DateTime();

            // Récupérer la date de construction depuis $infos_devi
            $dateConstruction = $infos_devi->getDateConstruct();

            // Calculer la différence en années
            $differenceAnnees = $dateActuelle->diff($dateConstruction)->y;
            $lastTotalAideFinale = 0;

            if ($infos_devi->isValidations() and $differenceAnnees >= 15) {
                foreach ($infos_devi->getTrancheFiscal()->getTranches() as $tranche) {
                    if ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'BTD') {
                        $lastTotalAideFinale = 1307;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'BTD') {
                        $lastTotalAideFinale = 894;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'BTD') {
                        $lastTotalAideFinale = 494;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'BTD') {
                        $lastTotalAideFinale = 85;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirAirBTD') {
                        $lastTotalAideFinale = 1307;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirAirBTD') {
                        $lastTotalAideFinale = 894;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirAirBTD') {
                        $lastTotalAideFinale = 494;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirAirBTD') {
                        $lastTotalAideFinale = 85;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirEauBTD') {
                        $lastTotalAideFinale = 11107;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirEauBTD') {
                        $lastTotalAideFinale = 8994;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirEauBTD') {
                        $lastTotalAideFinale = 6094;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirEauBTD') {
                        $lastTotalAideFinale = 2685;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirEau') {
                        $lastTotalAideFinale = 9800;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirEau') {
                        $lastTotalAideFinale = 8100;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirEau') {
                        $lastTotalAideFinale = 5600;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirEau') {
                        $lastTotalAideFinale = 2600;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'ITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =75*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =75*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'ITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =60*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =60*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'ITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =40*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =40*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'ITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =0*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =0*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirAir') {
                        $lastTotalAideFinale = 0;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirAir') {
                        $lastTotalAideFinale = 0;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirAir') {
                        $lastTotalAideFinale = 0;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirAir') {
                        $lastTotalAideFinale = 0;
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirAirBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =1307+75*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =1307+75*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirAirBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =894+60*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =894+60*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirAirBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =494+40*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =494+40*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirAirBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =85+0*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =85+0*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirEauIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =9800+75*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =9800+75*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirEauIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =8100+60*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =8100+60*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirEauIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =5600+40*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =5600+40*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirEauIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =2600+0*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =2600+0*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirAirIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =75*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =75*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirAirIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =60*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =60*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirAirIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =40*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =40*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirAirIte') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =0*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =0*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'pacAirEauBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =9800+1307+75*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =9800+1307+75*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'pacAirEauBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =8100+894+60*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =8100+894+60*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'pacAirEauBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =5600+494+40*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =5600+494+40*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'pacAirEauBTDITE') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =2600+85+0*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =2600+85+0*100;
                        }
                    }
                    elseif ($tranche->getMenage()->getNom() == 'Ménages très modestes' && $infos_devi->getInstallations() == 'ITEBTD') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =1307+75*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =1307+75*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages modestes' && $infos_devi->getInstallations() == 'ITEBTD') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =894+60*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =894+60*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus intermédiaires' && $infos_devi->getInstallations() == 'ITEBTD') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =494+40*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =494+40*100;
                        }
                    } elseif ($tranche->getMenage()->getNom() == 'Ménages aux revenus les plus élevés' && $infos_devi->getInstallations() == 'ITEBTD') {
                        if($infos_devi->getSurfaceHabitable()<=100)
                        {
                            $lastTotalAideFinale =85+0*$infos_devi->getSurfaceHabitable();
                        }else{
                            $lastTotalAideFinale =85+0*100;
                        }
                    }

                }
                if ($infos_devi->getInstallations() == 'pacAirAir') {
                    $lastTotalAideFinale = 0;
                }

                $sheet->setCellValue('P' . $rowIdx, $lastTotalAideFinale . '€');
                $sheet->getStyle('P' . $rowIdx)->applyFromArray([
                    'font' => ['color' => ['rgb' => '008000']], // Vert
                ]);
            } else {
                $sheet->setCellValue('P' . $rowIdx, '0€');
                $sheet->getStyle('P' . $rowIdx)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'FF0000']], // Rouge
                ]);
            }
            // Ajoutez d'autres colonnes en fonction de votre modèle
            // ...
            if ($infos_devi->getTrancheFiscal()->getTranches()->count() > 0) {
                $menage = $infos_devi->getTrancheFiscal()->getTranches()[0]->getMenage();
                $sheet->setCellValue('Q' . $rowIdx, $menage->getNom());
            }

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


    #[Route('/generate-excel-reno', name: 'app_generate_excel_reno', methods: ['GET'])]
    public function generateExcelActionReno(EntityManagerInterface $entityManager)
    {
        $infos_devis = $entityManager->getRepository(InfosDevis::class)->findExceptRenoGlobal();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajoutez les en-têtes de colonne
        $columnHeaders = ['Nom', 'Prénom', 'Mail', 'Tél.', 'Personnes dans le Foyer', 'Numéro Fiscal', 'Tranche Fiscale', 'Région', 'Propriété', 'Surface (m²)', 'Chauffage', 'Résidence', 'Installations', 'Date depis qu\'il est proprietaire', 'Prime', 'Ménages'/* ... autres en-têtes ... */];
        foreach ($columnHeaders as $colIdx => $header) {
            $colRef = chr(65 + $colIdx); // Convertit l'index en lettre (A, B, C, ...)
            $sheet->setCellValue($colRef . '1', $header);
            // Définir la largeur de la colonne à 30 pour la colonne C, sinon 20
            $width = ($colRef === 'C' || $colRef === 'P' || $colRef === 'K'|| $colRef === 'N') ? 45 : 20;
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
            $sheet->setCellValue('D' . $rowIdx, '0' . $infos_devi->getTelephone());
            $nbPersonne = $infos_devi->getNbPersonne() ? $infos_devi->getNbPersonne()->getNbPersonne() : 'N/A';
            $sheet->setCellValue('E' . $rowIdx, $nbPersonne);
            $sheet->setCellValue('F' . $rowIdx, $infos_devi->getNumFiscal());
            $sheet->setCellValue('G' . $rowIdx, $infos_devi->getTrancheFiscal());
            $sheet->setCellValue('H' . $rowIdx, $infos_devi->getRegions() ? $infos_devi->getRegions()->getNom() : 'N/A');
            $sheet->setCellValue('I' . $rowIdx, $infos_devi->getProprieter());
            $sheet->setCellValue('J' . $rowIdx, $infos_devi->getSurfaceHabitable());
          //  $sheet->setCellValue('K' . $rowIdx, $infos_devi->getTypeChauffage());

            $install = "";
            if ($infos_devi->getTypeChauffage() == 'electricite') {
                $install = "Électricité";
            } elseif ($infos_devi->getTypeChauffage() == 'fioul') {
                $install = "Chaudière fioul";
            } elseif ($infos_devi->getTypeChauffage() == 'gaz') {
                $install = "Chaudière gaz";
            } elseif ($infos_devi->getTypeChauffage() == 'gazCond') {
                $install = "Chaudière gaz à condensation";
            } elseif ($infos_devi->getTypeChauffage() == 'electriciteChemineBois' || $infos_devi->getTypeChauffage() == 'bois')  {

                $install = "Électrique avec Cheminée (insert à bois, poêle à bois)";

            } elseif ($infos_devi->getTypeChauffage() == 'chaudiereGranulés') {

                $install = "Chaudière à granulés";
            } elseif ($infos_devi->getTypeChauffage() == 'PAC') {

                $install = "Pompe à chaleur";
            }

            // Affectez la valeur de $install à la cellule de la colonne K
            $sheet->setCellValue('K' . $rowIdx, $install);


            $sheet->setCellValue('L' . $rowIdx, $infos_devi->getResidencePrincipale());
            $sheet->setCellValue('M' . $rowIdx, $infos_devi->isValidations());

            $sheet->setCellValue('N'.$rowIdx, $infos_devi->getDatePropriete());

            // Ajoutez ici votre logique pour déterminer l'éligibilité en fonction de la datePropriete, surfaceHabitable, etc.
            $twoYearsAgo = new \DateTime('now');
            $twoYearsAgo->modify('-2 years');
            $dateDifference = $infos_devi->getDatePropriete()->diff($twoYearsAgo);

            if (
                $infos_devi->getSurfaceHabitable() > 130 &&
                $infos_devi->getProprieter() == 'Proprietaire' &&
                $dateDifference->days >= 365 * 2
            ) {
                // Ajoutez votre logique pour la colonne M (Prime) ici
                if (
                    $infos_devi->getTypeChauffage() == 'electricite' &&
                    $infos_devi->getSurfaceHabitable() > 200
                ) {
                    $sheet->setCellValue('O' . $rowIdx, 'Eligible');
                    $sheet->getStyle('O' . $rowIdx)->applyFromArray([
                        'font' => ['color' => ['rgb' => '008000']], // Vert
                    ]);
                } elseif (
                    in_array(
                        $infos_devi->getTypeChauffage(),
                        ['gaz', 'fioul', 'gazCond', 'bois', 'electriciteChemineBois']
                    )
                ) {
                    $sheet->setCellValue('O' . $rowIdx, 'Eligible');
                    $sheet->getStyle('O' . $rowIdx)->applyFromArray([
                        'font' => ['color' => ['rgb' => '008000']], // Vert
                    ]);
                } else {
                    $sheet->setCellValue('O' . $rowIdx, 'Non Eligible');
                    $sheet->getStyle('O' . $rowIdx)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'FF0000']], // Rouge
                    ]);
                }
            } else {
                $sheet->setCellValue('O' . $rowIdx, 'Non Eligible');
                $sheet->getStyle('O' . $rowIdx)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'FF0000']], // Rouge
                ]);
            }


            //  $lastTotalAideFinale = 0;

                // Ajoutez d'autres colonnes en fonction de votre modèle
                // ...

        foreach ($infos_devi->getTrancheFiscal()->getTranches() as $tranche) {
                if ($infos_devi->getTrancheFiscal()->getTranches()->count() > 0) {
                    $menage = $infos_devi->getTrancheFiscal()->getTranches()[0]->getMenage();
                    $sheet->setCellValue('P' . $rowIdx, $menage->getNom());
                }
                }


             //   $sheet->setCellValue('P', $rowIdx, $infos_devi->getDatePropriete());
             //   $sheet->setCellValue('Q', $rowIdx, )

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
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui" && $infosDevi->getTypeChauffage() != 'electricite' ) {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }
            if($infosDevi->getTypeChauffage() != 'electricite')
            {
                $infosDevi->setValidationCEE(true);
            }
            else{
                $infosDevi->setValidationCEE(false);
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

//    #[Route('/send_infos_devis/{id}', name: 'send_infos_devis')]
//    public function sendInfosDevis(UserNewsletterRepository $userNewsletterRepository, ArticleNewsletter $articleNewsletter, MailerInterface $mailer, EntityManagerInterface $entityManager ):Response
//    {
//        $users = $userNewsletterRepository->findAll();
//        try {
//            foreach($users as $user)
//            {
//                $email = (new TemplatedEmail())
//                    -> from('noasillam@gmail.com')
//                    -> to($user->getAdresseMail())
//                    ->subject($articleNewsletter->getNom())
//                    ->htmlTemplate('emails/newsletter.html.twig')
//                    ->context(compact('articleNewsletter', 'user'))
//                ;
//                $mailer->send($email);
//
//                $userArticle = new UserArticle();
//                $userArticle->setUserNewsletter($user);
//                $userArticle->setArticleNewsletter($articleNewsletter);
//
//                // Persist the UserArticle entity
//                $entityManager->persist($userArticle);
//
//
//            }
//            $entityManager->flush();
//            $this->addFlash('success', 'La newsletter a bien était envoyée !!!!');
//        } catch (\Exception $e) {
//            $this->addFlash('error', 'Erreur lors de l\'envoi de la newsletter : ' . $e->getMessage());
//        }
//        return $this->redirectToRoute('app_article_newsletter_index');
//
//    }


    #[Route('/new-reno', name: 'app_infos_devis_new_reno', methods: ['GET', 'POST'])]
    public function newReno(Request $request, EntityManagerInterface $entityManager): Response
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

        $isReno = $request->attributes->get('_route') === 'app_infos_devis_new_reno';

        $form = $this->createForm(InfosDevisType::class, $infosDevi, [
            'tranchesFiscales' => $tranchesFiscales,
            'is_edit' => false,
            'is_reno' => $isReno, // Assurez-vous que cette ligne est présente
        ]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Ajoutez la logique pour mettre à jour le champ "validations"
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui" && $infosDevi->getTypeChauffage() != 'electricite' ) {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }
            if($infosDevi->getTypeChauffage() != 'electricite')
            {
                $infosDevi->setValidationCEE(true);
            }
            else{
                $infosDevi->setValidationCEE(false);
            }


            $entityManager->persist($infosDevi);
            $entityManager->flush();
            // Vérifiez si c'est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Renvoyez une réponse JSON avec les données appropriées
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
            }

            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
            return $this->redirectToRoute('app_infos_devis_index_reno', [], Response::HTTP_SEE_OTHER);
        }

        // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
            return new JsonResponse(['success' => true]);
        }

        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
        return  $this->render('infos_devis/newReno.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
            'is_reno' => $isReno,
        ]);
    }


    #[Route('/new-reno-client', name: 'app_infos_devis_new_reno_client', methods: ['GET', 'POST'])]
    public function newRenoClient(Request $request, EntityManagerInterface $entityManager): Response
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

        $isReno = $request->attributes->get('_route') === 'app_infos_devis_new_reno_client';

        $form = $this->createForm(InfosDevisType::class, $infosDevi, [
            'tranchesFiscales' => $tranchesFiscales,
            'is_edit' => false,
            'is_reno' => $isReno, // Assurez-vous que cette ligne est présente
        ]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Ajoutez la logique pour mettre à jour le champ "validations"
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui" && $infosDevi->getTypeChauffage() != 'electricite' ) {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }
            if($infosDevi->getTypeChauffage() != 'electricite')
            {
                $infosDevi->setValidationCEE(true);
            }
            else{
                $infosDevi->setValidationCEE(false);
            }


            $entityManager->persist($infosDevi);
            $entityManager->flush();
            // Vérifiez si c'est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Renvoyez une réponse JSON avec les données appropriées
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('main_accueil_user')]);
            }

            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
            $this->addFlash('success', 'Vous serez contacter dans peu de temps par un de nos conseiller');
            return $this->redirectToRoute('main_accueil_user', [], Response::HTTP_SEE_OTHER);
            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
          //  return $this->redirectToRoute('main_accueil_user', [], Response::HTTP_SEE_OTHER);
        }

        // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
            return new JsonResponse(['success' => true]);
        }

        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
        return  $this->render('infos_devis/newReno.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
            'is_reno' => $isReno,
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
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui" && $infosDevi->getTypeChauffage() != 'electricite') {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }

            if($infosDevi->getTypeChauffage() != 'electricite')
            {
                $infosDevi->setValidationCEE(true);
            }
            else{
                $infosDevi->setValidationCEE(false);
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
            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui" && $infosDevi->getTypeChauffage() != 'electricite') {
                $infosDevi->setValidations(true);
            } else {
                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
            }
            if($infosDevi->getTypeChauffage() != 'electricite')
            {
                $infosDevi->setValidationCEE(true);
            }
            else{
                $infosDevi->setValidationCEE(false);
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


//    #[Route('/{id}/edit-reno', name: 'app_infos_devis_edit_reno', methods: ['GET', 'POST'])]
//    public function editReno(Request $request, InfosDevis $infosDevi, EntityManagerInterface $entityManager): Response
//    {
//
//
//        $tranchesFiscalesData = []; // Déplacez la déclaration ici
//
//        // Récupérer le nombre de personnes depuis la requête
//        /*       $nbPersonneId = $request->request->get('infos_devis')['nbPersonne'] ?? null;
//               // Récupérer la région depuis la requête
//               $regionId = $request->request->get('infos_devis')['Region'] ?? null;*/
//        $nbPersonneId = $request->request->get('nbPersonne') ?? null;
//        $regionId = $request->request->get('Region') ?? null;
//
//
//        if ($nbPersonneId) {
//            // Récupérer les tranches fiscales en fonction du nombre de personnes
//            // $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonne($nbPersonneId);
//            // Votre méthode new dans le contrôleur
//            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $regionId);
//
//            // Transformez les données des tranches fiscales en un format approprié pour la réponse JSON
//            foreach ($tranchesFiscales as $tranche) {
//                $tranchesFiscalesData[] = [
//                    'value' => $tranche->getId(),
//                    'label' => $tranche->getDebut() . ' - ' . $tranche->getFin(),
//                ];
//            }
//        } else {
//            // Si le nombre de personnes n'est pas sélectionné, récupérer toutes les tranches fiscales
//            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findAll();
//        }
//        $isEdit = true;
//        $isReno = $request->attributes->get('_route') === 'app_infos_devis_new_reno';
//        // ... (le reste de votre code)
//        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales,  'is_edit' => $isEdit, 'is_reno'=>$isReno]);
//        $form->handleRequest($request);
//
//        // Traitement du formulaire
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Ajoutez la logique pour mettre à jour le champ "validations"
//            if ($infosDevi->getProprieter() === "Proprietaire" && $infosDevi->getResidencePrincipale() === "Oui" && $infosDevi->getTypeChauffage() != 'electricite') {
//                $infosDevi->setValidations(true);
//            } else {
//                $infosDevi->setValidations(false); // ou la valeur par défaut que vous souhaitez
//            }
//            if($infosDevi->getTypeChauffage() != 'electricite')
//            {
//                $infosDevi->setValidationCEE(true);
//            }
//            else{
//                $infosDevi->setValidationCEE(false);
//            }
//
//
//            $entityManager->persist($infosDevi);
//            $entityManager->flush();
//            // Vérifiez si c'est une requête AJAX
//            if ($request->isXmlHttpRequest()) {
//                // Renvoyez une réponse JSON avec les données appropriées
//                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
//            }
//
//            // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
//            return $this->redirectToRoute('app_infos_devis_index_reno', [], Response::HTTP_SEE_OTHER);
//        }
//
//        // Si la requête n'est pas AJAX, renvoyez la vue HTML
//        if ($request->isXmlHttpRequest()) {
//            // Renvoyez une réponse JSON pour indiquer le succès
//            return new JsonResponse(['success' => true]);
//        }
//
//        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
//        return  $this->render('infos_devis/edit_reno.html.twig', [
//            'infos_devi' => $infosDevi,
//            'form' => $form->createView(),
//            'is_reno'=>$isReno,
//        ]);
//
//    }


    #[Route('/{id}/edit-reno', name: 'app_infos_devis_edit_reno', methods: ['GET', 'POST'])]
    public function editReno(Request $request, InfosDevis $infosDevi, EntityManagerInterface $entityManager): Response
    {
        $tranchesFiscalesData = [];

        $nbPersonneId = $request->request->get('nbPersonne') ?? null;
        $regionId = $request->request->get('Region') ?? null;

        if ($nbPersonneId) {
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $regionId);

            foreach ($tranchesFiscales as $tranche) {
                $tranchesFiscalesData[] = [
                    'value' => $tranche->getId(),
                    'label' => $tranche->getDebut() . ' - ' . $tranche->getFin(),
                ];
            }
        } else {
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findAll();
        }

        $isEditReno = true;
        $isReno = $request->attributes->get('_route') === 'app_infos_devis_new_reno';

        $form = $this->createForm(InfosDevisType::class, $infosDevi, [
            'tranchesFiscales' => $tranchesFiscales,
            'is_edit_reno' => $isEditReno,
            'is_reno' => $isReno,  // Assurez-vous de passer is_reno ici
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajoutez votre logique de mise à jour ici...

            $entityManager->persist($infosDevi);
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
            }

            return $this->redirectToRoute('app_infos_devis_index_reno', [], Response::HTTP_SEE_OTHER);
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => true]);
        }

        return $this->render('infos_devis/edit_reno.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
            'is_reno' => $isReno,
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
