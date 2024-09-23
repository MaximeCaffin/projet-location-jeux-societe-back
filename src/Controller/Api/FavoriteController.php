<?php

namespace App\Controller\Api; 

use App\Entity\ContentFavorite;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// ! Main route 
#[Route('/api/favoris', name: 'app_api_favorite_')]
class FavoriteController extends AbstractController
{



// ! Methode for display all favorites     
    #[Route('/', name: 'list',methods:"GET")]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
// Get user connected
        $user = $this->getUser();

// Display all favorites a user
        $favorites = $entityManager->getRepository(ContentFavorite::class)->findBy(['user' => $user]);
        
// add other properties if we want
        $data = [];
        foreach ($favorites as $favorite) {
            $data[] = [
                'id' => $favorite->getGame()->getId(),
                'name' => $favorite->getGame()->getName(),
                'description' => $favorite->getGame()->getDescription(),
                'price' => $favorite->getGame()->getPrice(),
                'status' => $favorite->getGame()->getStatus(),
                'category' => $favorite->getGame()->getCategory(),
                'image' => $favorite->getGame()->getImage(),
            ];
        }
// Return a JSON response
        return new JsonResponse($data);
    }



// ! Methode for add a favorite    
    #[Route('/add', name: 'add', methods:"POST")]
    public function add(Request $request, EntityManagerInterface $em, SerializerInterface $serializer ): JsonResponse
    {
// Retrieve data from JSON request
          $json = $request->getContent();

// Deserialize data into ContentFavorite object
        $favorite = $serializer->deserialize($json, ContentFavorite::class, 'json');  

// retrieve logged in user
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié');
        }

// Set user for favorites
        $favorite->setUser($user);

// retrieve the game that matches the id
        $game = $favorite->getGame();
        if (!$game) {
          throw $this->createNotFoundException('Le jeu demandé n\'existe pas');
        }

// Retrieve the repository for ContentFavorite
        $favoriteRepository = $em->getRepository(ContentFavorite::class);

// Check if favorite already exists for user and game
        $existingFavorite = $favoriteRepository->findOneBy([
        'user' => $user,
        'game' => $game
        ]);

        if ($existingFavorite) {
// Favorite already exists
            return $this->json([
                'message' => 'Ce jeu est déjà dans vos favoris',
                'favoris' => $existingFavorite->getId()
            ], 200);
        }

// Persist favorite
        $em->persist($favorite);
        $em->flush();

// Return a JSON response
       return $this->json([
        'message' => 'Le favoris a été créée avec succès',
        'favoris' => $favorite->getId()
       ], 201);
}



// ! Methode for delete a favorites  
    #[Route('/delete', name: 'delete', methods:"DELETE")]
    public function delete(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
// Retrieve data from JSON request
        $json = $request->getContent();

// Deserialize data into ContentFavorite object
     $favorite = $serializer->deserialize($json, ContentFavorite::class, 'json');  


// retrieve logged in user
     $user = $this->getUser();
     if (!$user instanceof User) {
         throw $this->createAccessDeniedException('Utilisateur non authentifié');
     }

// Set user for favorites
      $favorite->setUser($user);

// retrieve the game that matches the id
      $game = $favorite->getGame();
      if (!$game) {
        throw $this->createNotFoundException('Le jeu demandé n\'existe pas');
      }

// Retrieve the repository for ContentFavorite
      $favoriteRepository = $em->getRepository(ContentFavorite::class);

// Check if favorite already exists for user and game
      $existingFavorite = $favoriteRepository->findOneBy([
      'user' => $user,
      'game' => $game
      ]);

// Check that the favorite exists and return a JSON response
      if ($existingFavorite) {
        $em->remove($existingFavorite);
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Ce jeu a été supprimé dans vos favoris',
            'favoris' => $existingFavorite->getId()
        ], 204);
    } else {
        return $this->json([
            'success' => false,
            'message' => "Ce jeu n'est pas dans vos favoris",
        ], 200);
    }
    

    }



// ! Methode for display a favorite with Id  
    #[Route('/read/{id}', name: 'read',methods:"GET")]
    public function read(ContentFavorite $favorite): JsonResponse
    {
// add other properties if we want
        $data = [
            'id' => $favorite->getId(),
            'game' => [
                'id' => $favorite->getGame()->getId(),
                'name' => $favorite->getGame()->getName(),
                'description' => $favorite->getGame()->getDescription(),
                'price' => $favorite->getGame()->getPrice(),
                'status' => $favorite->getGame()->getStatus(),
                'category' => $favorite->getGame()->getCategory(),
                'image' => $favorite->getGame()->getImage(),
            ],
// add other properties if we want
            'user' => [
                'id' => $favorite->getUser()->getId(),
                'firstname' => $favorite->getUser()->getFirstname(),
                'lastname' => $favorite->getUser()->getLastname(),
            ],
        ];
// Return a JSON response
        return new JsonResponse($data);
    }

}