<?php

namespace App\Controller;

use App\Entity\{Campaign, CampaignInvitation, User};
use App\Form\{CampaignType, CampaignInvitationType};
use App\Security\Voter\CampaignVoter;
use App\Service\CampaignAccessService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use RandomLib\Factory;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


class CampaignController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
    #[IsGranted(CampaignVoter::CAMPAIGN_VIEW, subject: 'campaign')]
    public function read(Campaign $campaign, CampaignAccessService $accessService): Response
    {
        $user = $this->getUser();
        $accessDetails = $accessService->calculateAccessDetails($campaign, $user);

        return $this->render('campaign/read.html.twig', [
            'campaign' => $campaign,
            'isGameMaster' => $accessDetails['isGameMaster'],
            'isPlayer' => $accessDetails['isPlayer'],
        ]);
    }


    // Editer une campagne
    #[Route('/campaign/{id}/update', name: 'app_campaign_update')]
    #[IsGranted(CampaignVoter::CAMPAIGN_EDIT, subject: 'campaign')]
    public function update(Campaign $campaign, Request $request): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request); // Permet au formulaire de traiter la requête

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($campaign);
            $this->entityManager->flush();

            // Redirection vers la route "read" après la validation
            return $this->redirectToRoute('app_campaign_read', [
                'id' => $campaign->getId(),
            ]);
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function generateInvitationToken(): string
    {
        $factory = new Factory;
        $generator = $factory->getMediumStrengthGenerator();
        return $generator->generateString(64); // Longueur du token
    }

    //les invitations ne sont pas encore fonctionnelle, ne pas prendre en compte pour la note

    // #[Route('/campaign/{id}/invite', name: 'app_campaign_invite')]
    // public function invite(Campaign $campaign, Request $request, MailerInterface $mailer): Response
    // {
    //     $form = $this->createForm(CampaignInvitationType::class); // Formulaire d'invitation (email)
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $email = $form->getData()['email']; // Récupère l'email du joueur à inviter
    //         $token = $this->generateInvitationToken(); // Génère un token unique

    //         // Envoi de l'email d'invitation
    //         $emailMessage = (new Email())
    //             ->from('noreply@cog-app.com') // Adresse de l'expéditeur
    //             ->to($email) // L'email du joueur à inviter
    //             ->subject('Invitation à rejoindre la campagne')
    //             ->html('<p>Voici votre lien d\'invitation : <a href="' . $this->generateUrl('app_campaign_join', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL) . '">Rejoindre la campagne</a></p>');

    //         $mailer->send($emailMessage);

    //         // Sauvegarder le token dans la base de données (lié à la campagne et à l'invité)
    //         $invitation = new CampaignInvitation(); // Model Invitation à créer
    //         $invitation->setCampaign($campaign);
    //         $invitation->setEmail($email);
    //         $invitation->setToken($token);

    //         $this->entityManager->persist($invitation);
    //         $this->entityManager->flush();

    //         // Rediriger vers la page de la campagne
    //         return $this->redirectToRoute('app_campaign_read', ['id' => $campaign->getId()]);
    //     }

    //     return $this->render('campaign/invite.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/invite/{token}', name: 'app_campaign_join', methods: ['GET'])]
    // public function joinCampaign(string $token): Response 
    // {
    //     // Logique de vérification de l'invitation
    //     // (valider le token, vérifier si l'invitation existe, etc.)

    //     // Récupérer l'invitation avec le token (tu devras probablement l'ajouter dans ton repository)
    //     $invitation = $this->entityManager->getRepository(CampaignInvitation::class)->findOneBy(['token' => $token]);

    //     if (!$invitation) {
    //         // Si l'invitation est introuvable, afficher un message d'erreur
    //         return $this->createNotFoundException('L\'invitation est invalide ou a expiré.');
    //     }

    //     // Vérifier si l'utilisateur est déjà connecté
    //     if ($this->getUser()) {
    //         // Si l'utilisateur est connecté, l'ajouter à la campagne et rediriger vers la page de la campagne
    //         // Ici, tu peux avoir une méthode qui ajoute l'utilisateur à la campagne
    //         $this->addUserToCampaign($invitation, $this->getUser());

    //         return $this->redirectToRoute('app_campaign_view', ['id' => $invitation->getCampaign()->getId()]);
    //     }
    // }


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
            $this->entityManager->persist($campaign);
            $this->entityManager->flush();

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
