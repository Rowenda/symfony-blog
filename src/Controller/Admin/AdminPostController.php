<?php


namespace App\Controller\Admin;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Filesystem\Filesystem;
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

            //On récupère les données du formulaire c'est un objet de la classe UploadFile
            $imageUploadFile = $form->get('imageFile')->getData();

            //Si le champs image est rempli (car pas obligatoire) alors on va faire l'upload
            if ($imageUploadFile) {

                //on commence par récupérer le nom original du fichier uploadé avec la fonction de PHP pathinfo() 
                $originalFilename = pathinfo($imageUploadFile->getClientOriginalName(), PATHINFO_FILENAME);

                //On va ensuite sluggifier le nom du fichier
                $safeFilename = Urlizer::urlize($originalFilename);

                //On construit ensuite le nom complet du fichier en ajoutant l'extension 
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageUploadFile->guessExtension();

                //On uploade ensuite l'image, càd on copie le fichier temporaire vers le répertoire de destination final du fichier 
                $imageUploadFile->move(
                    $this->getParameter('post_image_directory'),
                    $newFilename
                );

                //On termine par enregistrer le nom du fichier dans notre entité Post : 
                $post->setImage($newFilename);
            }

            // L'entity manager enregistre notre entité $post en base de données
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

    /**
     * @Route("/admin/post/edit/{id}", name="admin.post.edit")
     * @return Response
     */
    public function edit(Post $post, Request $request, EntityManagerInterface $manager, Filesystem $filesystem): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            $post->setUser($this->getUser());

            if ($imageUploadFile = $form->get('imageFile')->getData()) {

                // Si il existe une image actuellement on la supprime
                if ($currentFilename = $post->getImage()) {
    
                    //On construit le chemin vers le dossier d'image et le nom de l'image.
                    $currentPath = $this->getParameter('post_image_directory') . '/' . $currentFilename;

                    //On vérifie si l'image existe, si elle existe, on la supprime.
                    if ($filesystem->exists($currentPath)) {
                        $filesystem->remove($currentPath);
                    }
                }


                $originalFilename = pathinfo($imageUploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = Urlizer::urlize($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageUploadFile->guessExtension();

                $imageUploadFile->move(
                    $this->getParameter('post_image_directory'),
                    $newFilename
                );

                $post->setImage($newFilename);
            }

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Article modifié.');

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}