<?php

namespace App\Controller\Back;

use App\Entity\Game;
use App\Entity\ContentRent;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

// ! Display all games      
    #[Route('/back/game', name: 'app_back_game_browse')]
    public function browse(GameRepository $gameRepo): Response
    {

// We retrieve the games from the database  
        $allGameList = $gameRepo->findBy([], ['Name' => 'ASC']);

// Return all games in a page
        return $this->render('back/game/browse.html.twig', [
            'gameList' => $allGameList,
        ]);
    }



// ! Display a game  
    #[Route('/back/game/{id<\d+>}', name: 'app_back_game_read')]
    public function read(Game $game): Response
    {
// Return a game with id in a page
        return $this->render('back/game/read.html.twig', [
            'game' => $game
        ]);
    }



// ! Create a game  
    #[Route('/back/game/create', name: 'app_back_game_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

// Creation a new instance and a form for game 
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

// Checking the form data then sending data to the database        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();
// Sending a message success and redirection in a other page
            $this->addFlash('success', 'Jeu créé avec succès');

            return $this->redirectToRoute('app_back_game_read', ['id' => $game->getId()]);
        }

// Display a page with form game
        return $this->render('back/game/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }



// ! Edit a game  
    #[Route('/back/game/{id<\d+>}/update', name: 'app_back_game_edit')]
    public function edit(Game $game, Request $request, EntityManagerInterface $entityManager): Response
    {
// Retrieve a form game
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

// Checking the form data then sending data to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
// Sending a message success and redirection in a other page
            $this->addFlash('success', 'Modification réussie');

            return $this->redirectToRoute('app_back_game_read', ['id' => $game->getId()]);
        }

// Display a page with a form game        
        return $this->render('back/game/edit.html.twig', [
            'form' => $form,
            'game' => $game,
        ]);
    }



// ! Delete a game 
    #[Route('/back/game/{id}/delete', name: 'app_back_game_delete')]
    public function delete(Game $game, EntityManagerInterface $entityManager): Response
    {
// Delete related data in the content_rent table
        $contentRents = $entityManager->getRepository(ContentRent::class)->findBy(['game' => $game]);
        foreach ($contentRents as $contentRent) {
            $entityManager->remove($contentRent);
        }

// Delete a game and sending the changement to the database
        $entityManager->remove($game);
        $entityManager->flush();

// Sending a message succes
        $this->addFlash('success', 'Suppression réussie');

// redirection in a other page        
        return $this->redirectToRoute('app_back_game_browse');
    }
}