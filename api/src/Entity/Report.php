<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reports")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $reporter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="reports")
     * @ORM\JoinColumn(name="reported_message_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $reportedMessage;

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

    public function getReporter(): ?User
    {
        return $this->reporter;
    }

    public function setReporter(?User $reporter): self
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function getReportedMessage(): ?Message
    {
        return $this->reportedMessage;
    }

    public function setReportedMessage(?Message $reportedMessage): self
    {
        $this->reportedMessage = $reportedMessage;

        return $this;
    }
}
