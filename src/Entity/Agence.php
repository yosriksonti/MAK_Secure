<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 */
class Agence
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
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Addresse;

    /**
     * @ORM\Column(type="float")
     */
    private $Frais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Maps;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="Agence_Depart")
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="Agence_Arrive")
     */
    private $locations_arrive;

    /**
     * @ORM\Column(type="string", length=510)
     */
    private $Maps_Frame;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->locations_arrive = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->Addresse;
    }

    public function setAddresse(string $Addresse): self
    {
        $this->Addresse = $Addresse;

        return $this;
    }

    public function getFrais() : ?float
    {
        return $this->Frais;
    }

    public function setFrais(float $Frais): self
    {
        $this->Frais = $Frais;

        return $this;
    }

    public function getMaps(): ?string
    {
        return $this->Maps;
    }

    public function setMaps(string $Maps): self
    {
        $this->Maps = $Maps;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setAgenceDepart($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getAgenceDepart() === $this) {
                $location->setAgenceDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocationsArrive(): Collection
    {
        return $this->locations_arrive;
    }

    public function addLocationsArrive(Location $locationsArrive): self
    {
        if (!$this->locations_arrive->contains($locationsArrive)) {
            $this->locations_arrive[] = $locationsArrive;
            $locationsArrive->setAgenceArrive($this);
        }

        return $this;
    }

    public function removeLocationsArrive(Location $locationsArrive): self
    {
        if ($this->locations_arrive->removeElement($locationsArrive)) {
            // set the owning side to null (unless already changed)
            if ($locationsArrive->getAgenceArrive() === $this) {
                $locationsArrive->setAgenceArrive(null);
            }
        }

        return $this;
    }

    public function getMapsFrame(): ?string
    {
        return $this->Maps_Frame;
    }

    public function setMapsFrame(string $Maps_Frame): self
    {
        $this->Maps_Frame = $Maps_Frame;

        return $this;
    }
}
