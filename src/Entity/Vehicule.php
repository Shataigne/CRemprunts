<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;


    #[ORM\Column(length: 50, nullable: true)]
    private ?string $modele = null;
    #[ORM\Column(length: 20)]
    private ?string $plaque = null;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Centre $centre = null;

    #[ORM\OneToMany(targetEntity: EmpruntVehicule::class, mappedBy: 'vehicule',cascade: ['remove'])]
    private Collection $empruntVehicules;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marque $marque = null;

    public function __construct()
    {
        $this->empruntVehicules = new ArrayCollection();
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

    public function getPlaque(): ?string
    {
        return $this->plaque;
    }

    public function setPlaque(string $plaque): static
    {
        $this->plaque = $plaque;

        return $this;
    }

    /**
     * @return Collection<int, EmpruntVehicule>
     */
    public function getEmpruntVehicules(): Collection
    {
        return $this->empruntVehicules;
    }

    public function addEmpruntVehicule(EmpruntVehicule $empruntVehicule): static
    {
        if (!$this->empruntVehicules->contains($empruntVehicule)) {
            $this->empruntVehicules->add($empruntVehicule);
            $empruntVehicule->setVehicule($this);
        }

        return $this;
    }

    public function removeEmpruntVehicule(EmpruntVehicule $empruntVehicule): static
    {
        if ($this->empruntVehicules->removeElement($empruntVehicule)) {
            // set the owning side to null (unless already changed)
            if ($empruntVehicule->getVehicule() === $this) {
                $empruntVehicule->setVehicule(null);
            }
        }

        return $this;
    }

    public function getMarque(): ?marque
    {
        return $this->marque;
    }

    public function setMarque(?marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }



}
