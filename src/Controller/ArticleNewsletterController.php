<?php

namespace App\Controller;

use App\Entity\ArticleNewsletter;
use App\Entity\UserArticle;
use App\Form\ArticleNewsletterType;
use App\Repository\ArticleNewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\UserNewsletterRepository;
use App\Service\SendMailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/article_newsletter')]
class ArticleNewsletterController extends AbstractController
{
    #[Route('/', name: 'app_article_newsletter_index', methods: ['GET'])]
    public function index(ArticleNewsletterRepository $articleNewsletterRepository): Response
    {
        return $this->render('article_newsletter/index.html.twig', [
            'article_newsletters' => $articleNewsletterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_newsletter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleNewsletter = new ArticleNewsletter();
        $form = $this->createForm(ArticleNewsletterType::class, $articleNewsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Générer un nom de fichier unique
                $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    
                // Copier le fichier dans le dossier d'images
                $filesystem = new Filesystem();
                $filesystem->copy($imageFile->getPathname(), $this->getParameter('images_directory').'/'.$fileName);
                
    
                // Enregistrer le nom de fichier dans l'entité Article
                $articleNewsletter->setImage($fileName);
            }
            $entityManager->persist($articleNewsletter);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_newsletter/new.html.twig', [
            'article_newsletter' => $articleNewsletter,
            'form' => $form,
        ]);
    }


    
    #[Route('/{id}', name: 'app_article_newsletter_show', methods: ['GET'])]
    public function show(ArticleNewsletter $articleNewsletter): Response
    {
        return $this->render('article_newsletter/show.html.twig', [
            'article_newsletter' => $articleNewsletter,
        ]);
    }

  

    // #[Route('/send_newsletter', name: 'send_newsletter')]
    // public function sendNewsletter(UserNewsletterRepository $userNewsletterRepository, SendMailService $mail, EntityManagerInterface $entityManager): Response // Injectez EntityManagerInterface
    // {
    //     // Récupérer tous les utilisateurs de la newsletter
    //     $users = $userNewsletterRepository->findAll();

    //     // Récupérer l'article que vous souhaitez envoyer
    //     $article = $entityManager->getRepository(ArticleNewsletter::class)->find(1); // Remplacez 1 par l'ID de l'article que vous souhaitez envoyer

    //     // Vérifiez que l'article existe
    //     if (!$article) {
    //         $this->addFlash('danger', 'L\'article n\'existe pas.');
    //         return $this->redirectToRoute('newsletter_dashboard'); // Remplacez 'newsletter_dashboard' par la route de votre tableau de bord newsletter
    //     }

    //     // Envoi de l'article à tous les utilisateurs de la newsletter
    //     foreach ($users as $user) {
    //         $mail->send(
    //             'noasillam@gmail.com',
    //             $user->getAdresseMail(),
    //             'Sujet de l\'article', // Remplacez par le sujet de l'e-mail
    //             'register', // Remplacez par le template d'e-mail que vous souhaitez utiliser
    //             ['user' => $user, 'article' => $article]
    //         );
    //     }

    //     $this->addFlash('success', 'L\'article a été envoyé à tous les utilisateurs de la newsletter.');
    //     return $this->redirectToRoute('newsletter_dashboard'); // Remplacez 'newsletter_dashboard' par la route de votre tableau de bord newsletter
    // }



    #[Route('/send_newsletter/{id}', name: 'send_newsletter')]
    public function sendNewsletter(UserNewsletterRepository $userNewsletterRepository, ArticleNewsletter $articleNewsletter, MailerInterface $mailer, EntityManagerInterface $entityManager ):Response
    {
        $users = $userNewsletterRepository->findAll();
        try {
        foreach($users as $user)
        {
                $email = (new TemplatedEmail())
                    -> from('noasillam@gmail.com')
                    -> to($user->getAdresseMail())
                    ->subject($articleNewsletter->getNom())
                    ->htmlTemplate('emails/newsletter.html.twig')
                    ->context(compact('articleNewsletter', 'user'))
                    ;
                    $mailer->send($email);

                    $userArticle = new UserArticle();
                    $userArticle->setUserNewsletter($user);
                    $userArticle->setArticleNewsletter($articleNewsletter);
            
                    // Persist the UserArticle entity
                    $entityManager->persist($userArticle);

            
        }
        $entityManager->flush();
        $this->addFlash('success', 'La newsletter a bien était envoyée !!!!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'envoi de la newsletter : ' . $e->getMessage());
        }
        return $this->redirectToRoute('app_article_newsletter_index');

    }













    #[Route('/{id}/edit', name: 'app_article_newsletter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArticleNewsletter $articleNewsletter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleNewsletterType::class, $articleNewsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Générer un nom de fichier unique
                $fileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    
                // Copier le fichier dans le dossier d'images
                $filesystem = new Filesystem();
                $filesystem->copy($imageFile->getPathname(), $this->getParameter('images_directory').'/'.$fileName);
                
    
                // Enregistrer le nom de fichier dans l'entité Article
                $articleNewsletter->setImage($fileName);
            }


            $entityManager->flush();

            return $this->redirectToRoute('app_article_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_newsletter/edit.html.twig', [
            'article_newsletter' => $articleNewsletter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_newsletter_delete', methods: ['POST'])]
    public function delete(Request $request, ArticleNewsletter $articleNewsletter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleNewsletter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($articleNewsletter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_newsletter_index', [], Response::HTTP_SEE_OTHER);
    }
}
