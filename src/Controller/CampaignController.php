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
use App\Entity\User;

class CampaignController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/campaign', name: 'app_campaign_index')]
    #[IsGranted('ROLE_USER')]
    public function read_all(): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
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

    //Consulter une campagne
    #[Route('/campaign/{id}', name: 'app_campaign_read', requirements: ['id' => '\d+'])]
    #[IsGranted(CampaignVoter::CAMPAIGN_EDIT, subject: 'campaign')]
    public function read(Campaign $campaign, Request $request): Response
    {
        $user = $this->getUser();
        // Vérifie si l'utilisateur est connecté et correspond bien à un User
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour consulter cette campagne.');
        }
        
        // Vérifie si l'utilisateur est le maître de jeu de cette campagne
        $isGameMaster = $campaign->getGameMasters()->contains($user);
        // Vérifie si l'utilisateur a un personnage dans cette campagne
        $isPlayer = false;
        foreach ($user->getCharacters() as $character) {
            if ($campaign->getCharacters()->contains($character)) {
                $isPlayer = true;
                break;
            }
        }
        // Si l'utilisateur n'est ni maître de jeu ni joueur, interdit l'accès
        if (!$isGameMaster && !$isPlayer) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette campagne.');
        }
        // Render de la vue pour afficher les détails de la campagne
        return $this->render('campaign/read.html.twig', [
            'campaign' => $campaign,
            'isGameMaster' => $isGameMaster,
            'isPlayer' => $isPlayer,
        ]);
        
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


    #[Route('/campaign/join', name: 'app_campaign_join')]
    #[IsGranted('ROLE_USER')]
    public function join(Request $request, ManagerRegistry $doctrine)
    {
        // Logic for joining a campaign (to be implemented)
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
}
