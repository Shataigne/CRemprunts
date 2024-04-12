<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero()]

    private ?int $numero = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $batiment = null;

    #[ORM\Column(nullable: true)]
    private ?int $etage = 0;

    #[ORM\Column(length: 3)]
    #[Assert\Choice(choices: ["CRS", "REU"])]
    private string $categorie;

    #[ORM\ManyToOne(inversedBy: 'salles')]
    #[ORM\JoinColumn(nullable: false)]
    private Centre $centre;

    #[ORM\OneToMany(targetEntity: EmpruntSalle::class, mappedBy: 'salle',cascade: ['remove'])]
    private Collection $empruntSalles;

    public function __construct()
    {
        $this->empruntSalles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(?string $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(?int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

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

    /**
     * @return Collection<int, EmpruntSalle>
     */
    public function getEmpruntSalles(): Collection
    {
        return $this->empruntSalles;
    }

    public function addEmpruntSalle(EmpruntSalle $empruntSalle): static
    {
        if (!$this->empruntSalles->contains($empruntSalle)) {
            $this->empruntSalles->add($empruntSalle);
            $empruntSalle->setSalle($this);
        }

        return $this;
    }

    public function removeEmpruntSalle(EmpruntSalle $empruntSalle): static
    {
        if ($this->empruntSalles->removeElement($empruntSalle)) {
            // set the owning side to null (unless already changed)
            if ($empruntSalle->getSalle() === $this) {
                $empruntSalle->setSalle(null);
            }
        }

        return $this;
    }


}
