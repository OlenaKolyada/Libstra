<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[MongoDB\Document]
class Book
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $title;

    #[MongoDB\Field(type: 'string')]
    private ?string $description;

    #[MongoDB\Field(type: 'string')]
    private ?string $language;

    #[MongoDB\Field(type: 'string')]
    private ?string $cover;

    #[MongoDB\Field(type: 'int')]
    private ?int $year;


    #[MongoDB\ReferenceOne(targetDocument: Author::class)]
    private ?Author $author;

    #[MongoDB\ReferenceMany(targetDocument: Genre::class)]
    private ArrayCollection $genre;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }
    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
            $genre->addBook($this);
        }
        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genre->removeElement($genre)) {
            $genre->removeBook($this);
        }
        return $this;
    }
}