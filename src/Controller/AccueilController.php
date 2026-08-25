<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return new Response('
            <h1>Bienvenue dans la plateforme de Formation !</h1>
            <p>vous êtes connecté.</p>
            <a href="/deconnexion">se déconnecter</a>
        ');
    }
}