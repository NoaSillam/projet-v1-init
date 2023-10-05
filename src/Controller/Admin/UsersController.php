<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users', name: 'admin_users')]
class UsersController extends AbstractController
{


    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

    #[Route('/profil', name: 'profil')]
    public function profil(): Response
    {
        return $this->render('admin/users/profil.html.twig');
    }
}