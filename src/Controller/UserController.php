<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/new", name="user.new")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        // Création d'un formulaire
        $form = $this->createForm(UserType::class);

        // On va traiter les données du formulaire
        $form->handleRequest($request);
        
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            
            // On récupère les données du formulaire
            $user = $form->getData();
            
            // Persistence des données
            $manager->persist($user);
            $manager->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'Votre compte a bien été créé, vous pouvez vous connecter.');
            
            // Redirection pour perdre les données de la requête
            return $this->redirectToRoute('security.login');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
            ]);
        }
    }

