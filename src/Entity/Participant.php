<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $surname = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 80)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\OneToMany(mappedBy: 'organiser', targetEntity: Event::class)]
    private Collection $eventsOrganised;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'eventAttendence')]
    private Collection $eventsAttending;

    public function __construct()
    {
        $this->eventsOrganised = new ArrayCollection();
        $this->eventsAttending = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsOrganised(): Collection
    {
        return $this->eventsOrganised;
    }

    public function addEventsOrganised(Event $eventsOrganised): self
    {
        if (!$this->eventsOrganised->contains($eventsOrganised)) {
            $this->eventsOrganised[] = $eventsOrganised;
            $eventsOrganised->setOrganiser($this);
        }

        return $this;
    }

    public function removeEventsOrganised(Event $eventsOrganised): self
    {
        if ($this->eventsOrganised->removeElement($eventsOrganised)) {
            // set the owning side to null (unless already changed)
            if ($eventsOrganised->getOrganiser() === $this) {
                $eventsOrganised->setOrganiser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventsAttending(): Collection
    {
        return $this->eventsAttending;
    }

    public function addEventsAttending(Event $eventsAttending): self
    {
        if (!$this->eventsAttending->contains($eventsAttending)) {
            $this->eventsAttending[] = $eventsAttending;
            $eventsAttending->addEventAttendence($this);
        }

        return $this;
    }

    public function removeEventsAttending(Event $eventsAttending): self
    {
        if ($this->eventsAttending->removeElement($eventsAttending)) {
            $eventsAttending->removeEventAttendence($this);
        }

        return $this;
    }
}
