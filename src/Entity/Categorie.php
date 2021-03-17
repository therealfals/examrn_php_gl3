<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=CV::class, inversedBy="categories")
     */
    private $cv;

    /**
     * @ORM\OneToMany(targetEntity=Emploi::class, mappedBy="categorie")
     */
    private $emplois;

    public function __construct()
    {
        $this->cv = new ArrayCollection();
        $this->emplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|CV[]
     */
    public function getCv(): Collection
    {
        return $this->cv;
    }

    public function addCv(CV $cv): self
    {
        if (!$this->cv->contains($cv)) {
            $this->cv[] = $cv;
        }

        return $this;
    }

    public function removeCv(CV $cv): self
    {
        $this->cv->removeElement($cv);

        return $this;
    }

    /**
     * @return Collection|Emploi[]
     */
    public function getEmplois(): Collection
    {
        return $this->emplois;
    }

    public function addEmploi(Emploi $emploi): self
    {
        if (!$this->emplois->contains($emploi)) {
            $this->emplois[] = $emploi;
            $emploi->setCategorie($this);
        }

        return $this;
    }

    public function removeEmploi(Emploi $emploi): self
    {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getCategorie() === $this) {
                $emploi->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
