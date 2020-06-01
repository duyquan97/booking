<?php

namespace App\Entity;

use App\Repository\SetRoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SetRoomRepository::class)
 * @UniqueEntity(
 *     fields={"room","dateRoom"},
 *     errorPath = "dateRoom")
 */
class SetRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $roomCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="setRooms")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity=PriceRoom::class, inversedBy="setRoom")
     */
    private $priceRoom;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount = 0;

    /**
     * @ORM\ManyToOne(targetEntity=DateRoom::class, inversedBy="setRoom")
     */
    private $dateRoom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomCount(): ?int
    {
        return $this->roomCount;
    }

    public function setRoomCount(?int $roomCount): self
    {
        $this->roomCount = $roomCount;

        return $this;
    }

    public function getPerson(): ?int
    {
        return $this->person;
    }

    public function setPerson(?int $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getPriceRoom(): ?PriceRoom
    {
        return $this->priceRoom;
    }

    public function setPriceRoom(?PriceRoom $priceRoom): self
    {
        $this->priceRoom = $priceRoom;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDateRoom(): ?DateRoom
    {
        return $this->dateRoom;
    }

    public function setDateRoom(?DateRoom $dateRoom): self
    {
        $this->dateRoom = $dateRoom;

        return $this;
    }
}
