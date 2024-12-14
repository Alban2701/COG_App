<?php

namespace App\Entity;

use App\Repository\CampaignInvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CampaignInvitationRepository::class)]
class CampaignInvitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'e-mail est obligatoire.")]
    #[Assert\Email(message: "Veuillez fournir un e-mail valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 64)]
    private ?string $token = null;

    #[ORM\ManyToOne(targetEntity: Campaign::class, inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campaign $campaign = null;
    
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;
    
    public function __construct()
    {
        // Remplir createdAt automatiquement lors de la création de l'entité
        $this->createdAt = new \DateTimeImmutable(); // Date actuelle
    }

    //Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): static
    {
        $this->campaign = $campaign;

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
}
