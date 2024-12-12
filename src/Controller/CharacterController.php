<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController extends AbstractController
{
    #[Route('/character', name: 'app_character_read')]
    #[IsGranted('ROLE_USER')]

    public function read(): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            // Accéder aux méthodes spécifiques de User
            // $campaignsGM = [];
            $campaignsCh = [];
            $campaignsGM = $user->getCampaigns();
            foreach ($user->getCharacters() as $character) {
                foreach ($character->getCampaigns() as $campaign) {
                    array_push($campaignsCh, $campaign);
                }
            }
        } else {
            // Gérer l'absence ou l'erreur de type
        }
        // dd($campaignsGM);
        return $this->render('campaign/index.html.twig', [
            'campaignsGM' => $campaignsGM,
            'campaignsCh' => $campaignsCh,
        ]);
    }
}
