<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\ModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormationController extends AbstractController
{
    private ModuleRepository $moduleRepository;
    private CategorieRepository $categorieRepository;
    private FormationRepository $formationRepository;
    public function __construct(ModuleRepository $moduleRepository, CategorieRepository $categorieRepository,FormationRepository $formationRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    #[Route('/formation', name: 'app_formation')]
    public function index(Request $request): Response
    {
        $Mot = $request->query->get('search','');
        $Categ = $request->query->get('categ','');
        $CategByid = $this->categorieRepository->find($Categ);
        $listCateg = $this->categorieRepository->getCateg();
        $limit = 3;
        $page = $request->query->getInt('page','1');
        $listModule = $this->moduleRepository->getModule($Mot,$page,$limit,$Categ);
        $totalItem = count($listModule);
        $totalPage = ceil($totalItem/$limit);
        if($page == $totalPage){
            $page = $totalPage;
        }
        return $this->render('formation/index.html.twig', [
            'listModule'=> $listModule,
            'listCateg' => $listCateg,
            'totalPage' => $totalPage,
            'CurrentPage' => $page,
            'mot' => $Mot,
            'categ' => $Categ,
            'categName' => $CategByid
        ]);
    }
    #[Route('/detail/{id}', name: 'app_detail')]
    public function detail(int $id): Response
    {
        $data = $this->formationRepository->getFormation($id);
        return $this->render('detail/detail.html.twig', [
            'formation' => $data,
        ]);
    }
}
