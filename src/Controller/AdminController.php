<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/Admin_dashboard.html.twig');
    }



    #[Route('/admin/formations', name: 'admin_formations')]
    public function formations(): Response
    {
        return $this->redirectToRoute('app_formation');
    }
}