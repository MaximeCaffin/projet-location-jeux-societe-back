<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentRepository::class)]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['rent_browse'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: "datetime")]
    #[Groups(['rent_browse'])]
    private $date_debut;

    #[ORM\Column(type: "datetime")]
    #[Groups(['rent_browse'])]
    private $date_fin;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['rent_browse'])]
    private $Status;

    #[ORM\OneToMany(mappedBy: "rent", targetEntity: ContentRent::class, orphanRemoval: true)]
    #[Groups(['rent_browse'])]
    private Collection $contentRents;

    public function __construct()
    {
        $this->contentRents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

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

    public function addContentRent(ContentRent $contentRent): self
    {
        if (!$this->contentRents->contains($contentRent)) {
            $this->contentRents[] = $contentRent;
            $contentRent->setRent($this);
        }

        return $this;
    }

    public function removeContentRent(ContentRent $contentRent): self
    {
        if ($this->contentRents->removeElement($contentRent)) {
            // set the owning side to null (unless already changed)
            if ($contentRent->getRent() === $this) {
                $contentRent->setRent(null);
            }
        }

        return $this;
    }

    public function getContentRents(): Collection
    {
        return $this->contentRents;
    }
}
