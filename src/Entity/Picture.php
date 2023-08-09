<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Auto;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Picture
 *
 * @ORM\Table(name="Picture", indexes={@ORM\Index(name="Auto", columns={"Auto"}) })
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Picture implements \Serializable
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdat  ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
     */
    private $updatedat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;
    

    /**
     * @Vich\UploadableField(mapping="Auto_image", fileNameProperty="image")
     * @var File|null
    */
    private  $imageFile = null;

    /**
     * @var \Auto
     *
     * @ORM\ManyToOne(targetEntity="Auto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Auto", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $Auto;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile instanceof UploadedFile) {
            $this->updatedat = new \DateTime();
        }
    }
    public function getAuto(): ?Auto
    {
        return $this->Auto;
    }

    public function setAuto(?Auto $Auto): self
    {
        $this->Auto = $Auto;

        return $this;
    }


    public function serialize()
    {
        $this->imageFile = base64_encode($this->imageFile);
    }

    public function unserialize($serialized)
    {
        $this->imageFile = base64_decode($this->imageFile);

    }
}