<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CampaignRepository::class)]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'campaigns')]
    private Collection $gameMasters;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'campaigns')]
    private Collection $characters;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'campaign')]
    private Collection $posts;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: "create")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: "update")]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private bool $active = false; // Par défaut, les campagnes sont inactives

    
    public function __construct()
    {
        $this->gameMasters = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->posts = new ArrayCollection();
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
    
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * @return Collection<int, User>
     */
    public function getGameMasters(): Collection
    {
        return $this->gameMasters;
    }
    
    public function checkGameMaster(User $user): bool
    {
        return $this->gameMasters->contains($user);
    }
    
    public function addGameMaster(User $gameMaster): static
    {
        if (!$this->gameMasters->contains($gameMaster)) {
            $this->gameMasters->add($gameMaster);
        }
        
        return $this;
    }
    
    public function removeGameMaster(User $gameMaster): static
    {
        $this->gameMasters->removeElement($gameMaster);
        
        return $this;
    }
    
    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }
    
    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
        }
        
        return $this;
    }
    
    public function removeCharacter(Character $character): static
    {
        $this->characters->removeElement($character);
        
        return $this;
    }
    
    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
    
    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCampaign($this);
        }
        
        return $this;
    }
    
    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCampaign() === $this) {
                $post->setCampaign(null);
            }
        }
        
        return $this;
    }
    
    public function getActive(): bool
    {
        return $this->active;
    }
    
    public function setActive(bool $active): static
    {
        $this->active = $active;
    
        return $this;
    }
}
