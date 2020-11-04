<?php

namespace App\Controller;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     */
    public function index(): Response
    {
        //Création de l'objet Faker pour générer du faux contenu.
        $faker = Factory::create('fr_FR');

        $tablePost = [];

        for ($i=0; $i<10 ; $i++){
            $post= new \stdClass();
            $post->title = $faker->sentence();
            $post->content = $faker->text(2500);
            $post->image = "https://picsum.photos/seed/post-$i/750/300";
            $post->author = $faker->name();
            $post->createdAt = $faker->dateTimeBetween('-3 years', 'now', 'Europe/Paris');
            array_push($tablePost, $post);
        }

        //dd($tablePost);

        return $this->render('home/index.html.twig', [
            'tablePost' => $tablePost
        ]);
    }
}
