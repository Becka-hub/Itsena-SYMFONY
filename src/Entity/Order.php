<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $shopping;

    #[ORM\Column(type: 'string', length: 255)]
    private $totalPrice;

    #[ORM\Column(type: 'string', length: 255)]
    private $adressDevivery;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $country;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    public function tojson(): ?array
    {
        return $this ? [
            'id' => $this->id,
            'shopping' => $this->shopping,
            'totalPrice' => $this->totalPrice,
            'adressDevivery' => $this->adressDevivery,
            'createdAt'=>$this->createdAt,
            'country'=>$this->country,
            'city'=>$this->city,
            'user'=>$this->user?$this->user->tojson():null,
        ] : null;
    }
    
    public function __construct()
    {
        $this->createdAt= new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShopping(): ?string
    {
        return $this->shopping;
    }

    public function setShopping(string $shopping): self
    {
        $this->shopping = $shopping;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getAdressDevivery(): ?string
    {
        return $this->adressDevivery;
    }

    public function setAdressDevivery(string $adressDevivery): self
    {
        $this->adressDevivery = $adressDevivery;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
