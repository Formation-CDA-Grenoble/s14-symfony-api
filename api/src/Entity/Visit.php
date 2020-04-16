<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 */
class Visit implements \JsonSerializable
{
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'visitorId' => $this->visitor->getId(),
            'visitedId' => $this->visited->getId(),
        ];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sentVisits")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $visitor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receivedVisits")
     * @ORM\JoinColumn(name="visited_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $visited;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVisitor(): ?User
    {
        return $this->visitor;
    }

    public function setVisitor(?User $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }

    public function getVisited(): ?User
    {
        return $this->visited;
    }

    public function setVisited(?User $visited): self
    {
        $this->visited = $visited;

        return $this;
    }
}
