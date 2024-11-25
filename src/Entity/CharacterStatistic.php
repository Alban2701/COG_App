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
    
    #[ORM\ManyToOne(inversedBy: 'characterStatistics')]
    #[ORM\Column]
    private ?Statistic $statistic = null;

    #[ORM\ManyToOne(inversedBy: 'characterStatistics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $character = null;

    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatistic(): ?int
    {
        return $this->statistic;
    }

    public function setStatistic(Statistic $statistic): static
    {
        $this->statistic = $statistic;

        return $this;
    }

    public function getCharacterId(): Character
    {
        return $this->character;
    }

    public function setCharacterId(?Character $characterId): static
    {
        $this->character = $characterId;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
