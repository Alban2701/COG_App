<?php

namespace App\Security\Voter;

use App\Entity\Campaign;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CampaignVoter extends Voter
{
    // these strings are just invented: you can use anything
    public const CAMPAIGN_VIEW = 'campaign_view';
    public const CAMPAIGN_EDIT = 'campaign_edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CAMPAIGN_VIEW, self::CAMPAIGN_EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Campaign) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Campaign object, thanks to `supports()`
        /** @var Campaign $campaign */
        $campaign = $subject;

        return match ($attribute) {
            self::CAMPAIGN_VIEW => $this->canView($campaign, $user),
            self::CAMPAIGN_EDIT => $this->canEdit($campaign, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(Campaign $campaign, User $user): bool
    {
        return $this->canEdit($campaign, $user) || $this->isPlayerInCampaign($campaign, $user);
    }

    private function canEdit(Campaign $campaign, User $user): bool
    {
        return $campaign->getGameMasters()->contains($user);
    }

    private function isPlayerInCampaign(Campaign $campaign, User $user): bool
    {
        foreach ($user->getCharacters() as $character) {
            if ($campaign->getCharacters()->contains($character)) {
                return true;
            }
        }

        return false;
    }
}
