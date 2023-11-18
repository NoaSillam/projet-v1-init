<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbPersonne = null;

    #[ORM\OneToMany(mappedBy: 'nbPersonne', targetEntity: Tranche::class, orphanRemoval: true)]
    private Collection $tranches;

    #[ORM\OneToMany(mappedBy: 'nbPersonne', targetEntity: InfosDevis::class, orphanRemoval: true)]
    private Collection $infosDevis;

    #[ORM\OneToMany(mappedBy: 'nbPersonne', targetEntity: TrancheFiscal::class, orphanRemoval: true)]
    private Collection $trancheFiscals;

    public function __construct()
    {
        $this->tranches = new ArrayCollection();
        $this->infosDevis = new ArrayCollection();
        $this->trancheFiscals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPersonne(): ?int
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(int $nbPersonne): static
    {
        $this->nbPersonne = $nbPersonne;

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
            $tranch->setNbPersonne($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): static
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getNbPersonne() === $this) {
                $tranch->setNbPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InfosDevis>
     */
    public function getInfosDevis(): Collection
    {
        return $this->infosDevis;
    }

    public function addInfosDevi(InfosDevis $infosDevi): static
    {
        if (!$this->infosDevis->contains($infosDevi)) {
            $this->infosDevis->add($infosDevi);
            $infosDevi->setNbPersonne($this);
        }

        return $this;
    }

    public function removeInfosDevi(InfosDevis $infosDevi): static
    {
        if ($this->infosDevis->removeElement($infosDevi)) {
            // set the owning side to null (unless already changed)
            if ($infosDevi->getNbPersonne() === $this) {
                $infosDevi->setNbPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TrancheFiscal>
     */
    public function getTrancheFiscals(): Collection
    {
        return $this->trancheFiscals;
    }

    public function addTrancheFiscal(TrancheFiscal $trancheFiscal): static
    {
        if (!$this->trancheFiscals->contains($trancheFiscal)) {
            $this->trancheFiscals->add($trancheFiscal);
            $trancheFiscal->setNbPersonne($this);
        }

        return $this;
    }

    public function removeTrancheFiscal(TrancheFiscal $trancheFiscal): static
    {
        if ($this->trancheFiscals->removeElement($trancheFiscal)) {
            // set the owning side to null (unless already changed)
            if ($trancheFiscal->getNbPersonne() === $this) {
                $trancheFiscal->setNbPersonne(null);
            }
        }

        return $this;
    }
}
