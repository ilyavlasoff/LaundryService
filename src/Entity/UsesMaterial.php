<?php

namespace App\Entity;

use App\Repository\UsesMaterialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsesMaterialRepository::class)
 */
class UsesMaterial
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="useMaterials")
     */
    private $service;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Material", inversedBy="usedBy")
     */
    private $materials;

    /**
     * @ORM\Column(type="float")
     */
    private $usesQuantity;

    public function getUsesQuantity(): ?float
    {
        return $this->usesQuantity;
    }

    public function setUsesQuantity(float $usesQuantity): self
    {
        $this->usesQuantity = $usesQuantity;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getMaterials(): ?Material
    {
        return $this->materials;
    }

    public function setMaterials(?Material $materials): self
    {
        $this->materials = $materials;

        return $this;
    }

}
