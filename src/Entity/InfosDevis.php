<?php

namespace App\Entity;

use App\Repository\InfosDevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosDevisRepository::class)]
class InfosDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $Mail = null;

    #[ORM\Column]
    private ?float $Num_fiscal = null;

    #[ORM\ManyToMany(targetEntity: Tranche::class, inversedBy: 'infosDevis')]
    private Collection $Tranche;

    public function __construct()
    {
        $this->Tranche = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(string $Mail): static
    {
        $this->Mail = $Mail;

        return $this;
    }

    public function getNumFiscal(): ?float
    {
        return $this->Num_fiscal;
    }

    public function setNumFiscal(float $Num_fiscal): static
    {
        $this->Num_fiscal = $Num_fiscal;

        return $this;
    }

    /**
     * @return Collection<int, Tranche>
     */
    public function getTranche(): Collection
    {
        return $this->Tranche;
    }

    public function addTranche(Tranche $tranche): static
    {
        if (!$this->Tranche->contains($tranche)) {
            $this->Tranche->add($tranche);
        }

        return $this;
    }

    public function removeTranche(Tranche $tranche): static
    {
        $this->Tranche->removeElement($tranche);

        return $this;
    }

}
