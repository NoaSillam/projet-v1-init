<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/article', name: 'admin_article_')]
class ArticleController extends AbstractController
{


    #[Route('', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('Image')->getData();

            if ($imageFile) {
                // Générer un nom de fichier unique
                $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    
                // Copier le fichier dans le dossier d'images
                $filesystem = new Filesystem();
                $filesystem->copy($imageFile->getPathname(), $this->getParameter('images_directory').'/'.$fileName);
                
    
                // Enregistrer le nom de fichier dans l'entité Article
                $article->setImage($fileName);
            }


            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_article_app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('Image')->getData();

            if ($imageFile) {
                // Générer un nom de fichier unique
                $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    
                // Copier le fichier dans le dossier d'images
                $filesystem = new Filesystem();
                $filesystem->copy($imageFile->getPathname(), $this->getParameter('images_directory').'/'.$fileName);
                
    
                // Enregistrer le nom de fichier dans l'entité Article
                $article->setImage($fileName);
            }

            
            $entityManager->flush();

            return $this->redirectToRoute('admin_article_app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->denyAccessUnlessGranted('ARTICLE_EDIT', $article);
       //  $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        $this->denyAccessUnlessGranted('ARTICLE_DELETE', $article);
        return $this->redirectToRoute('admin_article_app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}