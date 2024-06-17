<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["person_read", "place_read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["person_read","place_read"])]
    private ?string $firstname = null;

    /**
     * @var Collection<int, Place>
     */
    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'likedBy')]
    #[Groups(["person_read"])]
    private Collection $placesLiked;

    public function __construct()
    {
        $this->placesLiked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlacesLiked(): Collection
    {
        return $this->placesLiked;
    }

    public function addPlacesLiked(Place $placesLiked): static
    {
        if (!$this->placesLiked->contains($placesLiked)) {
            $this->placesLiked->add($placesLiked);
        }

        return $this;
    }

    public function removePlacesLiked(Place $placesLiked): static
    {
        $this->placesLiked->removeElement($placesLiked);

        return $this;
    }
}
