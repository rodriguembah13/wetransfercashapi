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
    private $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dwollaid;
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
    private $bankid;
    public function getId(): ?int
    {
        return $this->id;
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
    public function getDwollaid()
    {
        return $this->dwollaid;
    }

    /**
     * @param mixed $dwollaid
     */
    public function setDwollaid($dwollaid): void
    {
        $this->dwollaid = $dwollaid;
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

}
