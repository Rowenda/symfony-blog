<?php


namespace App\Controller\Admin;

use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/admin/post/new", name="admin.post.new")
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        // Création d'un formulaire
        $form = $this->createForm(PostType::class);

        // On va traiter les données du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On réucpère les données du formulaire
            $post = $form->getData();
            $post->setUser($this->getUser());

            // Persistence des données
            $manager->persist($post);
            $manager->flush();
        
            // Ajout d'un message flash
            $this->addFlash('success', 'Votre Article a été ajouté !');

            // Redirection 
            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}