<?php

namespace App\Entity;

use App\Repository\TrancheFiscalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrancheFiscalRepository::class)]
class TrancheFiscal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Debut = null;

    #[ORM\Column(nullable: true)]
    private ?int $Fin = null;

    #[ORM\OneToMany(mappedBy: 'TrancheFiscal', targetEntity: Tranche::class)]
    private Collection $tranches;

    public function __construct()
    {
        $this->tranches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?int
    {
        return $this->Debut;
    }

    public function setDebut(int $Debut): static
    {
        $this->Debut = $Debut;

        return $this;
    }

    public function getFin(): ?int
    {
        return $this->Fin;
    }

    public function setFin(int $Fin): static
    {
        $this->Fin = $Fin;

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
            $tranch->setTrancheFiscal($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): static
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getTrancheFiscal() === $this) {
                $tranch->setTrancheFiscal(null);
            }
        }

        return $this;
    }

}
