<?php

namespace App\Controller\Api;

use App\Entity\Rent;
use App\Entity\User;
use App\Repository\RentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

// ! Main route 
#[Route('/api/louer', name: 'app_api_rent_')]
class RentController extends AbstractController
{
// !  Methode for display all rent for an user 
    #[Route('/', name: 'read', methods: "GET")]
    public function read(RentRepository $rentRepo): JsonResponse
    {
// Retrieve authenticated user
        $user = $this->getUser();

// Check if user is authenticated
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié');
        }

// Retrieve user's rentals
        $rentList = $rentRepo->findByUser($user);

// Return locations in JSON format
        return $this->json($rentList,  Response::HTTP_OK, [], ['groups' => 'rent_browse']);
    }



// !  Methode for add a rent 
    #[Route('/add', name: 'add', methods: "POST")]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
// Retrieve data from JSON request
        $json = $request->getContent();

// Deserialize data into Rent object
        $rent = $serializer->deserialize($json, Rent::class, 'json');
        
// retrieve logged in user
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié');
        }

// Set user for rental
        $rent->setUser($user);

// retrieve the game that matches the id
        $game = $rent->getContentRents()->first()->getGame();
        if (!$game) {
        return $this->json([
        'message' => "Le jeu demandé n'existe pas",
                ], 400);
        }

        if ($game->getQuantity() <= 0 || $game->getStatus() === 'Hors de stock') {
            return $this->json([
                'message' => 'Le jeu demandé est actuellement hors de stock',
            ], 400);
        }

// Set initial rental status
        $rent->setStatus('Pending');

// Persist rental and associated content
        $em->persist($rent);
        foreach ($rent->getContentRents() as $contentRent) {
        $em->persist($contentRent);

        $game = $contentRent->getGame();

// Check if the game is in stock
        if ($game->getQuantity() > 0) {
// Decrease the amount of game
            $game->setQuantity($game->getQuantity() - 1);
            
// If the quantity is equal to 0, set the status to "Out of stock"
            if ($game->getQuantity() === 0) {
                $game->setStatus('Hors de stock');
            } 
        }
    }
        $em->flush();

// Return a JSON response
       return $this->json([
        'message' => 'La location a été créée avec succès',
        'rent' => $rent->getId()
       ], 201);
}

}