<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture implements \JsonSerializable
{
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'createdAt' => $this->createdAt,
        ];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="galleryPictures")
     * @ORM\JoinColumn(name="user_gallery_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $userGallery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getUserGallery(): ?User
    {
        return $this->userGallery;
    }

    public function setUserGallery(?User $userGallery): self
    {
        $this->userGallery = $userGallery;

        return $this;
    }
}
