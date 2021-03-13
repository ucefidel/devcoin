<?php

namespace App\Entity;

use App\Repository\HistorySearchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistorySearchRepository::class)
 */
class HistorySearch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $keyword;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $result;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="researchs")
     * @ORM\JoinColumn(nullable=true)
     */
    private User $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $localization;

    /**
     * HistorySearch constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     * @return $this
     */
    public function setKeyword(string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string $result
     * @return $this
     */
    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocalization(): ?string
    {
        return $this->localization;
    }

    /**
     * @param string $localization
     * @return $this
     */
    public function setLocalization(string $localization): self
    {
        $this->localization = $localization;

        return $this;
    }
}
