<?php

namespace App\Entity;

use App\Repository\BlacklistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlacklistRepository::class)
 */
class Blacklist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $Permis;

    /**
     * @ORM\Column(type="string")
     */
    private $CIN;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCIN(): ?string
    {
        return $this->CIN;
    }

    public function setCIN(string $CIN): self
    {
        $this->CIN = $CIN;

        return $this;
    }
}
