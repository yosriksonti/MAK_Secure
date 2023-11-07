<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PartnerRepository::class)
 * @Vich\Uploadable
 */
class Partner
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
    private $Photo_Def;

    
    /**
     *
     * @Vich\UploadableField(mapping="Partner_image", fileNameProperty="Photo_Def")
     * 
     * @var File|null
     */
    private ?File $Def = null;

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


    public function getPhotoDef(): ?string
    {
        return $this->Photo_Def;
    }

    public function setPhotoDef(?string $Photo_Def): self
    {
        $this->Photo_Def = $Photo_Def;

        return $this;
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

}
