<?php

namespace App\Entity;

use App\Repository\CVRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CVRepository::class)
 */
class CV
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le prenom est obligatoire")
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="L'age est obligatoire")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'adresse est obligatoire")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="L'email n'est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le telephone est obligatoire")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="La specialité est obligatoire")
     */
    private $specialite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le niveau d'etudes est obligatoire")
     */
    private $niveauEtude;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="L'experience professionnelle est obligatoire")
     */
    private $experienceProfessionnelle;

    /**
     * @ORM\OneToOne(targetEntity=DemandeurEmploi::class, mappedBy="cv", cascade={"persist", "remove"})
     */
    private $demandeurEmploi;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, mappedBy="cv")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getNiveauEtude(): ?string
    {
        return $this->niveauEtude;
    }

    public function setNiveauEtude(?string $niveauEtude): self
    {
        $this->niveauEtude = $niveauEtude;

        return $this;
    }

    public function getExperienceProfessionnelle(): ?string
    {
        return $this->experienceProfessionnelle;
    }

    public function setExperienceProfessionnelle(?string $experienceProfessionnelle): self
    {
        $this->experienceProfessionnelle = $experienceProfessionnelle;

        return $this;
    }

    public function getDemandeurEmploi(): ?DemandeurEmploi
    {
        return $this->demandeurEmploi;
    }

    public function setDemandeurEmploi(?DemandeurEmploi $demandeurEmploi): self
    {
        // unset the owning side of the relation if necessary
        if ($demandeurEmploi === null && $this->demandeurEmploi !== null) {
            $this->demandeurEmploi->setCv(null);
        }

        // set the owning side of the relation if necessary
        if ($demandeurEmploi !== null && $demandeurEmploi->getCv() !== $this) {
            $demandeurEmploi->setCv($this);
        }

        $this->demandeurEmploi = $demandeurEmploi;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addCv($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeCv($this);
        }

        return $this;
    }
}
