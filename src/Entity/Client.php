<?php

namespace App\Entity;

use DateTimeInterface;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;


/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Add1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Add2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Permis;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_Permis;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CIN;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Date_CIN;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_Naissance;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="Client", fetch="LAZY")
     */
    private $Locations;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="Client", fetch="LAZY", orphanRemoval=true)
     */
    private $payments;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="Client", fetch="LAZY")
     */
    private $feedback;


    public function __construct()
    {
        $this->Locations = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(?string $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): self
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    public function getAdd1(): ?string
    {
        return $this->Add1;
    }

    public function setAdd1(string $Add1): self
    {
        $this->Add1 = $Add1;

        return $this;
    }

    public function getAdd2(): ?string
    {
        return $this->Add2;
    }

    public function setAdd2(string $Add2): self
    {
        $this->Add2 = $Add2;

        return $this;
    }

    public function getPermis(): ?string
    {
        return $this->Permis;
    }

    public function setPermis(string $Permis): self
    {
        $this->Permis = $Permis;

        return $this;
    }

    public function getDate_Permis(): ?string
    {
        $newDate = $this->Date_Permis->format('d/m/Y');
        return $newDate;    
    }

    public function getDatePermis(): ?DateTimeInterface
    {
        return $this->Date_Permis;
    }

    public function setDatePermis(DateTimeInterface $dateTime): self
    {
        $this->Date_Permis = $dateTime;

        return $this;
    }

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function getDate_CIN(): ?string
    {
        $newDate = $this->Date_CIN->format('d/m/Y');
        return $newDate;    
    }

    public function getDateCIN(): ?DateTimeInterface
    {
        return $this->Date_CIN;
    }

    public function setDateCIN(?DateTimeInterface $dateTime): self
    {
        $this->Date_CIN = $dateTime;

        return $this;
    }

    public function getDate_Naissance(): ?string
    {
        $newDate = $this->Date_Naissance->format('d/m/Y');
        return $newDate;    
    }

    public function getDateNaissance(): ?DateTimeInterface
    {
        return $this->Date_Naissance;
    }

    public function setDateNaissance(DateTimeInterface $dateTime): self
    {
        $this->Date_Naissance = $dateTime;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->Locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->Locations->contains($location)) {
            $this->Locations[] = $location;
            $location->setClient($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->Locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getClient() === $this) {
                $location->setClient(null);
            }
        }

        return $this;
    }

    // public function getUser(): ?User
    // {
    //     return $this->User;
    // }

    // public function setUser(User $User): self
    // {
    //     $this->User = $User;

    //     return $this;
    // }

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
            $payment->setClient($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getClient() === $this) {
                $payment->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback[] = $feedback;
            $feedback->setClient($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getClient() === $this) {
                $feedback->setClient(null);
            }
        }

        return $this;
    }
}
