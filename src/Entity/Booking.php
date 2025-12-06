<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // --- USER QUI RÉSERVE ---
    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // --- CHAMBRE RÉSERVÉE ---
    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    // --- LISTE DES OCCUPANTS ---
    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: Guest::class, cascade: ['persist', 'remove'])]
    private Collection $guests;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkOut = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'confirmed';

    #[ORM\Column(nullable: true)]
    private ?float $totalPrice = null;

    #[ORM\ManyToMany(targetEntity: Bed::class)]
    private Collection $beds;

    #[ORM\Column(type: 'integer', options: ['default' => 1])]
    private ?int $numberOfBedsReserved = 1;

    public function __construct()
    {
        $this->beds = new ArrayCollection();
        $this->guests = new ArrayCollection();
    }

    // -----------------------
    // GETTERS / SETTERS
    // -----------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    // ---- User ----
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    // ---- Room ----
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;
        return $this;
    }

    // ---- Guests ----
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(Guest $guest): static
    {
        if (!$this->guests->contains($guest)) {
            $this->guests->add($guest);
            $guest->setBooking($this);
        }
        return $this;
    }

    public function removeGuest(Guest $guest): static
    {
        if ($this->guests->removeElement($guest)) {
            if ($guest->getBooking() === $this) {
                $guest->setBooking(null);
            }
        }
        return $this;
    }

    // ---- Dates ----
    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(\DateTimeInterface $checkIn): static
    {
        $this->checkIn = $checkIn;
        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(\DateTimeInterface $checkOut): static
    {
        $this->checkOut = $checkOut;
        return $this;
    }

    // ---- Status ----
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    // ---- Beds ----
    public function getBeds(): Collection
    {
        return $this->beds;
    }

    public function addBed(Bed $bed): static
    {
        if (!$this->beds->contains($bed)) {
            $this->beds->add($bed);
        }
        return $this;
    }

    // ---- Number of beds ----
    public function getNumberOfBedsReserved(): ?int
    {
        return $this->numberOfBedsReserved;
    }

    public function setNumberOfBedsReserved(int $numberOfBedsReserved): static
    {
        $this->numberOfBedsReserved = $numberOfBedsReserved;
        return $this;
    }

    // ---- Total Price ----
    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    // ---- Price calculation ----
    public function calculateTotalPrice(): void
    {
        if ($this->room && $this->checkIn && $this->checkOut) {

            $interval = $this->checkIn->diff($this->checkOut);
            $nights = $interval->days;

            $pricePerNight = $this->room->getPricePerNight() ?? 0;
            $beds = $this->numberOfBedsReserved ?? 1;

            $this->totalPrice = $nights * $pricePerNight * $beds;
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTotalPrice(): void
    {
        $this->calculateTotalPrice();
    }
}
