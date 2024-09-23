<?php

namespace App\Controller\Api;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


// ! Main route 
#[Route('/api/jeu', name: 'app_api_game')]
class GameController extends AbstractController
{



// !  Methode for display all game 
    #[Route('/', name: 'browse', methods: "GET")]
    public function browse(GameRepository $gameRepo): JsonResponse
    {
// We retrieve the games from the database   
        $gameList = $gameRepo->findAll();


// Return a JSON response
        return $this->json($gameList, Response::HTTP_OK, [], ['groups' => 'game_browse']);
    }



// ! Methode for display a game
    #[Route('/{id<\d+>}', name: 'read', methods: "GET")]
    public function read(int $id, GameRepository $gameRepo): JsonResponse
    {
// We retrieve a game from the database with Id 
        $gameListId = $gameRepo->find($id);

// Check if the category exists in the database
        if (is_null($gameListId)) {
            return $this->gameNotFound();
        }



// Return a JSON response
        return $this->json($gameListId, Response::HTTP_OK, [], ['groups' => 'game_browse']);
    }

// Function for display a message if no game found    
    private function gameNotFound(): JsonResponse
    {
        $data = [
            "success" => false,
            "message" => "game not found"
        ];

        return $this->json($data, Response::HTTP_NOT_FOUND);
    }

    

}