<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $fromId;

    /**
     * @ORM\Column(type="integer")
     */
    public $toId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $desciption;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $date;

    /**
     * @ORM\Column(type="boolean")
     */
    public $active;

    /**
     * @ORM\Column(type="boolean")
     */
    public $reqAccepted;

    /**
     * @ORM\Column(type="boolean")
     */
    public $reqDeclined;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromId(): ?int
    {
        return $this->fromId;
    }

    public function setFromId(int $fromId): self
    {
        $this->fromId = $fromId;

        return $this;
    }

    public function getToId(): ?int
    {
        return $this->toId;
    }

    public function setToId(int $toId): self
    {
        $this->toId = $toId;

        return $this;
    }

    public function getDesciption(): ?string
    {
        return $this->desciption;
    }

    public function setDesciption(?string $desciption): self
    {
        $this->desciption = $desciption;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getReqAccepted(): ?bool
    {
        return $this->reqAccepted;
    }

    public function setReqAccepted(bool $reqAccepted): self
    {
        $this->reqAccepted = $reqAccepted;

        return $this;
    }

    public function getReqDeclined(): ?bool
    {
        return $this->reqDeclined;
    }

    public function setReqDeclined(bool $reqDeclined): self
    {
        $this->reqDeclined = $reqDeclined;

        return $this;
    }
}
