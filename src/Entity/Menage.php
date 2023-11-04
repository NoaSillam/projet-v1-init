<?php

namespace App\Entity;

use App\Repository\MenageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenageRepository::class)]
class Menage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\OneToMany(mappedBy: 'Menage', targetEntity: Prime::class, orphanRemoval: true)]
    private Collection $primes;

    #[ORM\OneToMany(mappedBy: 'Menage', targetEntity: Tranche::class, orphanRemoval: true)]
    private Collection $tranches;

    public function __construct()
    {
        $this->primes = new ArrayCollection();
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

    /**
     * @return Collection<int, Prime>
     */
    public function getPrimes(): Collection
    {
        return $this->primes;
    }

    public function addPrime(Prime $prime): static
    {
        if (!$this->primes->contains($prime)) {
            $this->primes->add($prime);
            $prime->setMenage($this);
        }

        return $this;
    }

    public function removePrime(Prime $prime): static
    {
        if ($this->primes->removeElement($prime)) {
            // set the owning side to null (unless already changed)
            if ($prime->getMenage() === $this) {
                $prime->setMenage(null);
            }
        }

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
            $tranch->setMenage($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): static
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getMenage() === $this) {
                $tranch->setMenage(null);
            }
        }

        return $this;
    }
}
