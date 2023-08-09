<?php

namespace App\Entity;

use DateTimeInterface;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $Num;

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $IP;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date_Res;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date_Loc;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_Retour;

    /**
     * @ORM\Column(type="float")
     */
    private $Montant;

    /**
     * @ORM\Column(type="float")
     */
    private $Avance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Status;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="Locations", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicule::class, inversedBy="Locations" )
     * @ORM\JoinColumn(nullable=false)
     */
    private $Vehicule;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="Location", orphanRemoval=true)
     */
    private $payments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBabySeat;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReservoire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPersonalDriver;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSecondDriver;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $SecondDriverCIN;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $SecondDriverPermis;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $SecondDriverDN;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $SecondDriverDateCIN;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $SecondDriverDatePermis;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSTW;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Agence_Depart;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="locations_arrive")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Agence_Arrive;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?string
    {
        return $this->Num;
    }

    public function setNum(string $Num): self
    {
        $this->Num = $Num;

        return $this;
    }

    public function getIP(): ?string
    {
        return $this->IP;
    }

    public function setIP(string $IP): self
    {
        $this->IP = $IP;

        return $this;
    }

    public function getDate_Res(): ?string
    {
        $newDate = $this->Date_Res->format('d/m/Y');
        return $newDate;
    }

    public function getDateRes(): ?DateTimeInterface
    {
        return $this->Date_Res;
    }
    

    public function setDateRes(DateTimeInterface $dateTime): self
    {
        $this->Date_Res = $dateTime;

        return $this;
    }

    public function getDate_Loc(): ?string
    {
        $newDate = $this->Date_Loc->format('m/d/Y');

        return $newDate;
    }

    public function getDateLoc(): ?DateTimeInterface
    {
        return $this->Date_Loc;
    }

    public function setDateLoc(DateTimeInterface $dateTime): self
    {
        $this->Date_Loc = $dateTime;

        return $this;
    }

    public function getDate_Retour(): ?string
    {
        $newDate = $this->Date_Retour->format('m/d/Y');

        return $newDate;
    }

    public function getDateRetour(): ?DateTimeInterface
    {
        return $this->Date_Retour;
    }

    public function setDateRetour(DateTimeInterface $dateTime): self
    {
        $this->Date_Retour = $dateTime;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getAvance(): ?float
    {
        return $this->Avance;
    }

    public function setAvance(float $Avance): self
    {
        $this->Avance = $Avance;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->Vehicule;
    }

    public function setVehicule(?Vehicule $Vehicule): self
    {
        $this->Vehicule = $Vehicule;

        return $this;
    }

    public function __toString() : string {
        return $this->id;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setLocation($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getLocation() === $this) {
                $payment->setLocation(null);
            }
        }

        return $this;
    }

    public function isIsBabySeat(): ?bool
    {
        return $this->isBabySeat;
    }

    public function setIsBabySeat(bool $isBabySeat): self
    {
        $this->isBabySeat = $isBabySeat;

        return $this;
    }

    public function isIsPersonalDriver(): ?bool
    {
        return $this->isPersonalDriver;
    }

    public function setIsPersonalDriver(bool $isPersonalDriver): self
    {
        $this->isPersonalDriver = $isPersonalDriver;

        return $this;
    }

    public function isIsSecondDriver(): ?bool
    {
        return $this->isSecondDriver;
    }

    public function setIsSecondDriver(bool $isSecondDriver): self
    {
        $this->isSecondDriver = $isSecondDriver;

        return $this;
    }

    public function isIsSTW(): ?bool
    {
        return $this->isSTW;
    }

    public function setIsSTW(bool $isSTW): self
    {
        $this->isSTW = $isSTW;

        return $this;
    }

    public function getIsReservoire(): ?bool
    {
        return $this->isReservoire;
    }

    public function setIsReservoire(bool $isReservoire): self
    {
        $this->isReservoire = $isReservoire;

        return $this;
    }

    public function getAgenceDepart(): ?Agence
    {
        return $this->Agence_Depart;
    }

    public function setAgenceDepart(?Agence $Agence_Depart): self
    {
        $this->Agence_Depart = $Agence_Depart;

        return $this;
    }

    public function getAgenceArrive(): ?Agence
    {
        return $this->Agence_Arrive;
    }

    public function setAgenceArrive(?Agence $Agence_Arrive): self
    {
        $this->Agence_Arrive = $Agence_Arrive;

        return $this;
    }

    public function getSecondDriverCIN(): ?string
    {
        return $this->SecondDriverCIN;
    }
    public function setSecondDriverCIN(?string $CIN): self
    {
        $this->SecondDriverCIN = $CIN;

        return $this;
    }

    public function getSecondDriverPermis(): ?string
    {
        return $this->SecondDriverPermis;
    }
    public function setSecondDriverPermis(?string $Permis): self
    {
        $this->SecondDriverPermis = $Permis;
        
        return $this;
    }

    public function getSecondDriverDN(): ?DateTimeInterface
    {
        return $this->SecondDriverDN;
    }

    public function setSecondDriverDN(?DateTimeInterface $dateTime): self
    {
        $this->SecondDriverDN = $dateTime;

        return $this;
    }

    public function getSecondDriverDateCIN(): ?DateTimeInterface
    {
        return $this->SecondDriverDateCIN;
    }

    public function setSecondDriverDateCIN(?DateTimeInterface $dateTime): self
    {
        $this->SecondDriverDateCIN = $dateTime;

        return $this;
    }
    public function getSecondDriverDatePermis(): ?DateTimeInterface
    {
        return $this->SecondDriverDatePermis;
    }

    public function setSecondDriverDatePermis(?DateTimeInterface $dateTime): self
    {
        $this->SecondDriverDatePermis = $dateTime;

        return $this;
    }
}
