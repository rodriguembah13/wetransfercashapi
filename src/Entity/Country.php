<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="countries")
     */
    private $zone;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flag;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $monaire;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMonaire()
    {
        return $this->monaire;
    }

    /**
     * @param mixed $monaire
     */
    public function setMonaire($monaire): void
    {
        $this->monaire = $monaire;
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag): void
    {
        $this->flag = $flag;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }

}
