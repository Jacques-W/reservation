<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route("/account", name="account")
     */
    public function account()
    {
        if($this->getUser()->hasRole('ROLE_STAFF')){
            return $this->redirectToRoute('accueil');
        }if($this->getUser()->hasRole('ROLE_PRO')){
            return $this->redirectToRoute('accueil');
        }else{
            return $this->redirectToRoute('accueil');
        }
    }
}