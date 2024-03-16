<?php

namespace App\Controller;

use App\Entity\UserNewsletter;
use App\Form\UserNewsletterType;
use App\Repository\UserNewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user_newsletter')]
class UserNewsletterController extends AbstractController
{
    #[Route('', name: 'app_user_newsletter_index', methods: ['GET'])]
    public function index(UserNewsletterRepository $userNewsletterRepository): Response
    {
        return $this->render('user_newsletter/index.html.twig', [
            'user_newsletters' => $userNewsletterRepository->findAll(),
        ]);
    }
    #[Route('/desab/{userId}', name: 'app_user_newsletter_desb', methods: ['GET'])]
    public function desab($userId): Response
    {
        // Utilisez $userId comme nécessaire dans votre action
        return $this->render('user_newsletter/desab.html.twig', ['userId' => $userId]);
    }

    #[Route('/new', name: 'app_user_newsletter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userNewsletter = new UserNewsletter();
        $form = $this->createForm(UserNewsletterType::class, $userNewsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userNewsletter);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_newsletter/new.html.twig', [
            'user_newsletter' => $userNewsletter,
            'form' => $form,
        ]);
    }


    #[Route('/new_user', name: 'app_user_newsletter_new_user', methods: ['GET', 'POST'])]
    public function new_user(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userNewsletter = new UserNewsletter();
        $form = $this->createForm(UserNewsletterType::class, $userNewsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userNewsletter);
            $entityManager->flush();

            return $this->redirectToRoute('main_index_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_newsletter/new_user.html.twig', [
            'user_newsletter' => $userNewsletter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_newsletter_show', methods: ['GET'])]
    public function show(UserNewsletter $userNewsletter): Response
    {
        return $this->render('user_newsletter/show.html.twig', [
            'user_newsletter' => $userNewsletter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_newsletter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserNewsletter $userNewsletter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserNewsletterType::class, $userNewsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_newsletter/edit.html.twig', [
            'user_newsletter' => $userNewsletter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_newsletter_delete', methods: ['POST'])]
    public function delete(Request $request, UserNewsletter $userNewsletter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $userNewsletter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userNewsletter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_newsletter_index', [], Response::HTTP_SEE_OTHER);
    }
}
