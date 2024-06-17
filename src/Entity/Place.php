<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["person_read", "place_read"])]
    private ?string $name = null;

    /**
     * @var Collection<int, Person>
     */
    #[ORM\ManyToMany(targetEntity: Person::class, mappedBy: 'placesLiked')]
    #[Groups(["place_read"])]

    private Collection $likedBy;

    public function __construct()
    {
        $this->likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    public function addLikedBy(Person $likedBy): static
    {
        if (!$this->likedBy->contains($likedBy)) {
            $this->likedBy->add($likedBy);
            $likedBy->addPlacesLiked($this);
        }

        return $this;
    }

    public function removeLikedBy(Person $likedBy): static
    {
        if ($this->likedBy->removeElement($likedBy)) {
            $likedBy->removePlacesLiked($this);
        }

        return $this;
    }
}
