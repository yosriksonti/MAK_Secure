<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=VehiculeRepository::class)
 * @Vich\Uploadable
 */
class Vehicule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Modele;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Boite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Carb;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nb_Places;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nb_Portes;

    /**
     * @ORM\Column(type="integer")
     */
    private $Nb_Val;

    /**
     * @ORM\Column(type="float")
     */
    private $Caut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Clim;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="text")
     */
    private $Description_Det;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Photo_Def;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Photo_Reel;

    /**
     *
     * @Vich\UploadableField(mapping="Vehicule_image", fileNameProperty="Photo_Reel")
     * 
     * @var File|null
     */
    private ?File $Reel = null;

    /**
     *
     * @Vich\UploadableField(mapping="Vehicule_image", fileNameProperty="Photo_Def")
     * 
     * @var File|null
     */
    private ?File $Def = null;

    /**
     *
     * @Vich\UploadableField(mapping="Vehicule_image", fileNameProperty="Photo_Saison")
     * 
     * @var File|null
     */
    private ?File $Saison = null;

    /**
     *
     * @Vich\UploadableField(mapping="Vehicule_image", fileNameProperty="CarteGrise")
     * 
     * @var File|null
     */
    private ?File $Grise = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Photo_Saison;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="Vehicule", orphanRemoval=true)
     */
    private $Locations;

    /**
     * @ORM\ManyToOne(targetEntity=Park::class, inversedBy="Vehicules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Park;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUnlimitedMileage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCarInsurance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPassengerInsurance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVAT;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Dispo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $CarteGrise;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\Column(type="float")
     */
    private $PrixHS;

    /**
     * @ORM\Column(type="float")
     */
    private $Reservoire;

    /**
     * @ORM\OneToMany(targetEntity=Depence::class, mappedBy="Vehicule", orphanRemoval=true)
     */
    private $depences;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="Vehicule", orphanRemoval=true)
     */
    private $feedback;


    public function __construct()
    {
        $this->Locations = new ArrayCollection();
        $this->feedback = new ArrayCollection();
        $this->depences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->Marque;
    }

    public function setMarque(string $Marque): self
    {
        $this->Marque = $Marque;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->Modele;
    }

    public function setModele(string $Modele): self
    {
        $this->Modele = $Modele;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getBoite(): ?string
    {
        return $this->Boite;
    }

    public function setBoite(string $Boite): self
    {
        $this->Boite = $Boite;

        return $this;
    }

    public function getCarb(): ?string
    {
        return $this->Carb;
    }

    public function setCarb(string $Carb): self
    {
        $this->Carb = $Carb;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->Nb_Places;
    }

    public function setNbPlaces(int $Nb_Places): self
    {
        $this->Nb_Places = $Nb_Places;

        return $this;
    }

    public function getNbPortes(): ?int
    {
        return $this->Nb_Portes;
    }

    public function setNbPortes(int $Nb_Portes): self
    {
        $this->Nb_Portes = $Nb_Portes;

        return $this;
    }

    public function getNbVal(): ?int
    {
        return $this->Nb_Val;
    }

    public function setNbVal(int $Nb_Val): self
    {
        $this->Nb_Val = $Nb_Val;

        return $this;
    }

    public function getCaut(): ?float
    {
        return $this->Caut;
    }

    public function setCaut(float $Caut): self
    {
        $this->Caut = $Caut;

        return $this;
    }

    public function isClim(): ?bool
    {
        return $this->Clim;
    }

    public function setClim(bool $Clim): self
    {
        $this->Clim = $Clim;

        return $this;
    }

    public function isDispo(): ?bool
    {
        return $this->Dispo;
    }

    public function setDispo(bool $Dispo): self
    {
        $this->Dispo = $Dispo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDescriptionDet(): ?string
    {
        return $this->Description_Det;
    }

    public function setDescriptionDet(string $Description_Det): self
    {
        $this->Description_Det = $Description_Det;

        return $this;
    }

    public function getPhotoDef(): ?string
    {
        return $this->Photo_Def;
    }

    public function setPhotoDef(?string $Photo_Def): self
    {
        $this->Photo_Def = $Photo_Def;

        return $this;
    }

    public function getReel(): ?File
    {
        return $this->Reel;
    }

    public function setReel(?File $reel): void
    {
        $this->Reel = $reel;

        if ($reel instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getGrise(): ?File
    {
        return $this->Grise;
    }

    public function setGrise(?File $grise): void
    {
        $this->Grise = $grise;

        if ($grise instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getDef(): ?File
    {
        return $this->Def;
    }

    public function setDef(?File $def): void
    {
        $this->Def = $def;

        if ($def instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getSaison(): ?File
    {
        return $this->Saison;
    }

    public function setSaison(?File $saison): void
    {
        $this->Saison = $saison;

        if ($saison instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPhotoReel(): ?string
    {
        return $this->Photo_Reel;
    }

    public function setPhotoReel(?string $Photo_Reel): self
    {
        $this->Photo_Reel = $Photo_Reel;

        return $this;
    }

    public function getPhotoSaison(): ?string
    {
        return $this->Photo_Saison;
    }

    public function setPhotoSaison(?string $Photo_Saison): self
    {
        $this->Photo_Saison = $Photo_Saison;

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
            $location->setVehicule($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->Locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getVehicule() === $this) {
                $location->setVehicule(null);
            }
        }

        return $this;
    }

    public function getPark(): ?Park
    {
        return $this->Park;
    }

    public function setPark(?Park $Park): self
    {
        $this->Park = $Park;

        return $this;
    }
    public function __toString() : string {
        return $this->id;
    }

    public function isIsUnlimitedMileage(): ?bool
    {
        return $this->isUnlimitedMileage;
    }

    public function setIsUnlimitedMileage(bool $isUnlimitedMileage): self
    {
        $this->isUnlimitedMileage = $isUnlimitedMileage;

        return $this;
    }

    public function isIsCarInsurance(): ?bool
    {
        return $this->isCarInsurance;
    }

    public function setIsCarInsurance(bool $isCarInsurance): self
    {
        $this->isCarInsurance = $isCarInsurance;

        return $this;
    }

    public function isIsPassengerInsurance(): ?bool
    {
        return $this->isPassengerInsurance;
    }

    public function setIsPassengerInsurance(bool $isPassengerInsurance): self
    {
        $this->isPassengerInsurance = $isPassengerInsurance;

        return $this;
    }

    public function isIsVAT(): ?bool
    {
        return $this->isVAT;
    }

    public function setIsVAT(bool $isVAT): self
    {
        $this->isVAT = $isVAT;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->Matricule;
    }

    public function setMatricule(string $Matricule): self
    {
        $this->Matricule = $Matricule;

        return $this;
    }

    public function getCarteGrise(): ?string
    {
        return $this->CarteGrise;
    }

    public function setCarteGrise(string $CarteGrise): self
    {
        $this->CarteGrise = $CarteGrise;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }
    public function getPrixHS(): ?float
    {
        return $this->PrixHS;
    }

    public function setPrixHS(float $PrixHS): self
    {
        $this->PrixHS = $PrixHS;

        return $this;
    }
    
    public function getReservoire(): ?float
    {
        return $this->Reservoire;
    }

    public function setReservoire(float $Reservoire): self
    {
        $this->Reservoire = $Reservoire;

        return $this;
    }

    /**
     * @return Collection<int, Depence>
     */
    public function getDepences(): Collection
    {
        return $this->depences;
    }

    public function addDepence(Depence $depence): self
    {
        if (!$this->depences->contains($depence)) {
            $this->depences[] = $depence;
            $depence->setVehicule($this);
        }

        return $this;
    }

    public function removeDepence(Depence $depence): self
    {
        if ($this->depences->removeElement($depence)) {
            // set the owning side to null (unless already changed)
            if ($depence->getVehicule() === $this) {
                $depence->setVehicule(null);
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
            $feedback->setVehicule($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getVehicule() === $this) {
                $feedback->setVehicule(null);
            }
        }

        return $this;
    }
}
