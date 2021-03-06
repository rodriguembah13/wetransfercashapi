<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    public const ENVALIDATION="en validation";
    public const VALIDATION="validation";
    public const EFFECTUE="effectue";

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
     * @ORM\ManyToOne(targetEntity=Country::class)
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numerotransaction;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroidentifiant;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typetransaction;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prestateurservice;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status=self::ENVALIDATION;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileimagename;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isdelete=false;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     */
    private $agent;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNumeroidentifiant()
    {
        return $this->numeroidentifiant;
    }

    /**
     * @param mixed $numeroidentifiant
     */
    public function setNumeroidentifiant($numeroidentifiant): void
    {
        $this->numeroidentifiant = $numeroidentifiant;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
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


    public function getCountry(): ?Country
    {
        return $this->country;
    }


    public function setCountry(?Country $country): self
    {
        $this->country = $country;
        return $this;
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

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileimagename()
    {
        return $this->fileimagename;
    }

    /**
     * @param mixed $fileimagename
     */
    public function setFileimagename($fileimagename): void
    {
        $this->fileimagename = $fileimagename;
    }

    /**
     * @return mixed
     */
    public function getIsdelete()
    {
        return $this->isdelete;
    }

    /**
     * @param mixed $isdelete
     */
    public function setIsdelete($isdelete): void
    {
        $this->isdelete = $isdelete;
    }

}
