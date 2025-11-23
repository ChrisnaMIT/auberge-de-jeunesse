<?php

namespace App\Entity;

use App\Enum\DocumentType;
use App\Repository\GuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
class Guest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(nullable: true, enumType: DocumentType::class)]
    private ?DocumentType $documentType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $documentNumber = null;

    #[ORM\ManyToOne(inversedBy: 'guests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Booking $booking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    public function setDocumentType(?DocumentType $documentType): static
    {
        $this->documentType = $documentType;
        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(?string $documentNumber): static
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): static
    {
        $this->booking = $booking;
        return $this;
    }
}

