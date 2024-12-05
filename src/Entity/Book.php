<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[UniqueEntity('isbn')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 255)]
    #[Assert\NotNull]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 255)]
    #[Assert\NotBlank]
    private ?string $author = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Range(min:1900, max: 2024)]
    private ?int $release_date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Isbn(type: Assert\Isbn::ISBN_10)]
    private ?string $isbn = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getReleaseDate(): ?int
    {
        return $this->release_date;
    }

    public function setReleaseDate(int $release_date): static
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }
}
