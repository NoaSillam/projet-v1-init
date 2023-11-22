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





























  /*  #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();

        $personne = new Personne(); // ou récupérez l'entité Personne à partir de la base de données
        $entityManager->persist($personne);

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
    }*/

 /*   #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();

        $form = $this->createForm(InfosDevisType::class, $infosDevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les entités associées à InfosDevis
            foreach ($infosDevi->getTranche() as $tranche) {
                $entityManager->persist($tranche);
            }

            // Vérifier si $nbPersonne est défini avant de le persister
            $nbPersonne = $infosDevi->getNbPersonne();
            if ($nbPersonne !== null) {
                $entityManager->persist($nbPersonne);
            }

            // Puis persister l'entité InfosDevis
            $entityManager->persist($infosDevi);
            $entityManager->flush();

            return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('infos_devis/new.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
        ]);
    }*/

   /* #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();

        // Récupérer le nombre de personnes depuis la requête
        $nbPersonneId = $request->request->get('infos_devis')['nbPersonne'] ?? null;

        // Vérifier si le nombre de personnes est sélectionné
        if ($nbPersonneId) {
            // Récupérer les tranches fiscales en fonction du nombre de personnes
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonne($nbPersonneId);
        } else {
            // Si le nombre de personnes n'est pas sélectionné, récupérer toutes les tranches fiscales
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findAll();
        }

        // Créer le formulaire
        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // ... (votre logique pour traiter le formulaire)

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
        return $this->render('infos_devis/new.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
        ]);
    }*/



  /*  #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
        $nbPersonneId = $request->request->get('infos_devis')['nbPersonne'] ?? null;

        if ($nbPersonneId) {
            // Récupérer les tranches fiscales en fonction du nombre de personnes
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonne($nbPersonneId);

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
        $nbPersonneId = $request->query->get('nbPersonneId');

        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
        $tranchesFiscales = $trancheFiscalRepository->findByNbPersonne($nbPersonneId);

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
    }*/






/*
    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici
        //    // Récupérer le nombre de personnes depuis la requête
          $nbPersonneId = $request->request->get('infos_devis')['nbPersonne']->getId() ?? null;
          $nbRegionsId = $request->request->get('infos_devis')['Regions']->getId() ?? null;
          if ($nbPersonneId !== null && $nbRegionsId !== null)   {
              // Récupérer les tranches fiscales en fonction du nombre de personnes
                    $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $nbRegionsId);
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
        $form = $this->createForm(InfosDevisType::class,
            $infosDevi, ['tranchesFiscales' => $tranchesFiscales]);
          $form->handleRequest($request);    // Traitement du formulaire
         if ($form->isSubmitted() && $form->isValid()) {
             // ... (votre logique pour traiter le formulaire)
             //        // Vérifiez si c'est une requête AJAX
             if ($request->isXmlHttpRequest()) {
                 // Renvoyez une réponse JSON avec les données appropriées
                 return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
             }
             // S'il ne s'agit pas d'une requête AJAX, redirigez vers la vue appropriée
             return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
         }    // Si la requête n'est pas AJAX, renvoyez la vue HTML
        if ($request->isXmlHttpRequest()) {
            // Renvoyez une réponse JSON pour indiquer le succès
             return new JsonResponse(['success' => true]);
        }
        // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
        return  $this->render('infos_devis/new.html.twig', [
            'infos_devi' => $infosDevi,
            'form' => $form->createView(),
            ]);}

    #[Route('/updateTranches', name: 'app_infos_devis_tranches', methods: ['GET', 'POST'])]
    public function updateTranches(Request $request, TrancheFiscalRepository $trancheFiscalRepository){
        $nbPersonneId = $request->query->get('nbPersonneId')  ?? null;
        $nbRegionsId = $request->query->get('nbRegionsId')  ?? null;
        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
        $tranchesFiscales = $trancheFiscalRepository->findByNbPersonneByRegions($nbPersonneId, $nbRegionsId);
        $tranchesFiscalesData = [];    foreach ($tranchesFiscales as $tranche) {
           $label = $tranche->getDebut();
            //        // Ajoutez ' - fin' si la valeur de 'fin' n'est pas null
              if ($tranche->getFin() !== null) {
              $label = $tranche->getDebut().' - ' . $tranche->getFin();
              } else {
                  $label = ' > ' . $tranche->getDebut();
              }        $tranchesFiscalesData[] = [
                  'value' => $tranche->getId(),
                'label' => $label,        ];
        }    return new JsonResponse($tranchesFiscalesData);
    }*/

/*
#[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{        try {
    $infosDevi = new InfosDevis();
    $tranchesFiscalesData = []; // Déplacez la déclaration ici
    //           // Récupérer le nombre de personnes depuis la requête
            $nbPersonneId = $request->request->get('nbPersonne') ?? null;
            $nbRegionId = $request->request->get('regionName') ?? null;
            if ($nbPersonneId) {
                $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegion($nbPersonneId, $nbRegionId);
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
            }            // ... (le reste de votre code)
              $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscales]);
            $form->handleRequest($request);
            // Traitement du formulaire
          if ($form->isSubmitted() && $form->isValid()) {
              // ... (votre logique pour traiter le formulaire)
              $formData = $form->getData();
              // Persister les données
              $entityManager->persist($formData);
              $entityManager->flush();
              // Redirection ou réponse JSON en fonction de la requête
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true, 'redirect' => $this->generateUrl('app_infos_devis_index')]);
            }
            return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
          }
          //   Assurez-vous d'adapter ce code en fonction de votre logique métier spécifique et de vos entités associées. Si le problème persiste, examinez les messages d'erreur spécifiques générés par Doctrine lors de la tentative de flush pour obtenir des informations plus précises sur ce qui ne va pas.
    //            // Si la requête n'est pas AJAX, renvoyez la vue HTML
              if ($request->isXmlHttpRequest()) {
                  // Renvoyer une réponse JSON pour indiquer le succès
               return new JsonResponse(['success' => true]);
              }
              // S'il ne s'agit pas d'une requête AJAX, renvoyez la vue HTML
                return  $this->render('infos_devis/new.html.twig', [
                    'infos_devi' => $infosDevi,
                    'form' => $form->createView(),
                    ]);        } catch (\Exception $e) {
    // Log or handle the exception as needed
               error_log('Error in InfosDevisController: ' . $e->getMessage());
               if ($request->isXmlHttpRequest()) {
                   return new JsonResponse(['success' => false, 'error' => $e->getMessage()]);
               }
               // Ajoutez un message flash pour afficher l'erreur dans la vue
            $this->addFlash('error', $e->getMessage());
               return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
}}
    #[Route('/updateTranches', name: 'app_infos_devis_tranches', methods: ['GET', 'POST'])]
    public function updateTranches(Request $request, TrancheFiscalRepository $trancheFiscalRepository)
    {
        $nbPersonneId = $request->query->get('nbPersonneId');
        $nbRegionId = $request->query->get('nbRegionId');
        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
           $tranchesFiscales = $trancheFiscalRepository->findByNbPersonneByRegion($nbPersonneId, $nbRegionId);
           $tranchesFiscalesData = [];
           foreach ($tranchesFiscales as $tranche) {
               // $label = $tranche->getDebut();
               //            // Ajoutez ' - fin' si la valeur de 'fin' n'est pas null
                         if ($tranche->getFin() !== null) {
                             $label = $tranche->getDebut().' - ' . $tranche->getFin();
                         } else {
                             $label = ' > ' . $tranche->getDebut();
                         }
                         $tranchesFiscalesData[] = [
                             'value' => $tranche->getId(),
                             'label' => $label,
                             ];        }
           return new JsonResponse($tranchesFiscalesData);
    }
*/
























/*
    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
        $nbPersonneId = $request->request->get('infos_devis')['Region'] ?? null;

        if ($nbPersonneId !== null)  {
            // Récupérer les tranches fiscales en fonction du nombre de personnes
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByRegion($nbPersonneId);

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
        $nbPersonneId = $request->query->get('nbPersonneId');

        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
        $tranchesFiscales = $trancheFiscalRepository->findByRegion($nbPersonneId);

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
    }*/


/*
    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici


        // ... (le reste de votre code)
        $form = $this->createForm(InfosDevisType::class, $infosDevi, ['tranchesFiscales' => $tranchesFiscalesData]);
        $form->handleRequest($request);

        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // ... (votre logique pour traiter le formulaire)
            // Récupérer le nombre de personnes depuis la requête
            $nbPersonneId = $infosDevi->getNbPersonne()->getId();
            $nbRegionsId = $infosDevi->getRegions()->getId();


            if ($nbPersonneId !== null && $nbRegionsId !== null)  {
                // Récupérer les tranches fiscales en fonction du nombre de personnes
                $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $nbRegionsId);

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
    public function getTranchesAction(Request $request): JsonResponse
    {
        // Ajoutez ici votre logique pour récupérer les tranches fiscales
        // Utilisez le service du repository approprié

        $tranches = $this->getDoctrine()->getRepository(TrancheFiscal::class)->findTrancheFiscalChoicesByPersonne();

        $response = [];
        foreach ($tranches as $id => $label) {
            $response[] = ['value' => $id, 'label' => $label];
        }

        return $this->json($response);
    }

    #[Route('/updateTranches', name: 'app_infos_devis_tranches', methods: ['GET', 'POST'])]
    public function updateTranches(Request $request, TrancheFiscalRepository $trancheFiscalRepository)
    {
        $nbPersonneId = $request->query->get('nbPersonneId');
        $nbRegionsId = $request->query->get('nbRegionsId');

        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
        $tranchesFiscales = $trancheFiscalRepository->findByNbPersonneByRegions($nbPersonneId, $nbRegionsId);

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

    }*/













































/*
    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infosDevi = new InfosDevis();
        $tranchesFiscalesData = []; // Déplacez la déclaration ici

        // Récupérer le nombre de personnes depuis la requête
        $nbPersonneId = $request->request->get('infos_devis')['nbPersonne'] ?? null;
        $nbRegionId = $request->request->get('infos_devis')['Region'] ?? null;

        if ($nbPersonneId) {
            // Récupérer les tranches fiscales en fonction du nombre de personnes
            $tranchesFiscales = $entityManager->getRepository(TrancheFiscal::class)->findByNbPersonneByRegions($nbPersonneId, $nbRegionId);

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
        $nbPersonneId = $request->query->get('nbPersonneId');
        $nbRegionId = $request->query->get('$nbRegionId');

        // Votre logique pour récupérer les tranches fiscales en fonction du nombre de personnes
        $tranchesFiscales = $trancheFiscalRepository->findByNbPersonneByRegions($nbPersonneId, $nbRegionId);

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
    }*/




















    /*    #[Route('/new', name: 'app_infos_devis_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $infosDevi = new InfosDevis();

            $form = $this->createForm(InfosDevisType::class, $infosDevi);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persister les entités "Tranche" associées à "InfosDevis"
                foreach ($infosDevi->getTranche() as $tranche) {
                    $entityManager->persist($tranche);
                }

                // Pas besoin de persister explicitement "Personne", car elle est gérée par Doctrine

                // Persister l'entité "InfosDevis"
                $entityManager->persist($infosDevi);
                $entityManager->flush();

                return $this->redirectToRoute('app_infos_devis_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('infos_devis/new.html.twig', [
                'infos_devi' => $infosDevi,
                'form' => $form->createView(),
            ]);
        }*/






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
