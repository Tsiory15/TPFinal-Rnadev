<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Module;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class AjoutFormationController extends AbstractController
{
    private FormationRepository $formationRepository;
    private ModuleRepository $moduleRepository;
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;
    private CategorieRepository $categorieRepository;
    public function __construct(FormationRepository $formationRepository,ModuleRepository $moduleRepository,EntityManagerInterface $entityManager,SluggerInterface $slugger,CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->moduleRepository = $moduleRepository;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->categorieRepository = $categorieRepository;
    }
    #[Route('/ajout/formation', name: 'app_ajout_formation')]
    public function index(): Response
    {
        $dataFormation = $this->formationRepository->findAll();
        $dataModule = $this->moduleRepository->findAll();
        $dataCategorie = $this->categorieRepository->findAll();
        return $this->render('ajout_formation/index.html.twig', [
            'controller_name' => 'AjoutFormationController',
            'dataFormation' => $dataFormation,
            'dataModule' => $dataModule,
            'dataCategorie' => $dataCategorie
        ]);
    }
    #[Route('/addFormation',name:'add_formation',methods:['POST'])]
    public function addFormation(Request $request):Response
    {
        $titre = $request->request->get('title');
        $description = $request->request->get('description');
        $module = $this->moduleRepository->find($request->request->get('module'));

        $images = $request->files->get('image');
        $imageName = pathinfo($images->getClientOriginalName(),PATHINFO_FILENAME);
        $safeImageName = $this->slugger->slug($imageName);
        $newImageName = $safeImageName.'-'.uniqid().'.'.$images->guessExtension();

        $videos = $request->files->get('videos');
        $videoName = pathinfo($videos->getClientOriginalName(),PATHINFO_FILENAME);
        $safeVideoName = $this->slugger->slug($videoName);
        $newVideoName = $safeVideoName.'-'.uniqid().'.'.$videos->guessExtension();

        $fichier = $request->files->get('fichiers');
        $fichierName = pathinfo($fichier->getClientOriginalName(),PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($fichierName);
        $newFileName = $safeFileName.'-'.uniqid().'.'.$fichier->guessExtension();


        $formation = new Formation;
        $formation->setTitre($titre);
        $formation->setDescription($description);
        $formation->setImages($newImageName);
        $formation->setVideos($newVideoName);
        $formation->setFichiers($newFileName);
        $formation->setModule($module);

        try {
            $images->move(
                $this->getParameter('images_directory'),
                $newImageName
            );
            $videos->move(
                $this->getParameter('videos_directory'),
                $newVideoName
            );
            $fichier->move(
                $this->getParameter('file_directory'),
                $newFileName
            );
        } catch (\Throwable $th) {
            throw $th;
        }

        $this->entityManager->persist($formation);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_ajout_formation');
    }
    #[Route('/addModule',name:'add_module',methods:['POST'])]
    public function addModule(Request $request):Response
    {
        $tag = $request->request->all('tag');
        $titre = $request->request->get('title');
        $description = $request->request->get('description');
        $price = $request->request->getString('price','0');
        $image = $request->files->get('mon_image');
        $imageName = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
        $imageExtension = $image->guessExtension();
        $safeName = $this->slugger->slug($imageName);
        $newName = $safeName.'-'.uniqid().'.'.$imageExtension;

        $module = new Module;
        $module->setTitre($titre);
        $module->setDescription($description);
        $module->setPrix($price);
        $module->setImages($newName);
        foreach ($tag as $value) {
            $module->addCategorie($this->categorieRepository->find($value));
        }
        

        $this->entityManager->persist($module);
        $this->entityManager->flush();

        try {
            $image->move(
                $this->getParameter('images_directory'),
                $newName
            );
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->redirectToRoute('app_ajout_formation'); 
    }
}
