<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/profil', name: 'profil')]
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig');
    }
}