<?php

namespace App\Entity;

use App\Repository\UserArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserArticleRepository::class)]
class UserArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userArticles', cascade: ["remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserNewsletter $userNewsletter = null;

    #[ORM\ManyToOne(inversedBy: 'userArticles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ArticleNewsletter $articleNewsletter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserNewsletter(): ?userNewsletter
    {
        return $this->userNewsletter;
    }

    public function setUserNewsletter(?userNewsletter $userNewsletter): static
    {
        $this->userNewsletter = $userNewsletter;

        return $this;
    }

    public function getArticleNewsletter(): ?articleNewsletter
    {
        return $this->articleNewsletter;
    }

    public function setArticleNewsletter(?articleNewsletter $articleNewsletter): static
    {
        $this->articleNewsletter = $articleNewsletter;

        return $this;
    }
}
