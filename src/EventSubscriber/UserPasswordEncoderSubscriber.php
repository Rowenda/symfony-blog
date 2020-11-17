<?php

namespace App\EventSubscriber;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserPasswordEncoderSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function onFormPostSubmit($event)
    {
        //On récupère l'objet formulaire qui nous intéresse.
        $form = $event->getForm();

        //On récupère l'objet user car on devra le modifier (y ajouter le MDP hashé)
        $user = $event->getData();

        //On récupère le mot de passe de l'utilisateur dans une variable
        $plainTextPassword = $form->get('plaintextPassword')->getData();

        if ($plainTextPassword) {
            //On hash le password pour le protéger
            $hashedPassword = $this->encoder->encodePassword($user, $plainTextPassword);
            
            //On transmet le mot de passe hashé a l'objet User pour la propriété password.
            $user->setPassword($hashedPassword);
        }
    }


    public static function getSubscribedEvents()
    {
        return [
            'form.post_submit' => 'onFormPostSubmit',
        ];
    }
}
