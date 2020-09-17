<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false, length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", nullable=false)
     */
    private $standardPricing;

    /**
     * @ORM\OneToMany(targetEntity="UsesMaterial", mappedBy="service")
     */
    private $useMaterials;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $image;

    public function __construct()
    {
        $this->materials = new ArrayCollection();
        $this->useMaterials = new ArrayCollection();
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

    public function getStandardPricing(): ?string
    {
        return $this->standardPricing;
    }

    public function setStandardPricing(string $standardPricing): self
    {
        $this->standardPricing = $standardPricing;

        return $this;
    }

    /**
     * @return Collection|UsesMaterial[]
     */
    public function getUseMaterials(): Collection
    {
        return $this->useMaterials;
    }

    public function addUseMaterial(UsesMaterial $useMaterial): self
    {
        if (!$this->useMaterials->contains($useMaterial)) {
            $this->useMaterials[] = $useMaterial;
            $useMaterial->setService($this);
        }

        return $this;
    }

    public function removeUseMaterial(UsesMaterial $useMaterial): self
    {
        if ($this->useMaterials->contains($useMaterial)) {
            $this->useMaterials->removeElement($useMaterial);
            // set the owning side to null (unless already changed)
            if ($useMaterial->getService() === $this) {
                $useMaterial->setService(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


}
