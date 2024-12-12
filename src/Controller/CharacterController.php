<?php

namespace App\Controller;

use App\Form\CharacterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\{User, Character};
use Doctrine\Persistence\ManagerRegistry;

class CharacterController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/character', name: 'app_character_read')]
    #[IsGranted('ROLE_USER')]

    public function read(): Response
    {
        $user = $this->getUser();
        $characters = [];
        if ($user instanceof User) {
            $characters = $user->getCharacters();
        } else {
            // Gérer l'absence ou l'erreur de type
        }
        return $this->render('character/index.html.twig', [
            "characters" => $characters
        ]);
    }


    #[Route('/character/create', name: 'app_character_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout de User comme propriétaire (l'utilisateur actuel) du Personnage
            $character->setUserId($this->getUser());
            // Enregistrement du Personnage
            $em = $this->doctrine->getManager();
            $em->persist($character);
            $em->flush();

            // Rediriger vers la page d'accueil après la création
            return $this->redirectToRoute('home');
        }

        return $this->render('character/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
