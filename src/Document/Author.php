<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[MongoDB\Document]
class Author
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $name;

    #[MongoDB\Field(type: 'string')]
    private ?string $lastName;

    #[MongoDB\Field(type: 'int')]
    private ?int $year;

    #[MongoDB\Field(type: 'string')]
    private ?string $country;

    #[MongoDB\ReferenceMany(targetDocument: Book::class)]
    private ArrayCollection $book;

    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
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
    public function getBooks(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
        }
        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->book->removeElement($book);
        return $this;
    }
}