<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;


class ClientController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function user()
    {
        $client = new User();
        $form = $this->createForm(RegisterClientType::class, $client);
        // $em = $this->getDoctrine()->getManager();
        // $request->isMethod('POST');
        // $data = $request->request->all();

        // $client = (new User())
        //     ->setEmail($data['email'])
        //     ->setPassword($data['password'])
        //     ->setNom($data['nom'])
        //     ->setPrenom($data['prenom'])
        //     ->setAdresse($data['adresse'])
        //     ->setCodepostal($data['codepostal'])
        //     ->setVille($data['ville'])
        //     ->setTelephone($data['telephone']);

        //     $em->persist($client);
        //     $em->flush();

        return $this->render('client/register.html.twig', [
            'form' => $form->createview(),

        ]);
    }
}
