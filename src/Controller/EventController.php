<?php

namespace App\Controller;

use App\Form\EventCreationType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function event(Request $request)
    {

        $form = $this->createForm(EventCreationType::class, $event);

        $form->handleRequest($request);

        //Verif que le formu n'soit pas vide au submit//
        if ($form->isSubmitted() && $form->isValid()) {
            //recup des données
            //$event = $form->getData();

            //recup de l'entity + table events
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(EventRepository::class);

            //requete de recup de toutes les données formu
            $data = $request->request->all();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'L\'évènement à bien été créé !');
        }

        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
            'form' => $form->createView()
        ]);
    }
}
