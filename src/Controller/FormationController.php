<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormationController extends AbstractController
{
    private ModuleRepository $ModuleRepository;
    private CategorieRepository $CategorieRepository;
    public function __construct(ModuleRepository $ModuleRepository, CategorieRepository $CategorieRepository)
    {
        $this->ModuleRepository = $ModuleRepository;
        $this->CategorieRepository = $CategorieRepository;
    }
    #[Route('/formation', name: 'app_formation')]
    public function index(Request $request): Response
    {
        $Mot = $request->request->get('search','');
        $listCateg = $this->CategorieRepository->getCateg();
        $limit = 5;
        $page = $request->query->getInt('page','1');
        $listModule = $this->ModuleRepository->getModule($Mot,$page,$limit);
        $totalItem = count($listModule);
        $totalPage = ceil($totalItem/$limit);
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'listModule'=> $listModule,
            'listCateg' => $listCateg,
            'totalItem' => $totalPage,
            'CurrentPage' => $page
        ]);
    }
}
