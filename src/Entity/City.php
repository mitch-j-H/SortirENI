<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    private ?string $postcode = null;

    #[ORM\OneToMany( mappedBy: "city", targetEntity: Location::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collection $locations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations():Collection {
        return $this->locations;
    }

    public function addLocations(Location$location): self {
        if(!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setCity($this);
        }
        return $this;
    }

    public function removeStudents(Location$location) {
        if($this->locations->contains($location)) {
            $this->locations->removeElement($location);

            if($location->getCity() === $this) {
                $location->setCity(null);
            }
            return $this;
        }
    }
}
