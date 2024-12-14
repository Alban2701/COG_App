<?php

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\User;

class CampaignAccessService
{
    public function calculateAccessDetails(Campaign $campaign, User $user): array
    {
        $isGameMaster = $campaign->getGameMasters()->contains($user);
        $isPlayer = false;

        foreach ($user->getCharacters() as $character) {
            if ($campaign->getCharacters()->contains($character)) {
                $isPlayer = true;
                break;
            }
        }

        return ['isGameMaster' => $isGameMaster, 'isPlayer' => $isPlayer];
    }
}
