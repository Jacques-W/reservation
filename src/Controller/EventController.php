<?php

namespace App\Controller;

use App\Form\EventCreationType;
use App\Form\EventModifType;
use App\Entity\Event;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



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

        $form->handleRequest($request);

        //Verif que le formu n'soit pas vide au submit//
        if ($form->isSubmitted() && $form->isValid()) {
            //recup des données

            //recup de l'entity + table events
            $em = $this->getDoctrine()->getManager();

            //requete de recup de toutes les données formu
            $event = $form->getData();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'L\'évènement à bien été créé !');
        }

        //------------------//
        //gestion des modif//
        //-----------------//


        $formulaireModif = $this->createForm(EventModifType::class);

        $formulaireModif->handleRequest($request);
        if($formulaireModif->isSubmitted() && $formulaireModif->isValid()){
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(Event::class);
            $data = $request->request->all();

            $repo = $repo->findOneBy(['artiste' => $data['event_modif']['artiste']]);

            $repo->setPresentation($data['event_modif']['presentation']);
            $repo->setDate($data['event_modif']['date']);
            $repo->setHeure($data['event_modif']['heure']);
            $repo->setAdresse($data['event_modif']['adresse']);
            $repo->setType($data['event_modif']['type']);
            $repo->settarif($data['event_modif']['tarif']);

            $em->flush();
            $this->addFlash('warning', 'L\'évènement à bien été modifié !');
        }

        return $this->render('event/event.html.twig', [
            'controller_name' => 'EventController',
            'form' => $form->createView(),
            'modif' => $formulaireModif->createView()
        ]);
    }
}
