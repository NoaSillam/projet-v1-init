<?php

namespace App\Controller;

use App\Entity\UserArticle;
use App\Form\UserArticleType;
use App\Repository\UserArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user_article')]
class UserArticleController extends AbstractController
{
    #[Route('/', name: 'app_user_article_index', methods: ['GET'])]
    public function index(UserArticleRepository $userArticleRepository): Response
    {
        return $this->render('user_article/index.html.twig', [
            'user_articles' => $userArticleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userArticle = new UserArticle();
        $form = $this->createForm(UserArticleType::class, $userArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userArticle);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_article/new.html.twig', [
            'user_article' => $userArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_article_show', methods: ['GET'])]
    public function show(UserArticle $userArticle): Response
    {
        return $this->render('user_article/show.html.twig', [
            'user_article' => $userArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserArticle $userArticle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserArticleType::class, $userArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_article/edit.html.twig', [
            'user_article' => $userArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_article_delete', methods: ['POST'])]
    public function delete(Request $request, UserArticle $userArticle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userArticle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
