<?php

namespace App\Entity;

use App\Repository\UrgencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrgencyRepository::class)
 */
class Urgency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $pricingCoefficient;

    public function getId(): ?int
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

    public function getPricingCoefficient(): ?float
    {
        return $this->pricingCoefficient;
    }

    public function setPricingCoefficient(float $pricingCoefficient): self
    {
        $this->pricingCoefficient = $pricingCoefficient;

        return $this;
    }
}
