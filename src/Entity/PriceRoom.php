<?php

namespace App\Entity;

use App\Repository\PriceRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceRoomRepository::class)
 */
class PriceRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currency;

    /**
     * @ORM\OneToMany(targetEntity=SetRoom::class, mappedBy="priceRoom")
     */
    private $setRoom;

    public function __construct()
    {
        $this->setRoom = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection|SetRoom[]
     */
    public function getSetRoom(): Collection
    {
        return $this->setRoom;
    }

    public function addSetRoom(SetRoom $setRoom): self
    {
        if (!$this->setRoom->contains($setRoom)) {
            $this->setRoom[] = $setRoom;
            $setRoom->setPriceRoom($this);
        }

        return $this;
    }

    public function removeSetRoom(SetRoom $setRoom): self
    {
        if ($this->setRoom->contains($setRoom)) {
            $this->setRoom->removeElement($setRoom);
            // set the owning side to null (unless already changed)
            if ($setRoom->getPriceRoom() === $this) {
                $setRoom->setPriceRoom(null);
            }
        }

        return $this;
    }
}
