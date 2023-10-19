<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Mobile_Detect;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index_1', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/article.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    #[Route('/Isolation', name: 'app_article_index_2', methods: ['GET'])]
    public function isolation(Request $request, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findByType(2);
        $screenWidth = $request->query->get('screen_width');

        // Définissez les variables en fonction de la largeur de l'écran
        $isDesktop = $screenWidth > 768;
        $isMobile = $screenWidth <= 768;

        // $isMobile = $this->detectMobile();
        return $this->render('article/isolation.html.twig', [
            'articles' => $articles,
            'isDesktop' => $isDesktop,
            'isMobile' => $isMobile,
        // 'isMobile' => $isMobile,
        ]);
    }



    #[Route('/pompeAchaleur', name: 'app_article_index_3', methods: ['GET'])]
    public function pompeAchaleur(Request $request, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findByType(1);
        $screenWidth = $request->query->get('screen_width');

        // Définissez les variables en fonction de la largeur de l'écran
        $isDesktop = $screenWidth > 768;
        $isMobile = $screenWidth <= 768;

        // $isMobile = $this->detectMobile();
        return $this->render('article/pompeAchaleur.html.twig', [
            'articles' => $articles,
            'isDesktop' => $isDesktop,
            'isMobile' => $isMobile,
        // 'isMobile' => $isMobile,
        ]);
    }
    #[Route('/solaire', name: 'app_article_index_4', methods: ['GET'])]
    public function solaire(Request $request, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findByType(4);
        $screenWidth = $request->query->get('screen_width');

        // Définissez les variables en fonction de la largeur de l'écran
        $isDesktop = $screenWidth > 768;
        $isMobile = $screenWidth <= 768;

        // $isMobile = $this->detectMobile();
        return $this->render('article/solaire.html.twig', [
            'articles' => $articles,
            'isDesktop' => $isDesktop,
            'isMobile' => $isMobile,
        // 'isMobile' => $isMobile,
        ]);
    }
    #[Route('/aidePrime', name: 'app_article_index_5', methods: ['GET'])]
    public function aidePrime(Request $request, ArticleRepository $articleRepository): Response
    {
         $articles = $articleRepository->findByType(4);
        // $screenWidth = $request->query->get('screen_width');

        // // Définissez les variables en fonction de la largeur de l'écran
        // $isDesktop = $screenWidth > 768;
        // $isMobile = $screenWidth <= 768;

        // $isMobile = $this->detectMobile();
        return $this->render('article/aidePrime.html.twig', [
            'articles' => $articles,
          
        ]);
    }






    // private function detectMobile()
    // {
    //     $detect = new Mobile_Detect();

    //     return $detect->isMobile();
    // }

    #[Route('/accueil', name: 'app_article_index', methods: ['GET'])]
    public function Accueil(): Response
    {
        return $this->render('accueil/accueil.html.twig');
    }

    // #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $article = new Article();
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $imageFile = $form->get('Image')->getData();

    //         if ($imageFile) {
    //             // Générer un nom de fichier unique
    //             $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    
    //             // Copier le fichier dans le dossier d'images
    //             $filesystem = new Filesystem();
    //             $filesystem->copy($imageFile->getPathname(), $this->getParameter('images_directory').'/'.$fileName);
                
    
    //             // Enregistrer le nom de fichier dans l'entité Article
    //             $article->setImage($fileName);
    //         }


    //         $entityManager->persist($article);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('article/new.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/showArticle.html.twig', [
            'article' => $article,
        ]);
    }
    #[Route('/plus/{id}', name: 'app_article_show_voir_plus', methods: ['GET'])]
    public function show_voir_plus(Article $article): Response
    {
        return $this->render('article/showVoirPlus.html.twig', [
            'article' => $article,
        ]);
    }


    // #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $imageFile = $form->get('Image')->getData();

    //         if ($imageFile) {
    //             // Générer un nom de fichier unique
    //             $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    
    //             // Copier le fichier dans le dossier d'images
    //             $filesystem = new Filesystem();
    //             $filesystem->copy($imageFile->getPathname(), $this->getParameter('images_directory').'/'.$fileName);
                
    
    //             // Enregistrer le nom de fichier dans l'entité Article
    //             $article->setImage($fileName);
    //         }

            
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('article/edit.html.twig', [
    //         'article' => $article,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    // public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($article);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    // }
}
