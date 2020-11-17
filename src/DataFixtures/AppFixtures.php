<?php

namespace App\DataFixtures;

use App\Factory\CommentFactory;
use App\Factory\CategoryFactory;
use App\Factory\PostFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //On créé un profil Administrateur
        UserFactory::new()->create([
            'roles' => ['ROLE_ADMIN'],
            'password' => 'admin',
            'email' => 'admin@admin.com'
        ]);
        
        //Création d'utilisateurs grâce a UserFactory, l'usine a fabriquer des utilisateurs
        UserFactory::new()->createMany(10);

        // Création de 5 catégories grâce à la CategoryFactory, l'usine à fabriquer des catégories
        CategoryFactory::new()->createMany(5);
        
        // Création de 10 articles grâce à la PostFactory, l'usine à fabriquer des articles
        PostFactory::new()->createMany(10);
        
        //Création de commentaires grâce a CommentFactory, l'usine a fabriquer des commentaires
        CommentFactory::new()->createMany(100);

        // Enregistrement des objets créés en base de données
        $manager->flush();
    }
}
