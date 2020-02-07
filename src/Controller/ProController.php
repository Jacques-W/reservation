<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProController extends AbstractController
{
    /**
     * @Route("/pro", name="pro")
     */
    public function index()
    {
        return $this->render('pro/index.html.twig', [
            'controller_name' => 'ProController',
        ]);
    }
}
