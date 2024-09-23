<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// ! Main route 
#[Route('/api/register', name: 'app_register_')]

class RegisterController extends AbstractController
{



// ! List of ban mail
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
     



// ! Method for retrieve the register form        
    #[Route('/', name: 'add' ,methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, 
    EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
// Get data from JSON request         
        $json = $request->getContent();

// Deserialize data into Rent object
        $user = $serializer->deserialize($json, User::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => new User(),
        ]);

// Validation datas received
        $errors = $validator->validate($user);

// Validation Email Adress
        $email = $user->getEmail();
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

// Hashing password        
        $TextPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $TextPassword
        );

        $user->setPassword($hashedPassword);

// Message if there is an erreur        
        if (count($errors) > 0) {
            $data = [
                'success' => false,
                'errors' => (string) $errors,
            ];

            return $this->json($data, Response::HTTP_BAD_REQUEST);
        }

// Set initial user role
        $user->setRoles(['ROLE_USER']);

      
// Persister the data of form
        $em->persist($user);
        $em->flush();

// Return a JSON response
        return $this->json($user, Response::HTTP_OK);
    }
    }
