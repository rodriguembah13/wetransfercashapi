<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationRepository::class)
 */
class Configuration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxplatform;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTauxplatform(): ?float
    {
        return $this->tauxplatform;
    }

    public function setTauxplatform(?float $tauxplatform): self
    {
        $this->tauxplatform = $tauxplatform;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
