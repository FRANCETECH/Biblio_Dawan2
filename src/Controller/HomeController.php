<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // Notion de service  // Exemple pour le système de service

    #[Route('/demo')]
    public function demo(ValidatorInterface $validator) // Ici j'ai injecté un service, ce qui m'a permi de faire un traitement
    {
        //dd($validator);
        $recipe = new Livre();
        $errors = $validator->validate($recipe);    // Valide moi ma recette
        dd((string) ($errors));  // Affiche moi les erreurs sous forme de chaines de caractères
    }


}
