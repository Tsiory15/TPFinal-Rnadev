<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailController extends AbstractController
{
    public FormationRepository $fr;
    public function __construct(FormationRepository $fr)
    {
        $this->fr = $fr;
    }
    #[Route('/detail/{id}', name: 'app_detail')]
    public function index(int $id): Response
    {
        $data = $this->fr->getFormation($id);
        return $this->render('detail/index.html.twig', [
            'formation' => $data,
        ]);
    }
}
