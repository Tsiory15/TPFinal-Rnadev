<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormationController extends AbstractController
{
    private ModuleRepository $mr;
    private CategorieRepository $cr;
    public function __construct(ModuleRepository $mr, CategorieRepository $cr)
    {
        $this->mr = $mr;
        $this->cr = $cr;
    }
    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        $listCateg = $this->cr->getCateg();
        $listModule = $this->mr->getModule();
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'listModule'=> $listModule,
            'listCateg' => $listCateg
        ]);
    }
}
