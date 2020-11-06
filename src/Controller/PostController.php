<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{slug}", name="post.index")
     * @param Post $post
     * @return Response
     */
    public function index(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        // Création d'un formulaire
        $form = $this->createForm(CommentType::class);
        
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($request->isXmlHttpRequest()) {

            $comment = $form->getData();
            $comment->setPost($post);
            $manager->persist($comment);
            $manager->flush();
            dd($comment);
       }

        if ($form->isSubmitted() && $form->isValid()) {

            // On réucpère les données du formulaire
            $comment = $form->getData();

            // On attribut au champ post l'article sur lequel on se trouve
            $comment->setPost($post);

            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès.');
            return $this->redirect($request->headers->get('referer'));
        }
        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}

