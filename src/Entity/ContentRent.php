<?php

namespace App\Entity;

use App\Repository\ContentRentRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentRentRepository::class)]
class ContentRent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Rent::class, inversedBy: "contentRents")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rent $rent = null;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: "contentRents")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rent_browse'])]
    private ?Game $game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRent(): ?Rent
    {
        return $this->rent;
    }

    public function setRent(?Rent $rent): self
    {
        $this->rent = $rent;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
