<?php

    namespace App\Entity;

    use App\Repository\EventRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: EventRepository::class)]
    class Event
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column()]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $name = null;

        #[ORM\Column]
        private ?\DateTime $startsAt = null;

        #[ORM\Column]
        private ?int $duration = null;

        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $cutOffDate = null;

        #[ORM\Column]
        private ?int $capacity = null;

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $eventInfo = null;

        #[ORM\Column(length: 50)]
        private ?string $status = null;

        #[ORM\ManyToOne(inversedBy: 'organizerSite')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Campus $campus = null;

        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private ?Location $Location = null;

        #[ORM\ManyToOne(inversedBy: 'eventsOrganised')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Participant $organiser = null;

        #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'eventsAttending')]
        private Collection $eventAttendence;

        #[ORM\OneToOne(cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: true)]
        private ?Reason $reason = null;

        public function __construct()
        {
            $this->eventAttendence = new ArrayCollection();
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

        public function getStartsAt(): ?\DateTime
        {
            return $this->startsAt;
        }

        public function setStartsAt(\DateTime $startsAt): self
        {
            $this->startsAt = $startsAt;

            return $this;
        }

        public function getDuration(): ?int
        {
            return $this->duration;
        }

        public function setDuration(int $duration): self
        {
            $this->duration = $duration;

            return $this;
        }

        public function getCutOffDate(): ?\DateTimeInterface
        {
            return $this->cutOffDate;
        }

        public function setCutOffDate(\DateTimeInterface $cutOffDate): self
        {
            $this->cutOffDate = $cutOffDate;

            return $this;
        }

        public function getCapacity(): ?int
        {
            return $this->capacity;
        }

        public function setCapacity(int $capacity): self
        {
            $this->capacity = $capacity;

            return $this;
        }

        public function getEventInfo(): ?string
        {
            return $this->eventInfo;
        }

        public function setEventInfo(?string $eventInfo): self
        {
            $this->eventInfo = $eventInfo;

            return $this;
        }

        public function getStatus(): ?string
        {
            return $this->status;
        }

        public function setStatus(string $status): self
        {
            $this->status = $status;

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

        public function getLocation(): ?Location
        {
            return $this->Location;
        }

        public function setLocation(?Location $Location): self
        {
            $this->Location = $Location;

            return $this;
        }

        public function getOrganiser(): ?Participant
        {
            return $this->organiser;
        }

        public function setOrganiser(?Participant $organiser): self
        {
            $this->organiser = $organiser;

            return $this;
        }

        /**
         * @return Collection<int, Participant>
         */
        public function getEventAttendence(): Collection
        {
            return $this->eventAttendence;
        }

        public function addEventAttendence(Participant $eventAttendence): self
        {
            if (!$this->eventAttendence->contains($eventAttendence)) {
                $this->eventAttendence[] = $eventAttendence;
            }

            return $this;
        }

        public function removeEventAttendence(Participant $eventAttendence): self
        {
            $this->eventAttendence->removeElement($eventAttendence);

            return $this;
        }

        public function getReason(): ?Reason
        {
            return $this->reason;
        }

        public function setReason(Reason $reason): self
        {
            $this->reason = $reason;

            return $this;
        }
    }
