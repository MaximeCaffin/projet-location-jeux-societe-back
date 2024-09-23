<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// ! Main route   
#[Route('api/category', name: 'app_api_category')]
class CategoryController extends AbstractController
{



// ! Methode for display all categories 
    #[Route('/', name: 'browse', methods:"GET")]

    public function browse(CategoryRepository $categoryRepo): JsonResponse
    {
// We retrieve the categories from the database    
     $categoryAllList = $categoryRepo -> findAll();

// Return a JSON response
     return $this->json($categoryAllList,  Response::HTTP_OK, [], ['groups' => 'game_browse']);
    }



// ! Methode for display a category
    #[Route('/{id<\d+>}', name: 'read', methods:"GET")]
    public function read(int $id, CategoryRepository $categoryRepo): JsonResponse
    {

// We retrieve a category from the database with Id   
        $categoryGameList = $categoryRepo -> find($id);

// Check if the category exists in the database
        if (is_null($categoryGameList))
        {
            return $this->categoryNotFound();
        }
// Return a JSON response
        return $this->json($categoryGameList,  Response::HTTP_OK, [], ['groups' => 'category_browse']);
    }

// Function for display a message if no category found
    private function categoryNotFound()
    {

        $data = [
            "success" => false,
            "message" => "category not found"
        ];
// Return a JSON response
        return $this->json($data, Response::HTTP_NOT_FOUND);
    }
}
