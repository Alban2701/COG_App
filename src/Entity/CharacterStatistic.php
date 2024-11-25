<?php

namespace App\Entity;

use App\Repository\CharacterStatisticRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterStatisticRepository::class)]
class CharacterStatistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $stat_id = null;

    #[ORM\ManyToOne(inversedBy: 'characterStatistics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $characterId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatId(): ?int
    {
        return $this->stat_id;
    }

    public function setStatId(int $stat_id): static
    {
        $this->stat_id = $stat_id;

        return $this;
    }

    public function getCharacterId(): Character
    {
        return $this->characterId;
    }

    public function setCharacterId(?Character $characterId): static
    {
        $this->characterId = $characterId;

        return $this;
    }
}
