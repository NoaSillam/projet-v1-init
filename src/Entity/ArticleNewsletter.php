<?php

namespace App\Entity;

use App\Repository\ArticleNewsletterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleNewsletterRepository::class)]
class ArticleNewsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $annonce = null;

    #[ORM\OneToMany(mappedBy: 'ArticleNewsletter', targetEntity: ArticleNewsletter::class, orphanRemoval: true)]
    private Collection $userArticles;


    public function __construct()
    {
        $this->userArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAnnonce(): ?string
    {
        return $this->annonce;
    }

    public function setAnnonce(string $annonce): static
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * @return Collection<int, UserArticle>
     */
    public function getUserArticles(): Collection
    {
        return $this->userArticles;
    }

    public function addUserArticle(UserArticle $userArticle): static
    {
        if (!$this->userArticles->contains($userArticle)) {
            $this->userArticles->add($userArticle);
            $userArticle->setArticleNewsletter($this);
        }

        return $this;
    }

    public function removeUserArticle(UserArticle $userArticle): static
    {
        if ($this->userArticles->removeElement($userArticle)) {
            // set the owning side to null (unless already changed)
            if ($userArticle->getArticleNewsletter() === $this) {
                $userArticle->setArticleNewsletter(null);
            }
        }

        return $this;
    }



}
