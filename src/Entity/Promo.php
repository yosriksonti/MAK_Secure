<?php

namespace App\Entity;

use App\Repository\PromoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Pourcentage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPourcentage(): ?float
    {
        return $this->Pourcentage;
    }

    public function setPourcentage(float $Pourcentage): self
    {
        $this->Pourcentage = $Pourcentage;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }
}
