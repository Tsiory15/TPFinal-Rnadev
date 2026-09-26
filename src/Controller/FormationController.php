<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormationController extends AbstractController
{
    private ModuleRepository $moduleRepository;
    private CategorieRepository $categorieRepository;
    private FormationRepository $formationRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ModuleRepository $moduleRepository, 
    CategorieRepository $categorieRepository,
    FormationRepository $formationRepository,
     EntityManagerInterface $entityManager)
    {
        $this->moduleRepository = $moduleRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
        $this->entityManager = $entityManager;
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

     #[Route('/formation/{id}/modifier', name: 'modifier_formation_page', methods: ['GET'])]
    public function modifierPage(int $id): Response
    {
        $formation = $this->formationRepository->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation introuvable.');
        }

        $dataModule = $this->moduleRepository->findAll();

        return $this->render('formation/modifier.html.twig', [
            'formation' => $formation,
            'dataModule' => $dataModule,
        ]);
    }

        #[Route('/formation/{id}/modifier', name: 'modifier_formation', methods: ['POST'])]
    public function modifier(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation introuvable.');
        }

        $titre = $request->request->get('title');
        $description = $request->request->get('description');
        $moduleId = $request->request->get('module');

        $module = $this->moduleRepository->find($moduleId);

        $formation->setTitre($titre);
        $formation->setDescription($description);
        $formation->setModule($module);

        $this->entityManager->flush();

        return $this->redirectToRoute('app_ajout_formation');
    }

        #[Route('/formation/{id}/supprimer', name: 'supprimer_formation', methods: ['POST'])]
    public function supprimer(int $id): Response
    {
        $formation = $this->formationRepository->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation introuvable.');
        }

        $this->entityManager->remove($formation);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_ajout_formation');
    }


        #[Route('/module/{id}/modifier', name: 'modifier_module_page', methods: ['GET'])]
    public function modifierModulePage(int $id): Response
    {
        $module = $this->moduleRepository->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module introuvable.');
        }

        $dataCategorie = $this->categorieRepository->findAll();

        return $this->render('module/modifier.html.twig', [
            'module' => $module,
            'dataCategorie' => $dataCategorie,
        ]);
    }


        #[Route('/module/{id}/modifier', name: 'modifier_module', methods: ['POST'])]
    public function modifierModule(int $id, Request $request): Response
    {
        $module = $this->moduleRepository->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Module introuvable.');
        }

        $titre = $request->request->get('title');
        $description = $request->request->get('description');
        $prix = $request->request->getInt('price');

        $tag = $request->request->all('tag');

        $module->setTitre($titre);
        $module->setDescription($description);
        $module->setPrix($prix);

        foreach ($module->getCategorie() as $categorie) {
            $module->removeCategorie($categorie);
        }

        foreach ($tag as $categorieId) {
            $categorie = $this->categorieRepository->find($categorieId);

                if ($categorie) {
                $module->addCategorie($categorie);
             }
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('app_ajout_formation');
    }
}
