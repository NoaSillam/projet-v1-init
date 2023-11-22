<?php

namespace App\Entity;

use App\Repository\RegionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionsRepository::class)]
class Regions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\OneToMany(mappedBy: 'Region', targetEntity: Tranche::class, orphanRemoval: true)]
    private Collection $tranches;

    #[ORM\OneToMany(mappedBy: 'Regions', targetEntity: InfosDevis::class, orphanRemoval: true)]
    private Collection $infosDevis;

    #[ORM\OneToMany(mappedBy: 'Regions', targetEntity: TrancheFiscal::class)]
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
            $tranch->setRegion($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): static
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getRegion() === $this) {
                $tranch->setRegion(null);
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
            $infosDevi->setRegions($this);
        }

        return $this;
    }

    public function removeInfosDevi(InfosDevis $infosDevi): static
    {
        if ($this->infosDevis->removeElement($infosDevi)) {
            // set the owning side to null (unless already changed)
            if ($infosDevi->getRegions() === $this) {
                $infosDevi->setRegions(null);
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
            $trancheFiscal->setRegions($this);
        }

        return $this;
    }

    public function removeTrancheFiscal(TrancheFiscal $trancheFiscal): static
    {
        if ($this->trancheFiscals->removeElement($trancheFiscal)) {
            // set the owning side to null (unless already changed)
            if ($trancheFiscal->getRegions() === $this) {
                $trancheFiscal->setRegions(null);
            }
        }

        return $this;
    }


}
