<?php

namespace App\Entity;

use App\Repository\ContactcustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactcustomerRepository::class)
 */
class Contactcustomer
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
    private $firstname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bussinesname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankroutingnumber;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankaccountnumber;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $banktype;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankiban;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankifsccode;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankswiftcode;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankrelaction;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $banknationalite;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankbranchnumber;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $banksignature;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankadressephysique;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankid;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="contactcustomers")
     */
    private $customer;
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getBankiban()
    {
        return $this->bankiban;
    }

    /**
     * @param mixed $bankiban
     */
    public function setBankiban($bankiban): void
    {
        $this->bankiban = $bankiban;
    }

    /**
     * @return mixed
     */
    public function getBankifsccode()
    {
        return $this->bankifsccode;
    }

    /**
     * @param mixed $bankifsccode
     */
    public function setBankifsccode($bankifsccode): void
    {
        $this->bankifsccode = $bankifsccode;
    }

    /**
     * @return mixed
     */
    public function getBankswiftcode()
    {
        return $this->bankswiftcode;
    }

    /**
     * @param mixed $bankswiftcode
     */
    public function setBankswiftcode($bankswiftcode): void
    {
        $this->bankswiftcode = $bankswiftcode;
    }

    /**
     * @return mixed
     */
    public function getBankrelaction()
    {
        return $this->bankrelaction;
    }

    /**
     * @param mixed $bankrelaction
     */
    public function setBankrelaction($bankrelaction): void
    {
        $this->bankrelaction = $bankrelaction;
    }

    /**
     * @return mixed
     */
    public function getBanknationalite()
    {
        return $this->banknationalite;
    }

    /**
     * @param mixed $banknationalite
     */
    public function setBanknationalite($banknationalite): void
    {
        $this->banknationalite = $banknationalite;
    }

    /**
     * @return mixed
     */
    public function getBankbranchnumber()
    {
        return $this->bankbranchnumber;
    }

    /**
     * @param mixed $bankbranchnumber
     */
    public function setBankbranchnumber($bankbranchnumber): void
    {
        $this->bankbranchnumber = $bankbranchnumber;
    }

    /**
     * @return mixed
     */
    public function getBanksignature()
    {
        return $this->banksignature;
    }

    /**
     * @param mixed $banksignature
     */
    public function setBanksignature($banksignature): void
    {
        $this->banksignature = $banksignature;
    }

    /**
     * @return mixed
     */
    public function getBankadressephysique()
    {
        return $this->bankadressephysique;
    }

    /**
     * @param mixed $bankadressephysique
     */
    public function setBankadressephysique($bankadressephysique): void
    {
        $this->bankadressephysique = $bankadressephysique;
    }

    /**
     * @return mixed
     */
    public function getBankid()
    {
        return $this->bankid;
    }

    /**
     * @param mixed $bankid
     */
    public function setBankid($bankid): void
    {
        $this->bankid = $bankid;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBussinesname()
    {
        return $this->bussinesname;
    }

    /**
     * @param mixed $bussinesname
     */
    public function setBussinesname($bussinesname): void
    {
        $this->bussinesname = $bussinesname;
    }

    /**
     * @return mixed
     */
    public function getBankname()
    {
        return $this->bankname;
    }

    /**
     * @param mixed $bankname
     */
    public function setBankname($bankname): void
    {
        $this->bankname = $bankname;
    }

    /**
     * @return mixed
     */
    public function getBankroutingnumber()
    {
        return $this->bankroutingnumber;
    }

    /**
     * @param mixed $bankroutingnumber
     */
    public function setBankroutingnumber($bankroutingnumber): void
    {
        $this->bankroutingnumber = $bankroutingnumber;
    }

    /**
     * @return mixed
     */
    public function getBankaccountnumber()
    {
        return $this->bankaccountnumber;
    }

    /**
     * @param mixed $bankaccountnumber
     */
    public function setBankaccountnumber($bankaccountnumber): void
    {
        $this->bankaccountnumber = $bankaccountnumber;
    }

    /**
     * @return mixed
     */
    public function getBanktype()
    {
        return $this->banktype;
    }

    /**
     * @param mixed $banktype
     */
    public function setBanktype($banktype): void
    {
        $this->banktype = $banktype;
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

}
