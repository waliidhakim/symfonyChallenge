<?php

namespace App\Entity;

use App\Repository\GiftRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GiftRepository::class)]
class Gift
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $purchaseLink = null;

    #[ORM\ManyToOne(inversedBy: 'gifts')]
    private ?GiftList $giftList = null;

    #[ORM\ManyToOne(inversedBy: 'gifts')]
    private ?User $reservedBy = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isChosen = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chosenByEmail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPurchaseLink(): ?string
    {
        return $this->purchaseLink;
    }

    public function setPurchaseLink(?string $purchaseLink): static
    {
        $this->purchaseLink = $purchaseLink;

        return $this;
    }

    public function getGiftList(): ?GiftList
    {
        return $this->giftList;
    }

    public function setGiftList(?GiftList $giftList): static
    {
        $this->giftList = $giftList;

        return $this;
    }

    public function getReservedBy(): ?User
    {
        return $this->reservedBy;
    }

    public function setReservedBy(?User $reservedBy): static
    {
        $this->reservedBy = $reservedBy;

        return $this;
    }

    public function isIsChosen(): ?bool
    {
        return $this->isChosen;
    }

    public function setIsChosen(?bool $isChosen): static
    {
        $this->isChosen = $isChosen;

        return $this;
    }

    public function getChosenByEmail(): ?string
    {
        return $this->chosenByEmail;
    }

    public function setChosenByEmail(?string $chosenByEmail): static
    {
        $this->chosenByEmail = $chosenByEmail;

        return $this;
    }
}
