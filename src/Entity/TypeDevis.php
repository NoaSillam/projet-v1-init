<?php

namespace App\Entity;

use App\Repository\TypeDevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDevisRepository::class)]
class TypeDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?int $metre_carre = null;



    #[ORM\OneToMany(mappedBy: 'TypeDevis', targetEntity: Tranche::class, orphanRemoval: true)]
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

    public function getMetreCarre(): ?int
    {
        return $this->metre_carre;
    }

    public function setMetreCarre(int $metre_carre): static
    {
        $this->metre_carre = $metre_carre;

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
            $tranch->setTypeDevis($this);
        }

        return $this;
    }

    public function removeTranch(Tranche $tranch): static
    {
        if ($this->tranches->removeElement($tranch)) {
            // set the owning side to null (unless already changed)
            if ($tranch->getTypeDevis() === $this) {
                $tranch->setTypeDevis(null);
            }
        }

        return $this;
    }
}
