<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StaffController extends AbstractController
{
    /**
     * @Route("/staff/", name="staff_panel")
     */
    public function staff()
    {
        return $this->render('staff/staff_panel.html.twig', [
            'controller_name' => 'StaffController',
        ]);
    }
}
