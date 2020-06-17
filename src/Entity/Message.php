<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"}, inversedBy="sendedMessages")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $transmitter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"}, inversedBy="receivedMessages")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $recipient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0}))
     */
    private $open_transmitter = false;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0}))
     */
    private $open_recipient = false;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0}))
     */
    private $trash_transmitter = false;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0}))
     */
    private $trash_recipient = false;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $liaison = false;

    public function isOwner(User $user)
    {
        return ($this->getTransmitter() === $user || $this->getRecipient() === $user );
    }

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTransmitter(): ?User
    {
        return $this->transmitter;
    }

    public function setTransmitter(?User $transmitter): self
    {
        $this->transmitter = $transmitter;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOpenTransmitter(): ?bool
    {
        return $this->open_transmitter;
    }

    public function setOpenTransmitter(bool $open_transmitter): self
    {
        $this->open_transmitter = $open_transmitter;

        return $this;
    }

    public function getOpenRecipient(): ?bool
    {
        return $this->open_recipient;
    }

    public function setOpenRecipient(bool $open_recipient): self
    {
        $this->open_recipient = $open_recipient;

        return $this;
    }

    public function getTrashTransmitter(): ?bool
    {
        return $this->trash_transmitter;
    }

    public function setTrashTransmitter(bool $trash_transmitter): self
    {
        $this->trash_transmitter = $trash_transmitter;

        return $this;
    }

    public function getTrashRecipient(): ?bool
    {
        return $this->trash_recipient;
    }

    public function setTrashRecipient(bool $trash_recipient): self
    {
        $this->trash_recipient = $trash_recipient;

        return $this;
    }

    public function getLiaison(): ?bool
    {
        return $this->liaison;
    }

    public function setLiaison(bool $liaison): self
    {
        $this->liaison = $liaison;

        return $this;
    }

}
