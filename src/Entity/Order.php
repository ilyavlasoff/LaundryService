<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $receiveDate;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $endingDate;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $clientMark;

    /**
     * @ORM\Column(type="decimal", nullable=false)
     */
    private $sumPrice;

    /**
     * @ORM\ManyToOne(targetEntity="Complexity")
     * @ORM\JoinColumn(name="order_complexity", referencedColumnName="id")
     */
    private $complexity;

    /**
     * @ORM\ManyToOne(targetEntity="Urgency")
     * @ORM\JoinColumn(name="order_urgency", referencedColumnName="id")
     */
    private $urgency;

    /**
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(name="order_service", referencedColumnName="id")
     */
    private $serviceName;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="orders")
     * @ORM\JoinColumn(name="order_employee", referencedColumnName="id")
     */
    private $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="orders")
     * @ORM\JoinColumn(name="order_client", referencedColumnName="id")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiveDate(): ?\DateTimeInterface
    {
        return $this->receiveDate;
    }

    public function setReceiveDate(\DateTimeInterface $receiveDate): self
    {
        $this->receiveDate = $receiveDate;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    public function setEndingDate(\DateTimeInterface $endingDate): self
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    public function getActive(): ?\DateTimeInterface
    {
        return $this->active;
    }

    public function setActive(\DateTimeInterface $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getClientMark()
    {
        return $this->clientMark;
    }

    public function setClientMark($clientMark): self
    {
        $this->clientMark = $clientMark;

        return $this;
    }

    public function getSumPrice()
    {
        return $this->sumPrice;
    }

    public function setSumPrice($sumPrice): self
    {
        $this->sumPrice = $sumPrice;

        return $this;
    }

    public function getComplexity(): ?Complexity
    {
        return $this->complexity;
    }

    public function setComplexity(?Complexity $complexity): self
    {
        $this->complexity = $complexity;

        return $this;
    }

    public function getUrgency(): ?Urgency
    {
        return $this->urgency;
    }

    public function setUrgency(?Urgency $urgency): self
    {
        $this->urgency = $urgency;

        return $this;
    }

    public function getServiceName(): ?Service
    {
        return $this->serviceName;
    }

    public function setServiceName(?Service $serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
