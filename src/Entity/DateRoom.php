<?php

namespace App\Entity;

use App\Repository\DateRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateRoomRepository::class)
 */
class DateRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fromDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $toDate;

    /**
     * @ORM\OneToMany(targetEntity=SetRoom::class, mappedBy="dateRoom")
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

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

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
            $setRoom->setDateRoom($this);
        }

        return $this;
    }

    public function removeSetRoom(SetRoom $setRoom): self
    {
        if ($this->setRoom->contains($setRoom)) {
            $this->setRoom->removeElement($setRoom);
            // set the owning side to null (unless already changed)
            if ($setRoom->getDateRoom() === $this) {
                $setRoom->setDateRoom(null);
            }
        }

        return $this;
    }
}
