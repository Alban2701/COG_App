<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Form\CampaignType;
use App\Security\Voter\CampaignVoter;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CampaignController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/campaign', name: 'app_campaign_read')]
    #[IsGranted(CampaignVoter::CAMPAIGN_VIEW, subject: 'campaign')]
    public function read(Request $request): Response
    {
        $user = $this->getUser();
        $campaignsGM = $user->getCampaigns();
        $campaignsCh = [];
        foreach ($user->getCharacters() as $character) {
            foreach ($character->getCampaigns() as $campaign) {
                array_push($campaignsCh, $campaign);
            }
        }

        return new Response(json_encode(["campaignsGM" => $campaignsGM, "campaignsCh" => $campaignsCh]));
    }


    //Editer une campagne
    #[Route('/campaign/{id}/update', name: 'app_campaign_update')]
    #[IsGranted(CampaignVoter::CAMPAIGN_EDIT, subject: 'campaign')]
    public function update(Campaign $campaign, Request $request): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($campaign);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/campaign/create', name: 'app_campaign_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout du GameMaster (l'utilisateur actuel) à la campagne
            $campaign->addGameMaster($this->getUser());
            // Enregistrement de la campagne
            $em = $this->doctrine->getManager();
            $em->persist($campaign);
            $em->flush();

            // Rediriger vers la page d'accueil après la création
            return $this->redirectToRoute('home');
        }

        return $this->render('campaign/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/campaign/{id}', name: 'app_campaign_truc')]
    #[IsGranted(CampaignVoter::CAMPAIGN_VIEW, subject: 'campaign')]
    public function readDetails(Campaign $campaign): Response
    {
        return $this->render('campaign/index.html.twig', [
            'campaign' => $campaign,
            'controller_name' => 'CampaignController',
        ]);
    }

    #[Route('/campaign/join', name: 'app_campaign_join')]
    #[IsGranted('ROLE_USER')]
    public function join(Request $request, ManagerRegistry $doctrine)
    {
        // Logic for joining a campaign (to be implemented)
    }
}
