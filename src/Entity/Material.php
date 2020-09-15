<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaterialRepository::class)
 */
class Material
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="UsesMaterial", mappedBy="materials")
     */
    private $usedBy;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $available;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->usedBy = new ArrayCollection();
    }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAvailable(): ?float
    {
        return $this->available;
    }

    public function setAvailable(float $available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection|UsesMaterial[]
     */
    public function getUsedBy(): Collection
    {
        return $this->usedBy;
    }

    public function addUsedBy(UsesMaterial $usedBy): self
    {
        if (!$this->usedBy->contains($usedBy)) {
            $this->usedBy[] = $usedBy;
            $usedBy->addMaterial($this);
        }

        return $this;
    }

    public function removeUsedBy(UsesMaterial $usedBy): self
    {
        if ($this->usedBy->contains($usedBy)) {
            $this->usedBy->removeElement($usedBy);
            $usedBy->removeMaterial($this);
        }

        return $this;
    }


}
