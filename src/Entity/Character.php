<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, CharacterStatistic>
     */
    #[ORM\OneToMany(targetEntity: CharacterStatistic::class, mappedBy: 'characterId', orphanRemoval: true)]
    private Collection $characterStatistics;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    /**
     * @var Collection<int, Campaign>
     */
    #[ORM\ManyToMany(targetEntity: Campaign::class, mappedBy: 'characters')]
    private Collection $campaigns;

    public function __construct()
    {
        $this->characterStatistics = new ArrayCollection();
        $this->campaigns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, CharacterStatistic>
     */
    public function getCharacterStatistics(): Collection
    {
        return $this->characterStatistics;
    }

    public function addCharacterStatistic(CharacterStatistic $characterStatistic): static
    {
        if (!$this->characterStatistics->contains($characterStatistic)) {
            $this->characterStatistics->add($characterStatistic);
            $characterStatistic->setCharacterId($this);
        }

        return $this;
    }

    public function removeCharacterStatistic(CharacterStatistic $characterStatistic): static
    {
        if ($this->characterStatistics->removeElement($characterStatistic)) {
            // set the owning side to null (unless already changed)
            if ($characterStatistic->getCharacterId() === $this) {
                $characterStatistic->setCharacterId(null);
            }
        }

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, Campaign>
     */
    public function getCampaigns(): Collection
    {
        return $this->campaigns;
    }

    public function addCampaign(Campaign $campaign): static
    {
        if (!$this->campaigns->contains($campaign)) {
            $this->campaigns->add($campaign);
            $campaign->addCharacter($this);
        }

        return $this;
    }

    public function removeCampaign(Campaign $campaign): static
    {
        if ($this->campaigns->removeElement($campaign)) {
            $campaign->removeCharacter($this);
        }

        return $this;
    }
}