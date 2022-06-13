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
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infobipAppId;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $infobipMessageId;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getInfobipAppId()
    {
        return $this->infobipAppId;
    }

    /**
     * @param mixed $infobipAppId
     */
    public function setInfobipAppId($infobipAppId): void
    {
        $this->infobipAppId = $infobipAppId;
    }

    /**
     * @return mixed
     */
    public function getInfobipMessageId()
    {
        return $this->infobipMessageId;
    }

    /**
     * @param mixed $infobipMessageId
     */
    public function setInfobipMessageId($infobipMessageId): void
    {
        $this->infobipMessageId = $infobipMessageId;
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
