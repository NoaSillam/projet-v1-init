<?php

namespace App\Entity;

use App\Repository\TrancheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrancheRepository::class)]
class Tranche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $debut = null;

    #[ORM\Column(nullable: true)]
    private ?int $Fin = null;

    #[ORM\ManyToOne(inversedBy: 'tranches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menage $Menage = null;

    #[ORM\ManyToOne(inversedBy: 'tranches')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Regions $Region = null;

    #[ORM\ManyToOne(inversedBy: 'tranches')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Personne $nbPersonne = null;

    #[ORM\ManyToOne(inversedBy: 'tranches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prime $Prime = null;

    #[ORM\ManyToOne(inversedBy: 'tranches')]
    private ?TypeDevis $TypeDevis = null;

    #[ORM\Column]
    private ?int $Aide = null;



    #[ORM\ManyToOne(inversedBy: 'tranches')]
    private ?TrancheFiscal $TrancheFiscal = null;

    public function __construct()
    {
      //  $this->infosDevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?int
    {
        return $this->debut;
    }

    public function setDebut(int $debut): static
    {
        $this->debut = $debut;

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

    public function getMenage(): ?Menage
    {
        return $this->Menage;
    }

    public function setMenage(?Menage $Menage): static
    {
        $this->Menage = $Menage;

        return $this;
    }

    public function getRegion(): ?Regions
    {
        return $this->Region;
    }

    public function setRegion(?Regions $Region): static
    {
        $this->Region = $Region;

        return $this;
    }

    public function getNbPersonne(): ?Personne
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(?Personne $nbPersonne): static
    {
        $this->nbPersonne = $nbPersonne;

        return $this;
    }

    public function getPrime(): ?Prime
    {
        return $this->Prime;
    }

    public function setPrime(?Prime $Prime): static
    {
        $this->Prime = $Prime;

        return $this;
    }

    public function getTypeDevis(): ?TypeDevis
    {
        return $this->TypeDevis; // Utilisez TypeDevis au lieu de Type_devis
    }

    public function setTypeDevis(?TypeDevis $TypeDevis): static
    {
        $this->TypeDevis = $TypeDevis; // Utilisez TypeDevis au lieu de Type_devis

        return $this;
    }

    public function getAide(): ?int
    {
        return $this->Aide;
    }

    public function setAide(int $Aide): static
    {
        $this->Aide = $Aide;

        return $this;
    }


    public function __toString()
    {
        if($this->Fin == null)
        {
            return (string) ' > '.$this->debut;
        }
        else{
            return (string) $this->debut.' - '.$this->Fin;
        }
        // Choisissez la propriété que vous souhaitez afficher dans le champ du formulaire, par exemple, 'debut'

    }

    public function getTrancheFiscal(): ?TrancheFiscal
    {
        return $this->TrancheFiscal;
    }

    public function setTrancheFiscal(?TrancheFiscal $TrancheFiscal): static
    {
        $this->TrancheFiscal = $TrancheFiscal;

        return $this;
    }


}
