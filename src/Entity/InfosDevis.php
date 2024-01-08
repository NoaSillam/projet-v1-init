<?php

namespace App\Entity;

use App\Repository\InfosDevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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



    #[ORM\ManyToOne(inversedBy: 'infosDevis')]
    private ?Personne $nbPersonne = null;

    #[ORM\ManyToOne(inversedBy: 'infosDevis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Regions $Regions = null;

    #[ORM\ManyToOne(inversedBy: 'infosDevis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrancheFiscal $TrancheFiscal = null;

    #[ORM\Column(length: 255)]
    private ?string $proprieter = null;

    #[ORM\Column]
    private ?int $surfaceHabitable = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $typeChauffage = null;

    #[ORM\Column(length: 255)]
    private ?string $residencePrincipale = null;

    #[ORM\Column]
    private ?bool $validations = false;

    #[ORM\Column(length: 255)]
    private ?string $installations = null;

    #[ORM\Column]
    private ?bool $validationCEE = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePropriete = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $isolationCombles = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateConstruct = null;

    public function __construct()
    {
       // $this->nbPersonne = null; // Initialisez avec null
    }


    #[Assert\Callback(callback: 'validateNumFiscal')]
    public function validateNumFiscal($context): void
    {
        $trancheFiscal = $this->getTrancheFiscal();
        $numFiscal = $this->getNumFiscal();

        if ($trancheFiscal) {
            $debutTranche = $trancheFiscal->getDebut();
            $finTranche = $trancheFiscal->getFin();

            if ($debutTranche !== null && $numFiscal < $debutTranche) {
                $context->buildViolation('Le numéro fiscal doit être supérieur ou égal au début de la tranche fiscale choisie.')
                    ->atPath('Num_fiscal')
                    ->addViolation();
            }

            if ($finTranche !== null && $numFiscal > $finTranche) {
                $context->buildViolation('Le numéro fiscal doit être inférieur ou égal à la fin de la tranche fiscale choisie.')
                    ->atPath('Num_fiscal')
                    ->addViolation();
            }
        }
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


    public function getNbPersonne(): ?Personne
    {
        return $this->nbPersonne;
    }

/*    public function setNbPersonne(?Personne $nbPersonne): static
    {
        $this->nbPersonne = $nbPersonne;

        return $this;
    }*/
    public function setNbPersonne(?Personne $nbPersonne): static
    {
        $this->nbPersonne = $nbPersonne;

        return $this;
    }

    public function getRegions(): ?Regions
    {
        return $this->Regions;
    }

    public function setRegions(?Regions $Regions): static
    {
        $this->Regions = $Regions;

        return $this;
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

    public function getProprieter(): ?string
    {
        return $this->proprieter;
    }

    public function setProprieter(string $proprieter): static
    {
        $this->proprieter = $proprieter;

        return $this;
    }

    public function getSurfaceHabitable(): ?int
    {
        return $this->surfaceHabitable;
    }

    public function setSurfaceHabitable(int $surfaceHabitable): static
    {
        $this->surfaceHabitable = $surfaceHabitable;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTypeChauffage(): ?string
    {
        return $this->typeChauffage;
    }

    public function setTypeChauffage(string $typeChauffage): static
    {
        $this->typeChauffage = $typeChauffage;

        return $this;
    }

    public function getResidencePrincipale(): ?string
    {
        return $this->residencePrincipale;
    }

    public function setResidencePrincipale(string $residencePrincipale): static
    {
        $this->residencePrincipale = $residencePrincipale;

        return $this;
    }

    public function isValidations(): ?bool
    {
        return $this->validations;
    }

    public function setValidations(bool $validations): static
    {
        $this->validations = $validations;

        return $this;
    }

    public function getInstallations(): ?string
    {
        return $this->installations;
    }

    public function setInstallations(string $installations): static
    {
        $this->installations = $installations;

        return $this;
    }

    public function isValidationCEE(): ?bool
    {
        return $this->validationCEE;
    }

    public function setValidationCEE(bool $validationCEE): static
    {
        $this->validationCEE = $validationCEE;

        return $this;
    }

    public function getDatePropriete(): ?\DateTimeInterface
    {
        return $this->datePropriete;
    }

    public function setDatePropriete(?\DateTimeInterface $datePropriete): static
    {
        $this->datePropriete = $datePropriete;

        return $this;
    }

    public function getIsolationCombles(): ?string
    {
        return $this->isolationCombles;
    }

    public function setIsolationCombles(?string $isolationCombles): static
    {
        $this->isolationCombles = $isolationCombles;

        return $this;
    }

    public function getDateConstruct(): ?\DateTimeInterface
    {
        return $this->dateConstruct;
    }

    public function setDateConstruct(?\DateTimeInterface $dateConstruct): static
    {
        $this->dateConstruct = $dateConstruct;

        return $this;
    }


}
