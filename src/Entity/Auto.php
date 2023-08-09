<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Repository\AutoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AutoRepository::class)
 * @Vich\Uploadable
 */
class Auto
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
     *
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="Auto", orphanRemoval=true)
     */
    private $pictures;

    /**
     *
     * @Vich\UploadableField(mapping="Auto_image", fileNameProperty="CarteGrise")
     * 
     * @var File|null
     */
    private ?File $Grise = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVAT;

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
    private $Kilos;



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

    public function isClim(): ?bool
    {
        return $this->Clim;
    }

    public function setClim(bool $Clim): self
    {
        $this->Clim = $Clim;

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
    
    public function __toString() : string {
        return $this->id;
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
    public function getKilos(): ?float
    {
        return $this->Kilos;
    }

    public function setKilos(float $Kilos): self
    {
        $this->Kilos = $Kilos;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getPicture(): ?Picture
    {
        if($this->pictures == null || $this->pictures->isEmpty()) {
            return new Picture();
        }
        return $this->pictures->last();
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAdherantid($this);
        }

        return $this;
    }
    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAuto() === $this) {
                $picture->setAuto(null);
            }
        }

        return $this;
    }
}
