<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, JsonSerializable
{
    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate,
            'gender' => $this->gender,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'city' => $this->city,
            'galleryPictures' => $this->galleryPictures->getValues(),
            'sentVisits' => $this->sentVisits->getValues(),
        ];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $gender;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="users")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="userGallery")
     */
    private $galleryPictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Report", mappedBy="reporter")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="sender")
     */
    private $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="recipient")
     */
    private $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Heart", mappedBy="sender")
     */
    private $sentHearts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Heart", mappedBy="recipient")
     */
    private $receivedHearts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visit", mappedBy="visitor")
     */
    private $sentVisits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visit", mappedBy="visited")
     */
    private $receivedVisits;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->galleryPictures = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->sentHearts = new ArrayCollection();
        $this->receivedHearts = new ArrayCollection();
        $this->sentVisits = new ArrayCollection();
        $this->receivedVisits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getGalleryPictures(): Collection
    {
        return $this->galleryPictures;
    }

    public function addGalleryPicture(Picture $galleryPicture): self
    {
        if (!$this->galleryPictures->contains($galleryPicture)) {
            $this->galleryPictures[] = $galleryPicture;
            $galleryPicture->setUserGallery($this);
        }

        return $this;
    }

    public function removeGalleryPicture(Picture $galleryPicture): self
    {
        if ($this->galleryPictures->contains($galleryPicture)) {
            $this->galleryPictures->removeElement($galleryPicture);
            // set the owning side to null (unless already changed)
            if ($galleryPicture->getUserGallery() === $this) {
                $galleryPicture->setUserGallery(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setReporter($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getReporter() === $this) {
                $report->setReporter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->removeElement($sentMessage);
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSender() === $this) {
                $sentMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->setRecipient($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->removeElement($receivedMessage);
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getRecipient() === $this) {
                $receivedMessage->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Heart[]
     */
    public function getSentHearts(): Collection
    {
        return $this->sentHearts;
    }

    public function addSentHeart(Heart $sentHeart): self
    {
        if (!$this->sentHearts->contains($sentHeart)) {
            $this->sentHearts[] = $sentHeart;
            $sentHeart->setSender($this);
        }

        return $this;
    }

    public function removeSentHeart(Heart $sentHeart): self
    {
        if ($this->sentHearts->contains($sentHeart)) {
            $this->sentHearts->removeElement($sentHeart);
            // set the owning side to null (unless already changed)
            if ($sentHeart->getSender() === $this) {
                $sentHeart->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Heart[]
     */
    public function getReceivedHearts(): Collection
    {
        return $this->receivedHearts;
    }

    public function addReceivedHeart(Heart $receivedHeart): self
    {
        if (!$this->receivedHearts->contains($receivedHeart)) {
            $this->receivedHearts[] = $receivedHeart;
            $receivedHeart->setRecipient($this);
        }

        return $this;
    }

    public function removeReceivedHeart(Heart $receivedHeart): self
    {
        if ($this->receivedHearts->contains($receivedHeart)) {
            $this->receivedHearts->removeElement($receivedHeart);
            // set the owning side to null (unless already changed)
            if ($receivedHeart->getRecipient() === $this) {
                $receivedHeart->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getSentVisits(): Collection
    {
        return $this->sentVisits;
    }

    public function addSentVisit(Visit $sentVisit): self
    {
        if (!$this->sentVisits->contains($sentVisit)) {
            $this->sentVisits[] = $sentVisit;
            $sentVisit->setVisitor($this);
        }

        return $this;
    }

    public function removeSentVisit(Visit $sentVisit): self
    {
        if ($this->sentVisits->contains($sentVisit)) {
            $this->sentVisits->removeElement($sentVisit);
            // set the owning side to null (unless already changed)
            if ($sentVisit->getVisitor() === $this) {
                $sentVisit->setVisitor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getReceivedVisits(): Collection
    {
        return $this->receivedVisits;
    }

    public function addReceivedVisit(Visit $receivedVisit): self
    {
        if (!$this->receivedVisits->contains($receivedVisit)) {
            $this->receivedVisits[] = $receivedVisit;
            $receivedVisit->setVisited($this);
        }

        return $this;
    }

    public function removeReceivedVisit(Visit $receivedVisit): self
    {
        if ($this->receivedVisits->contains($receivedVisit)) {
            $this->receivedVisits->removeElement($receivedVisit);
            // set the owning side to null (unless already changed)
            if ($receivedVisit->getVisited() === $this) {
                $receivedVisit->setVisited(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of apiToken
     */ 
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Set the value of apiToken
     *
     * @return  self
     */ 
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
