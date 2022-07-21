<?php

namespace App\Form\model;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Participant;
use Doctrine\Common\Collections\Collection;

class EventFormModel
{
    private ?string $name;

    private ?\DateTimeImmutable $startsAt;

    private ?int $duration;

    private ?\DateTimeInterface $cutOffDate;

    private ?int $capacity;

    private ?string $eventInfo;

    private ?string $status;

    private ?Campus $campus;

    private ?City $city;

    private ?string $place;

    private ?float $latitude;

    private ?float $longitude;


}