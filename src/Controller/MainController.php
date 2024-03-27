<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('/main', name: 'index_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }
    #[Route('/decret_tertiaire', name: 'decret_tertiaire')]
    public function decret_tertiaire(): Response
    {
        return $this->render('main/decret_tertiaire.html.twig');
    }

    #[Route('/navbar', name: 'index_main_navbar')]
    public function navbarFooter(): Response
    {
        return $this->render('main/navbar.html.twig');
    }

    #[Route('/profil', name: 'profil')]
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig');
    }
    #[Route('/accueil', name: 'accueil')]
    public function accueil(): Response
    {
        return $this->render('main/accueil.html.twig');
    }
    #[Route('/devis', name: 'devis')]
    public function devis(): Response
    {
        return $this->render('main/devis.html.twig');
    }

    #[Route('/accueilUser', name: 'accueil_user')]
    public function accueilUser(): Response
    {
        return $this->render('main/accueilUser.html.twig');
    }
    #[Route('/cgv', name: 'accueil_cgv')]
    public function cgv(): Response
    {
        return $this->render('main/cgv.html.twig');
    }

    #[Route('/credits', name: 'accueil_credits')]
    public function credits(): Response
    {
        return $this->render('main/credits.html.twig');
    }
    #[Route('/presentation', name: 'accueil_presentation')]
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }


    #[Route('/accueilUserTest', name: 'accueil_user_test')]
    public function accueilUserTest(): Response
    {
        return $this->render('main/accueilUserTest.html.twig');
    }
    #[Route('/cgu', name: 'accueil_cgu')]
    public function cgu(): Response
    {
        return $this->render('main/cgu.html.twig');
    }
    #[Route('/MentionLegale', name: 'accueil_mention_legale')]
    public function MentionLegale(): Response
    {
        return $this->render('main/MentionLegale.html.twig');
    }


    
}