<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     */
    public function index(EventRepository $event)
    {
        return $this->render('front/index.html.twig', [
            'events' => $event->findAll()
        ]);
    }
}
