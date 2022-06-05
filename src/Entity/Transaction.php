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
     * @ORM\Column(type="float", nullable=true)
     */
    private $fraisenvoi;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montanttotal;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetransaction;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $raisontransaction;
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
     * @ORM\ManyToOne(targetEntity=Country::class")
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numerotransaction;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typetransaction;
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
    public function getTypetransaction()
    {
        return $this->typetransaction;
    }

    /**
     * @param mixed $typetransaction
     */
    public function setTypetransaction($typetransaction): void
    {
        $this->typetransaction = $typetransaction;
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
    public function getNumerotransaction()
    {
        return $this->numerotransaction;
    }

    /**
     * @param mixed $numerotransaction
     */
    public function setNumerotransaction($numerotransaction): void
    {
        $this->numerotransaction = $numerotransaction;
    }

    /**
     * @return mixed
     */
    public function getFraisenvoi()
    {
        return $this->fraisenvoi;
    }

    /**
     * @param mixed $fraisenvoi
     */
    public function setFraisenvoi($fraisenvoi): void
    {
        $this->fraisenvoi = $fraisenvoi;
    }

    /**
     * @return mixed
     */
    public function getMontanttotal()
    {
        return $this->montanttotal;
    }

    /**
     * @param mixed $montanttotal
     */
    public function setMontanttotal($montanttotal): void
    {
        $this->montanttotal = $montanttotal;
    }

    /**
     * @return mixed
     */
    public function getDatetransaction()
    {
        return $this->datetransaction;
    }

    /**
     * @param mixed $datetransaction
     */
    public function setDatetransaction($datetransaction): void
    {
        $this->datetransaction = $datetransaction;
    }

    /**
     * @return mixed
     */
    public function getRaisontransaction()
    {
        return $this->raisontransaction;
    }

    /**
     * @param mixed $raisontransaction
     */
    public function setRaisontransaction($raisontransaction): void
    {
        $this->raisontransaction = $raisontransaction;
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
