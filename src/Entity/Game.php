<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['game_browse'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['game_browse', 'category_browse','rent_browse'])]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    
    #[Groups(['game_browse', 'category_browse', 'rent_browse'])]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['game_browse', 'category_browse','rent_browse'])]
    private ?string $Price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['game_browse', 'category_browse'])]
    private ?string $Status = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['game_browse', 'category_browse','rent_browse'])]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups(['game_browse'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: "game", targetEntity: ContentRent::class, orphanRemoval: true)]
    private Collection $contentRents;

    public function __construct()
    {
        $this->contentRents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(string $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function addContentRent(ContentRent $contentRent): self
    {
        if (!$this->contentRents->contains($contentRent)) {
            $this->contentRents[] = $contentRent;
            $contentRent->setGame($this);
        }

        return $this;
    }

    public function removeContentRent(ContentRent $contentRent): self
    {
        if ($this->contentRents->removeElement($contentRent)) {
            // set the owning side to null (unless already changed)
            if ($contentRent->getGame() === $this) {
                $contentRent->setGame(null);
            }
        }

        return $this;
    }

    public function getContentRents(): Collection
    {
        return $this->contentRents;
    }
}
