<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
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
    private $bussinesname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationalite;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motif;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeidentification;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeropiece;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isverify=false;
    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="customer")
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity=Contactcustomer::class, mappedBy="customer")
     */
    private $contactcustomers;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="customer", cascade={"persist", "remove"})
     */
    private $compte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class)
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileimagename;
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->contactcustomers = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * @param mixed $nationalite
     */
    public function setNationalite($nationalite): void
    {
        $this->nationalite = $nationalite;
    }

    /**
     * @return mixed
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param mixed $motif
     */
    public function setMotif($motif): void
    {
        $this->motif = $motif;
    }

    /**
     * @return mixed
     */
    public function getTypeidentification()
    {
        return $this->typeidentification;
    }

    /**
     * @param mixed $typeidentification
     */
    public function setTypeidentification($typeidentification): void
    {
        $this->typeidentification = $typeidentification;
    }

    /**
     * @return mixed
     */
    public function getNumeropiece()
    {
        return $this->numeropiece;
    }

    /**
     * @param mixed $numeropiece
     */
    public function setNumeropiece($numeropiece): void
    {
        $this->numeropiece = $numeropiece;
    }

    /**
     * @return bool
     */
    public function isIsverify(): bool
    {
        return $this->isverify;
    }

    /**
     * @param bool $isverify
     */
    public function setIsverify(bool $isverify): void
    {
        $this->isverify = $isverify;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCustomer($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCustomer() === $this) {
                $transaction->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contactcustomer>
     */
    public function getContactcustomers(): Collection
    {
        return $this->contactcustomers;
    }

    public function addContactcustomer(Contactcustomer $contactcustomer): self
    {
        if (!$this->contactcustomers->contains($contactcustomer)) {
            $this->contactcustomers[] = $contactcustomer;
            $contactcustomer->setCustomer($this);
        }

        return $this;
    }

    public function removeContactcustomer(Contactcustomer $contactcustomer): self
    {
        if ($this->contactcustomers->removeElement($contactcustomer)) {
            // set the owning side to null (unless already changed)
            if ($contactcustomer->getCustomer() === $this) {
                $contactcustomer->setCustomer(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?User
    {
        return $this->compte;
    }

    public function setCompte(?User $compte): self
    {
        $this->compte = $compte;

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


}
