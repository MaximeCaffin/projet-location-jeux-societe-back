<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $Firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Company = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private ?string $mail = null;


    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2)]
    private ?string $message = null;

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): static
    {
        $this->Firstname = $Firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->Company;
    }

    public function setCompany(?string $Company): static
    {
        $this->Company = $Company;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

}
