<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampaignController extends AbstractController
{
    #[Route('/campaign', name: 'app_campaign')]
    public function index(): Response
    {
        return $this->render('campaign/index.html.twig', [
            'controller_name' => 'CampaignController',
        ]);
    }
    #[Route('/campaign/new', name: 'app_campaign')]
    public function new(): Response
    {
        return $this->render('campaign/index.html.twig', [
            'controller_name' => 'CampaignController',
        ]);
    }
}
