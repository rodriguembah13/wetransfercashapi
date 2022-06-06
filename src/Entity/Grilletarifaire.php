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
     * @ORM\Column(type="float", length=255, nullable=true)
     */
    private $trancheA;

    /**
     * @ORM\Column(type="float", length=255, nullable=true)
     */
    private $trancheB;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $frais;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class)
     */
    private $zone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrancheA(): ?float
    {
        return $this->trancheA;
    }

    public function setTrancheA(?float $trancheA): self
    {
        $this->trancheA = $trancheA;

        return $this;
    }

    public function getTrancheB(): ?float
    {
        return $this->trancheB;
    }

    public function setTrancheB(?float $trancheB): self
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

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone): void
    {
        $this->zone = $zone;
    }


}
