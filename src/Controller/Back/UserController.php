<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

// ! Display all users  
    #[Route('/back/user', name: 'app_back_user_browse')]
    public function browse(UserRepository $userRepo): Response
    {
// We retrieve the rents from the database  
        $userList = $userRepo->findAll();

// Return all rents in a page
        return $this->render('back/user/browse.html.twig', [
            'userList' => $userList,
        ]);
    }



// ! Display an user  
    #[Route('/back/user/{id<\d+>}', name: 'app_back_user_read')]
    public function read(User $user): Response
    {
// Return an user with id in a page
        return $this->render('back/user/read.html.twig', [
            'user' => $user,
        ]);
    }



// ! Create an user  
    #[Route('/back/user/create', name: 'app_back_user_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
// Creation a new instance
        $user = new User();

// Creation a new form for user 
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

// Checking the form data then sending data to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
// Sending a message success and redirection in a other page
            $this->addFlash('success', 'Utilisateur créé avec succès');

            return $this->redirectToRoute('app_back_user_read', ['id' => $user->getId()]);
        }

// Display a page with form user
        return $this->render('back/user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// ! Edit an user 
    #[Route('/back/user/{id<\d+>}/edit', name: 'app_back_user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
// Retrieve a form user
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);


// Checking the form data then sending data to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
// Sending a message success and redirection in a other page
            $this->addFlash('success', 'Modification réussie');

            return $this->redirectToRoute('app_back_user_read', ['id' => $user->getId()]);
        }

// Display a page with a form user
        return $this->render('back/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

// ! Delete an user 
    #[Route('/back/user/{id}/delete', name: 'app_back_user_delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
// Delete a category and sending the changement to the database
        $entityManager->remove($user);
        $entityManager->flush();

// Sending a message succes
        $this->addFlash('success', 'Suppression réussie');

// redirection in a other page
        return $this->redirectToRoute('app_back_user_browse');
    }
}