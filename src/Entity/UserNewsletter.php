<?php

namespace App\Entity;

use App\Repository\UserNewsletterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserNewsletterRepository::class)]
class UserNewsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseMail = null;

    #[ORM\OneToMany(mappedBy: 'UserNewsletter', targetEntity: UserArticle::class, orphanRemoval: true)]
    private Collection $userArticles;

    #[ORM\Column]
    private ?int $numTelephone = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): static
    {
        $this->adresseMail = $adresseMail;

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
            $userArticle->setUserNewsletter($this);
        }

        return $this;
    }

    public function removeUserArticle(UserArticle $userArticle): static
    {
        if ($this->userArticles->removeElement($userArticle)) {
            // set the owning side to null (unless already changed)
            if ($userArticle->getUserNewsletter() === $this) {
                $userArticle->setUserNewsletter(null);
            }
        }

        return $this;
    }

    public function getNumTelephone(): ?int
    {
        return $this->numTelephone;
    }

    public function setNumTelephone(int $numTelephone): static
    {
        $this->numTelephone = $numTelephone;

        return $this;
    }
}
