<?php

namespace App\Entity;

use App\Repository\ReasonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReasonRepository::class)]
class Reason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $cancelReason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }
}
