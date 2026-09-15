<?php

namespace App\Controller;

use App\Services\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AcceuilController extends AbstractController
{
    private Mailer $mailer;
    public function __construct(Mailer $mailer){
        $this->mailer = $mailer;
    }
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }
    #[Route('/send',name:'send_mail')]
    public function send():Response
    {
        $this->mailer->sendMail();
        return new Response('Email sent');
    }
}
