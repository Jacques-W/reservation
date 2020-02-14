<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController extends AbstractController
{

    /**
     * @Route("/a/dispatch", name="admin_dispatch")
     */
    public function admin_dispatch()
    {
        if($this->getUser()->hasRole('ROLE_STAFF')){
            return $this->redirectToRoute('staff');
        }else{
            return $this->redirectToRoute('publisher_admin');
        }
    }
    
    /**
     * @Route("/a/", name="publisher_admin")
     */
    public function publisher_admin()
    {

        return $this->render('publisher/publisher_admin.html.twig', [
            'controller_name' => 'PublisherController',
        ]);
    }
}
