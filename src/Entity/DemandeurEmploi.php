<?php

namespace App\Entity;

use App\Repository\DemandeurEmploiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeurEmploiRepository::class)
 */
class DemandeurEmploi extends User
{
    /**
     * @ORM\OneToOne(targetEntity=CV::class, inversedBy="demandeurEmploi", cascade={"persist", "remove"})
     */
    private $cv;

    public function getCv(): ?CV
    {
        return $this->cv;
    }

    public function setCv(?CV $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
    public function __toString(): string
    {
        return $this->nomComplet;
    }
}
