<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;


class ClientController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function user(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $request->isMethod('POST');
        $data = $request->request->all();

        $client = (new User())
            ->setEmail($data['email'])
            ->setRoles($data['roles'])
            ->setPassword($data['password'])
            ->setRoles($data['nom'])
            ->setEmail($data['prenom'])
            ->setRoles($data['adresse'])
            ->setEmail($data['codepostal'])
            ->setRoles($data['ville'])
            ->setRoles($data['telephone']);

        $em->persist($client);
        $em->flush();

        return $this->render('client/register.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}
