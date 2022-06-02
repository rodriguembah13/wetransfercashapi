<?php

namespace App\Entity;

use App\Repository\GrilletarifaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GrilletarifaireRepository::class)
 */
class Grilletarifaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trancheA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trancheB;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $frais;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrancheA(): ?string
    {
        return $this->trancheA;
    }

    public function setTrancheA(?string $trancheA): self
    {
        $this->trancheA = $trancheA;

        return $this;
    }

    public function getTrancheB(): ?string
    {
        return $this->trancheB;
    }

    public function setTrancheB(?string $trancheB): self
    {
        $this->trancheB = $trancheB;

        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(?float $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(?string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}
