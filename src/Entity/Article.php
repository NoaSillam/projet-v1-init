<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'article ne peut pas être vide')]
    #[Assert\Length(min:5, minMessage:'Le nom de l\'article doit faire minimum {{ limit }} caractères')]
    #[Assert\Length(max:255, maxMessage:'Le nom de l\'article ne doit pas faire plus de {{ limit }} caractères')]
    private ?string $Nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Annonce = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Type $type_article = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_created = null;

    public function __construct()
    {
        $this->date_created = new \DateTime();
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

    public function getAnnonce(): ?string
    {
        return $this->Annonce;
    }

    public function setAnnonce(?string $Annonce): static
    {
        $this->Annonce = $Annonce;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    public function getTypeArticle(): ?Type
    {
        return $this->type_article;
    }

    public function setTypeArticle(?Type $type_article): static
    {
        $this->type_article = $type_article;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }
}
