<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 
class CategoryController extends AbstractController
{

// ! Display all categories     
    #[Route('/back/category', name: 'app_back_category_browse')]
    public function browse(CategoryRepository $categoryRepo): Response
    {

// We retrieve the categories from the database          
        $allCategories = $categoryRepo->findAll();

// Return all categories in a page
        return $this->render('back/category/browse.html.twig', [
            'categories' => $allCategories,
        ]);
    }



// ! Display a category 
    #[Route('/back/category/{id<\d+>}', name: 'app_back_category_read')]
    public function read(Category $category): Response
    {

// Return a category with id in a page
        return $this->render('back/category/read.html.twig', [
            'category' => $category
        ]);
    }



// ! Create a category 
    #[Route('/back/category/create', name: 'app_back_category_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

// Creation a new instance and a form for category 
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

// Checking the form data then sending data to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
// Sending a message success and redirection in a other page
            $this->addFlash('success', 'Catégorie créée avec succès');

            return $this->redirectToRoute('app_back_category_read', ['id' => $category->getId()]);
        }
        
// Display a page with form category
        return $this->render('back/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }



// ! Edit a category 
    #[Route('/back/category/{id<\d+>}/update', name: 'app_back_category_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
// Retrieve a form category
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

// Checking the form data then sending data to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
// Sending a message success and redirection in a other page
            $this->addFlash('success', 'Modification réussie');

            return $this->redirectToRoute('app_back_category_read', ['id' => $category->getId()]);
        }

// Display a page with a form category
        return $this->render('back/category/edit.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }



// ! Delete a category 
    #[Route('/back/category/{id}/delete', name: 'app_back_category_delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response
    {
// Delete a category and sending the changement to the database
        $entityManager->remove($category);
        $entityManager->flush();

// Sending a message succes
        $this->addFlash('success', 'Suppression réussie');

// redirection in a other page
        return $this->redirectToRoute('app_back_category_browse');
    }
}
