<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: Event::class)]
    private Collection $organizerSite;


    public function __construct()
    {
        $this->organizerSite = new ArrayCollection();
    }

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
    /**
     * @return Collection<int, Event>
     */
    public function getOrganizerSite(): Collection
    {
        return $this->organizerSite;
    }

//    public function addOrganizerSite(Event $organizerSite): self
//    {
//        if (!$this->organizerSite->contains($organizerSite)) {
//            $this->organizerSite[] = $organizerSite;
//            $organizerSite->setCampus($this);
//        }
//
//        return $this;
//    }

}

