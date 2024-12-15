<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository): Response
    {
        $counts = $postRepository->nbAllSubjects(); 

        return $this->render('admin/index.html.twig', [
            'counts' => $counts,
        ]);
    }
    #[Route('/category/add', name: 'category_add')]
    public function addCategory(Request $request, ManagerRegistry $doctrine): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
