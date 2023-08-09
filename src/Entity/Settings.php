<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 * @Vich\Uploadable
 */
class Settings
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
    private $Photo_Couverture;

    /**
     *
     * @Vich\UploadableField(mapping="Settings_image", fileNameProperty="Photo_Couverture")
     * 
     * @var File|null
     */
    private ?File $Couverture = null;


    /**
     * @ORM\Column(type="string", length=501)
     */
    private $Propos;

    /**
     * @ORM\Column(type="string", length=501)
     */
    private $Ad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Banner = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoCouverture(): ?string
    {
        return $this->Photo_Couverture;
    }

    public function setPhotoCouverture(string $Photo_Couverture): self
    {
        $this->Photo_Couverture = $Photo_Couverture;

        return $this;
    }

    public function getPropos(): ?string
    {
        return $this->Propos;
    }

    public function setPropos(string $Propos): self
    {
        $this->Propos = $Propos;

        return $this;
    }

    public function getAd(): ?string
    {
        return $this->Ad;
    }

    public function setAd(string $Ad): self
    {
        $this->Ad = $Ad;

        return $this;
    }

    public function getBanner(): ?bool
    {
        return $this->Banner;
    }

    public function setBanner(bool $Banner): self
    {
        $this->Banner = $Banner;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getCouverture(): ?File
    {
        return $this->Couverture;
    }

    public function setCouverture(?File $reel): void
    {
        $this->Couverture = $reel;

        if ($reel instanceof UploadedFile) {
            $this->updatedAt = new \DateTime();
        }
    }
}
