<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// ! Main route 
#[Route('/api/contact', name: 'app_api_contact')]
class ContactController extends AbstractController




// ! List of ban mail
{
    private $invalidEmailDomains = [
    '@test.com', 
    '@jupimail.com', 
    '@example.com', 
    '@subdomain.example.com',  
    '@123.123.123.123', 
    '@[123.123.123.123]', 
    '@example-one.com', 
    '@example.name', 
    '@example.museum', 
    '@example.co.jp',
    '@.com',
    '@spam.com',
    '@malicious.com',
    '@mailinator.com', 
    '@guerrillamail.com', 
    '@guerrillamailblock.com',
    '@spam.com', 
    '@spam4.me', 
    '@grr.la', 
    '@yopmail.com', 
    '@mailcatch.com', 
    '@mailnesia.com', 
    '@mailexpire.com', 
    '@maildrop.cc', 
    '@maildu.de', 
    '@mailtemporaire.fr', 
    '@tempemail.net', 
    '@throwawaymail.com', 
    '@10minutemail.com', 
    '@mailinator.net', 
    '@mailnull.com', 
    '@tempmail.net', 
    '@tempmail.de', 
    '@spambox.us', 
    '@spambox.info', 
    '@spambox.me', 
    '@spambox.irc', 
    '@spambox.org', 
    '@spambox.biz', 
    '@spambox.co', 
    '@spambox.xyz', 
    '@spambox.email', 
    '@spambox.ga', 
    '@spambox.ml', 
    '@spambox.tk', 
// Add invalidates domains
    ];



// ! Method for retrieve the contact form
    #[Route('/', name: 'add', methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
// Get data from JSON request        
        $json = $request->getContent();

// Deserialize data into Rent object
        $contact = $serializer->deserialize($json, Contact::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => new Contact(),
        ]);


// Validation datas received
        $errors = $validator->validate($contact);


        
// Verification of email with the table ban email
        $email = $contact->getMail();
        foreach ($this->invalidEmailDomains as $invalidDomain) {
            if (str_ends_with($email, $invalidDomain)) {
                $violation = new ConstraintViolation(
                    "L'adresse email {{ email }} n'est pas autorisée.",
                    null,
                    [],
                    $email,
                    'mail',
                    null
                );
                $errors->add($violation);
                break;
            }
        }
// Message if there is an erreur
        if (count($errors) > 0) {
            $data = [
                'success' => false,
                'errors' => $errors,
            ];

            return $this->json($data, Response::HTTP_BAD_REQUEST);
        }
// Persister the data of form
        $em->persist($contact);
        $em->flush();

// Return a JSON response
        return $this->json($contact, Response::HTTP_OK);
    }
}