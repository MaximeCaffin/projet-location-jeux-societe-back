<?php

namespace App\Controller\Back;

use App\Entity\Rent;
use App\Entity\ContentRent;
use App\Form\RentFormType;
use App\Repository\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{

// ! Display all rents   
    #[Route('/back/rent', name: 'app_back_rent_browse')]
    public function browse(RentRepository $rentRepo): Response
    {
// We retrieve the rents from the database  
        $allRentList = $rentRepo->findBy([], ['date_debut' => 'DESC']);

// Return all rents in a page
        return $this->render('back/rent/browse.html.twig', [
            'rentList' => $allRentList,
        ]);
    }



// ! Display a rent  
    #[Route('/back/rent/{id<\d+>}', name: 'app_back_rent_read')]
    public function read(Rent $rent): Response
    {
// Return a rent with id in a page
        return $this->render('back/rent/read.html.twig', [
            'rent' => $rent
        ]);
    }



// ! Create a rent  
    #[Route('/back/rent/create', name: 'app_back_rent_create')]
public function create(Request $request, EntityManagerInterface $entityManager): Response
{
// Creation a new instance
    $rent = new Rent();

// Set initial rental status
    $rent->setStatus('Pending');

// Creation a new form for rent 
    $form = $this->createForm(RentFormType::class, $rent);
    $form->handleRequest($request);

// Checking the form data   

    if ($form->isSubmitted() && $form->isValid()) {
        $contentRents = $form->get('contentRents')->getData();
        foreach ($contentRents as $contentRent) {
// Creation and persist associated content 
            $game = $contentRent->getGame();
            $rent->addContentRent($contentRent);
            $entityManager->persist($contentRent);
        }

// Persist and sending data to the database     
        $entityManager->persist($rent);
        $entityManager->flush();

// Sending a message success and redirection in a other page
        $this->addFlash('success', 'Location créée avec succès');

        return $this->redirectToRoute('app_back_rent_read', ['id' => $rent->getId()]);
    }

// Display a page with form rent
    return $this->render('back/rent/create.html.twig', [
        'form' => $form->createView(),
    ]);
}



// ! Edit a rent 
#[Route('/back/rent/{id<\d+>}/update', name: 'app_back_rent_edit')]
public function edit(Rent $rent, Request $request, EntityManagerInterface $entityManager): Response
{
// Retrieve a form rent    
    $form = $this->createForm(RentFormType::class, $rent);
    $form->handleRequest($request);

// Checking the form data and the associated content
    if ($form->isSubmitted() && $form->isValid()) {
        $contentRents = $form->get('contentRents')->getData();

// Delete the ex contentRents
        foreach ($rent->getContentRents() as $contentRent) {
            $entityManager->remove($contentRent);
        }
        $rent->getContentRents()->clear();

// Add the new contentRents
        foreach ($contentRents as $contentRent) {
            $game = $contentRent->getGame();
            $newContentRent = new ContentRent();
            $newContentRent->setGame($game); 
            $rent->addContentRent($newContentRent);
            $entityManager->persist($newContentRent);
        }
        
// Sending data to the database
        $entityManager->flush();

// Sending a message success and redirection in a other page
        $this->addFlash('success', 'Modification réussie');
        return $this->redirectToRoute('app_back_rent_read', ['id' => $rent->getId()]);
    }

// Display a page with a form rent   
    return $this->render('back/rent/edit.html.twig', [
        'form' => $form->createView(),
        'rent' => $rent,
    ]);
}



// ! Delete a rent 
    #[Route('/back/rent/{id}/delete', name: 'app_back_rent_delete')]
    public function delete(Rent $rent, EntityManagerInterface $entityManager): Response
    {

// Delete related data in the content_rent table
        $contentRents = $rent->getContentRents();
        foreach ($contentRents as $contentRent) {
            $game = $contentRent->getGame();
            $game->removeContentRent($contentRent);
            $entityManager->remove($contentRent);
        }

// Delete a game and sending the changement to the database
        $entityManager->remove($rent);
        $entityManager->flush();
// Sending a message succes
        $this->addFlash('success', 'Suppression réussie');

// redirection in a other page  
        return $this->redirectToRoute('app_back_rent_browse');
    }
}
