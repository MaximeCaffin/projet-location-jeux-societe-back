<?php

namespace App\Controller\Back;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ! Main route
#[Route('/back/contact', name: 'app_back_contact_')]
class ContactController extends AbstractController
{



// ! Display all Messages  
    #[Route('/', name: 'browse')]
    public function browse(ContactRepository $contactRepository): Response
    {

// We retrieve messages from the database 
        $contacts = $contactRepository->findAll();

        $data = [];
        foreach ($contacts as $contact) {
            $data[] = [
                'id' => $contact->getId(),
                'firstname' => $contact->getFirstname(),
                'name' => $contact->getName(),
                'company' => $contact->getCompany(),
                'mail' => $contact->getMail(),
                'message' => $contact->getMessage(),
            ];
        }
// Return all messages in a page
        return $this->render('back/contact/browse.html.twig', [
            'contacts' => $data,
        ]);
    }



// ! Delete a message     
    #[Route('/{id}/delete', name: 'delete')]
    public function delete(Contact $contact, EntityManagerInterface $entityManager): Response
    {

// Delete a message and sending the changement to the database
        $entityManager->remove($contact);
        $entityManager->flush();

// Sending a message succes
        $this->addFlash('success', 'Suppression réussie');

// redirection in a other page
        return $this->redirectToRoute('app_back_contact_browse');
    }
    
}