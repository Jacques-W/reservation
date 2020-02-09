<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EventRepository;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     */
    public function index(EventRepository $eventRepo)
    {


        return $this->render('front/index.html.twig', [
            'event' => $eventRepo->findAll()
        ]);
    }
}
