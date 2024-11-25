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
    public function __construct(private ManagerRegistry $doctrine)
    {

    }
    
    #[Route('/campaign', name: 'app_campaign_read')]
    #[IsGranted('ROLE_USER')]
    public function read(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $user = $this->getUser();
        $campaignsGM = $user->getCampaigns();
        $campaignsCh = [];
        foreach ($user->getCharacters() as $character) {
            foreach($character->getCampaigns() as $campaign) {
                array_push($campaignsCh, $campaign);
            }
        }

        return new Response(json_encode(["campaignsGM" => $campaignsGM, "campaignsCh" => $campaignsCh]));
    }
    
    
    //Editer une campagne
    #[Route('/campaign/{id}/update', name: 'app_campaign_update')]
    #[IsGranted(CampaignVoter::CAMPAIGN_EDIT, 'campaign')]
    public function update(Campaign $campaign): Response
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

    #[Route('/campaign/{id}', name: 'app_campaign')]
    #[IsGranted(CampaignVoter::CAMPAIGN_VIEW, 'campaign')]
    // check for "view" access: calls all voters
    public function readDetails(): Response
    {
        return $this->render('campaign/index.html.twig', [
            'controller_name' => 'CampaignController',
        ]);
    }  
    

    #[Route('/campaign/create', name: 'app_campaign_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $campaign->addGameMaster($this->getUser());
            // $campaign->setActive(false);
            $em = $this->doctrine->getManager();
            $em->persist($campaign);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        
        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/campaign/join', name: 'app_campaign_join')]
    #[IsGranted('ROLE_USER')]
    public function join(Request $request, ManagerRegistry $doctrine)
    {
        
    }

}
