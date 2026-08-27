<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\AdminFormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminFormationController extends AbstractController
{
    #[Route('/admin/formations', name: 'admin_formations')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        FormationRepository $formationRepository
    ): Response
    {
        $formation = new Formation();

        $form = $this->createForm(
            AdminFormationType::class,
            $formation
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_formations');
        }

        $formations = $formationRepository->findAll();

        return $this->render('admin/Crud_formations.html.twig', [
            'form' => $form->createView(),
            'formations' => $formations,
        ]);
    }

    #[Route('/admin/formations/{id}/modifier', name: 'admin_formation_modifier')]
    public function modifier(
        Formation $formation,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(AdminFormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('admin_formations');
        }

        return $this->render('admin/modifier_admin_formation.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }

    #[Route('/admin/formations/{id}/supprimer', name: 'admin_formation_supprimer', methods: ['POST'])]
    public function supprimer(
        Formation $formation,
        EntityManagerInterface $entityManager
        ): Response
        {
        $entityManager->remove($formation);
        $entityManager->flush();
        return $this->redirectToRoute('admin_formations');
        }


}
