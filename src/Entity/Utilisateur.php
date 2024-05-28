<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: "Adresse Mail au mauvais format.")]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 60)]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    private ?string $prenom = null;

    #[ORM\Column(length: 60)]
    private ?string $poste = null;

    #[ORM\OneToMany(targetEntity: EmpruntVehicule::class, mappedBy: 'emprunteur')]
    private Collection $empruntVehicules;

    #[ORM\OneToMany(targetEntity: EmpruntSalle::class, mappedBy: 'Emprunteur')]
    private Collection $empruntSalles;

    #[ORM\OneToMany(targetEntity: EmpruntMateriel::class, mappedBy: 'emprunteur')]
    private Collection $empruntMateriels;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'utilisateurs')]
    private ?Centre $Centre;

    public function __construct()
    {
        $this->empruntVehicules = new ArrayCollection();
        $this->empruntSalles = new ArrayCollection();
        $this->empruntMateriels = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

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
            $empruntVehicule->setEmprunteur($this);
        }

        return $this;
    }

    public function removeEmpruntVehicule(EmpruntVehicule $empruntVehicule): static
    {
        if ($this->empruntVehicules->removeElement($empruntVehicule)) {
            // set the owning side to null (unless already changed)
            if ($empruntVehicule->getEmprunteur() === $this) {
                $empruntVehicule->setEmprunteur(null);
            }
        }

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
            $empruntSalle->setEmprunteur($this);
        }

        return $this;
    }

    public function removeEmpruntSalle(EmpruntSalle $empruntSalle): static
    {
        if ($this->empruntSalles->removeElement($empruntSalle)) {
            // set the owning side to null (unless already changed)
            if ($empruntSalle->getEmprunteur() === $this) {
                $empruntSalle->setEmprunteur(null);
            }
        }

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
            $empruntMateriel->setEmprunteur($this);
        }

        return $this;
    }

    public function removeEmpruntMateriel(EmpruntMateriel $empruntMateriel): static
    {
        if ($this->empruntMateriels->removeElement($empruntMateriel)) {
            // set the owning side to null (unless already changed)
            if ($empruntMateriel->getEmprunteur() === $this) {
                $empruntMateriel->setEmprunteur(null);
            }
        }

        return $this;
    }

    public function getCentre(): ?Centre
    {
        return $this->Centre;
    }

    public function setCentre(?Centre $Centre): static
    {
        $this->Centre = $Centre;

        return $this;
    }

}
