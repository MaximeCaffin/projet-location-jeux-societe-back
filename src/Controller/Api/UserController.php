<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// ! Main route 
#[Route('/api/utilisateur', name: 'app_api_user')]
class UserController extends AbstractController
{
// !  Methode for display all data an user 
    #[Route('/', name: 'read', methods:"GET")]
    public function read(UserRepository $userRepo): JsonResponse
    {
// Get user connected
        $user = $this->getUser();

// retrieve logged in user
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié');
        }

// We retrieve dats from the database with user 
        $infoUser = $userRepo->find($user);

// Return a JSON response
        return $this->json($infoUser,  Response::HTTP_OK, [], ['groups' => 'user_info']);
    }

    
}
