<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $postImageDirectory;

    //On ajoute un constructeur pour pouvoir injecter le paramètre $postImageDirectory
    public function __construct(string $postImageDirectory)
    {
        $this->postImageDirectory = $postImageDirectory;
    }

    public function getFunctions(): array
    {
        //On a ici un tableau d'objet TwigFunction qui prend 2paramètres:
        //1er paramètre : nom de la fonction twig qu'on souhaite créer
        //2ème paramètre : la fonction PHP qui va faire le travail
        return [
            new TwigFunction('asset_post_image', [$this, 'assetPostImage']),
        ];
    }

    public function assetPostImage($image)
    {
        // Si $image contient une URL
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            // On retourne directement $image sans faire de modifications
            return $image;
        }

        // Sinon on concatène le chemin vers le dossier des images
        return $this->postImageDirectory . '/' . $image;
    }
}
