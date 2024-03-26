<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $modele = null;
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $noSerie = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Centre $centre = null;

    #[ORM\OneToMany(targetEntity: EmpruntMateriel::class, mappedBy: 'materiel',cascade: ['remove'])]
    private Collection $empruntMateriels;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    private ?Marque $marque = null;

    public function __construct()
    {
        $this->empruntMateriels = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getCentre(): ?centre
    {
        return $this->centre;
    }

    public function setCentre(?centre $centre): static
    {
        $this->centre = $centre;

        return $this;
    }

    public function getNoSerie(): ?string
    {
        return $this->noSerie;
    }

    public function setNoSerie(string $noSerie): static
    {
        $this->noSerie = $noSerie;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, EmpruntMateriel>
     */
    public function getEmpruntMateriels(): Collection
    {
        return $this->empruntMateriels;
    }

    public function addEmpruntMateriel(EmpruntMateriel $empruntMateriel): static
    {
        if (!$this->empruntMateriels->contains($empruntMateriel)) {
            $this->empruntMateriels->add($empruntMateriel);
            $empruntMateriel->setMateriel($this);
        }

        return $this;
    }

    public function removeEmpruntMateriel(EmpruntMateriel $empruntMateriel): static
    {
        if ($this->empruntMateriels->removeElement($empruntMateriel)) {
            // set the owning side to null (unless already changed)
            if ($empruntMateriel->getMateriel() === $this) {
                $empruntMateriel->setMateriel(null);
            }
        }

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }



}
