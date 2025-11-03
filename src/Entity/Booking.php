<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;



#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $checkIn = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $checkOut = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'confirmed';

    #[ORM\Column]
    private ?int $bedsBooked = 1;

    #[ORM\Column(nullable: true)]
    private ?float $totalPrice = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $reference = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getCheckIn(): ?\DateTime
    {
        return $this->checkIn;
    }

    public function setCheckIn(\DateTime $checkIn): static
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTime
    {
        return $this->checkOut;
    }

    public function setCheckOut(\DateTime $checkOut): static
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBedsBooked(): ?int
    {
        return $this->bedsBooked;
    }

    public function setBedsBooked(int $bedsBooked): static
    {
        $this->bedsBooked = $bedsBooked;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function calculateTotalPrice(): void
    {
        if ($this->room && $this->checkIn && $this->checkOut) {
            $interval = $this->checkIn->diff($this->checkOut);
            $nights = $interval->days;

            $pricePerNight = $this->room->getPricePerNight() ?? 0;
            $beds = $this->bedsBooked ?? 1;

            $this->totalPrice = $nights * $pricePerNight * $beds;
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTotalPrice(): void
    {
        $this->calculateTotalPrice();
    }


    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

}
