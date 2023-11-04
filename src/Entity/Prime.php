<?php

namespace App\Entity;

use App\Repository\PrimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrimeRepository::class)]
class Prime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\ManyToOne(inversedBy: 'primes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeDevis $Type_chauffage = null;

    #[ORM\ManyToOne(inversedBy: 'primes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menage $Menage = null;

    #[ORM\Column]
    private ?int $aide = null;

    #[ORM\OneToMany(mappedBy: 'Prime', targetEntity: Tranche::class, orphanRemoval: true)]
    private Collection $tranches;

    public function __construct()
    {
        $this->tranches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getType_Chauffage(): ?TypeDevis
    {
        return $this->Type_chauffage;
    }

    public function setType_Chauffage(?TypeDevis $Type_chauffage): static
    {
        $this->Type_chauffage = $Type_chauffage;

        return $this;
    }

    public function getMenage(): ?Menage
    {
        return $this->Menage;
    }

    public function setMenage(?Menage $Menage): static
    {
        $this->Menage = $Menage;

        return $this;
    }

    public function getAide(): ?int
    {
        return $this->aide;
    }

    public function setAide(int $aide): static
    {
        $this->aide = $aide;

        return $this;
    }

    /**
     * @return Collection<int, Tranche>
     */
    public function getTranches(): Collection
    {
        return $this->tranches;
    }

    public function addTranch(Tranche $tranch): static
    {
        if (!$this->tranches->contains($tranch)) {
            $this->tranches->add($tranch);
            $tranch->setPrime($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): static
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getPrime() === $this) {
                $tranch->setPrime(null);
            }
        }

        return $this;
    }
}
