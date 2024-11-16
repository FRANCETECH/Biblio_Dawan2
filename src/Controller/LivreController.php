<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'livre.index')]
    public function index(Request $request, LivreRepository $repository): Response
    {
        //dd($request);
        //$livres = $repository->findTotalYear();
        //$livres = $repository->findWithPublicationYearLowerThan(2023);
        //dd($livres);
        $livres = $repository->findAll();
        //dd($livres);

        return $this->render('livre/index.html.twig', [
            'livres' => $livres
        ]);
    }


    // Cette show est la fonction index() au debut   //requirements: format attendu  
    // 'slug' => '[a-z09-]+'] : erreur pour les majuscule comme: https://127.0.0.1:8000/livre/picasso-29
    #[Route('/livre/{slug}-{id}', name: 'livre.show', requirements: ['id' => '\d+', 'slug' => "[A-Za-z0-9-'éàùç]*"])]
    public function show(Request $request, string $slug, int $id, LivreRepository $repository): Response
    {
        //dd($request); // (1)
        // https://localhost:8000/livre/symfony-7  //  (2)
        //dd($request->attributes->get('slug'), $request->attributes->get('id'));// Permet de recuperer ces attributs. On utiliser getInt à la place de get

        /* A faire une fois la BDD créee avec les 3 livres */
        $livre = $repository->find($id);
        if ($livre->getSlug() !== $slug) {
            return $this->redirectToRoute('livre.show', ['slug' => $livre->getSlug(), 'id' => $livre->getId()]);
        }

        return $this->render('livre/show.html.twig', [
            'livre' => $livre
        ]);
    }


     // Partie à faire au chapitre formulaire
    // J'utilise id pour retrouver livre / Ou simplement pour recuperer une recette
    //  L'EntityManagerInterface est utilisée pour gérer les entités dans la base de données. Cela inclut la création, la lecture, la mise à jour et la suppression (CRUD) des entités.
    #[Route('/livre/{id}/edit', name: 'livre.edit', methods: ['GET', 'POST'])]
    public function edit(Livre $livre, Request $request, EntityManagerInterface $em)
    {
        //dd($livre);
        // Crée et renvoie une instance Form à partir du type du formulaire. Premier parametre: le formulaire qu'on souhaite utilisé,
        $form = $this->createForm(LivreType::class, $livre);  //second parametre:les données, ici l'entité pré rempli avec les données provenat de la BDD
        $form->handleRequest($request);  // Je demande à mon formulaire de gérer la requête, ce qui me permettra d'envoyer les donner à la fin
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();   // je fais appelle à mon EntityManagerInterface, et je peux lui dire flush (sauvegarde) mes données en tenant compte du changement.
            /*message à afficher à l'utilisateur. En parametre, on n'a le type et le message. On consomme le message sur twig grace notre objet global 
            app, voir dans base.html.twig*/
            $this->addFlash('success', 'Le livre a bien été modifié');
            return $this->redirectToRoute('livre.index');
        }

        return $this->render('livre/edit.html.twig', [
            'form' => $form,
            'livre' => $livre
        ]);
    }

    #[Route('/livre/create', name: 'livre.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $livre = new livre();
        $form = $this->createForm(livreType::class, $livre);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($livre);
            $em->flush();

            $this->addFlash('success', 'La recette a bien été créee');
            return $this->redirectToRoute('livre.index');
        }

        return $this->render('livre/create.html.twig', [
            'form' => $form
        ]);

    }


    #[Route('/livre/{id}/edit', name: 'livre.delete', methods: ['DELETE'])]
    public function remove(livre $livre, EntityManagerInterface $em)
    {
        $em->remove($livre);
        $em->flush(); // Sauvegarde les modifications au niveau de la BDD
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('livre.index');
    }









}
