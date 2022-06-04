<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="transactions")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Contactcustomer::class)
     */
    private $beneficiare;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modetransfert;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $branche;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeservice;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prestateurservice;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant): void
    {
        $this->montant = $montant;
    }

    /**
     * @return mixed
     */
    public function getModetransfert()
    {
        return $this->modetransfert;
    }

    /**
     * @param mixed $modetransfert
     */
    public function setModetransfert($modetransfert): void
    {
        $this->modetransfert = $modetransfert;
    }

    /**
     * @return mixed
     */
    public function getBranche()
    {
        return $this->branche;
    }

    /**
     * @param mixed $branche
     */
    public function setBranche($branche): void
    {
        $this->branche = $branche;
    }

    /**
     * @return mixed
     */
    public function getTypeservice()
    {
        return $this->typeservice;
    }

    /**
     * @param mixed $typeservice
     */
    public function setTypeservice($typeservice): void
    {
        $this->typeservice = $typeservice;
    }

    /**
     * @return mixed
     */
    public function getPrestateurservice()
    {
        return $this->prestateurservice;
    }

    /**
     * @param mixed $prestateurservice
     */
    public function setPrestateurservice($prestateurservice): void
    {
        $this->prestateurservice = $prestateurservice;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getBeneficiare(): ?Contactcustomer
    {
        return $this->beneficiare;
    }

    public function setBeneficiare(?Contactcustomer $beneficiare): self
    {
        $this->beneficiare = $beneficiare;

        return $this;
    }
}
