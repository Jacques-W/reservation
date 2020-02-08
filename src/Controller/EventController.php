<?php

namespace App\Controller;

use App\Form\EventCreationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Event;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function event(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        $form = $this->createForm(EventCreationType::class, $event);

        //Gestion Formulaire + Verification//
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $event = $request->request->all();

            $eventValide = (new Event())
                ->setArtiste($event['event_creation']['artiste'])
                ->setPresentation($event['event_creation']['presentation'])
                ->setDate($event['event_creation']['date'])
                ->setHeure($event['event_creation']['heure'])
                ->setAdresse($event['event_creation']['adresse'])
                ->setType($event['event_creation']['type'])
                ->setTarif($event['event_creation']['tarif'])

            ;
            //gestion + insertion dans la DB
            $em->persist($eventValide);
            $em->flush();
            $this->addFlash('success', 'L\'évènement à bien été ajouté !');
        }

        return $this->render('event/event.html.twig', [
            'controller_name' => 'EventController',
            'form' => $form->createView()
        ]);
    }
}
