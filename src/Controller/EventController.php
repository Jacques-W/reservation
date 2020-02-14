<?php

namespace App\Controller;

use App\Form\EventCreationType;
use App\Form\EventModifType;
use App\Entity\Event;
use App\Entity\Reservations;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class EventController extends AbstractController
{
    /**
     * @Route("/staff/eventCreate/{id}", name="event")
     */
    public function event($id, Request $request, EventRepository $eventRepo)
    {
        if($id){
            $event = $eventRepo->find($id);
            $nouveau = false;
        }else{
            $event = new Event();
            $nouveau = true;
        }
        $form = $this->createForm(EventCreationType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // ici commence le code pour uploader une image
            $file= $event->getImage();
            $fileName = md5(uniqid()). ' . ' .$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

            } catch (FileException $e) {
                // throw $e;

                //ATTENTION! IL FAUT METTRE LA LIGNE DE COMMANDE CI-DESSOUS
                // DANS LE FICHIER SERVICES.YAML SE TROUVANT DANS LE REPERTOIRE CONFIG
                //JUSTE AU DESSUS DE 'SERVICE:'
                //PS:NOUBLIES PAS DE CREER LES REPERTOIRES UPLOADS ET IMAGES DANS PUBLIC
                // parameters:
                // images_directory: '%kernel.project_dir%/public/uploads/images'

            }//Ici se termine le code pour uploader une image

            $em = $this->getDoctrine()->getManager();

            $event = $form->getData();
            $nbdeplace = 15;

            for($i=1; $i <= $nbdeplace; $i++){
              $reservation = (new Reservations())
                ->setEvent($event)
                ->setPlace($i);

              $em->persist($reservation);
            }

            $em->persist($event);
            $em->flush();

            $this->addFlash("success", "L'évènement à bien été ". ($nouveau ? 'créé' : 'modifié') ." !");
        }

        $action = $request->query->get('action');
        if($action == 'delete'){
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();

            $this->addFlash('danger', "L'article a bien été supprimé.");
            return $this->redirectToRoute('accueil');
        }

        return $this->render('event/event.html.twig', [
            'form' => $form->createView(),
            'nouveau' => $nouveau
        ]);
    }




 /**
     * @Route("/enventSingle/{id}", name="eventSingle")
     */
    public function eventSingle($id, Request $request)
    {

        // On récupère l' ArticlesRepository
        $em = $this->getDoctrine()->getManager();
        $eventsRepo = $em->getRepository(Event::class);

        // On récupère l'article, en fonction de l'ID qui est dans l'URL
        $event= $eventsRepo->find($id);

        if(!$event){
            $this->addFlash('danger', "L'article demandé n'a pas été trouvé.");
            return $this->redirectToRoute('eventSingle', ['id' => $event->getId()]);
        }

        return $this->render('event/eventSingle.html.twig',[
        'event' => $event    
        ]);
    }
    /**
     * @Route("/user/commandes", name="commandes")
     */
    public function commandes(EventRepository $eventRepo)
    {
        $events = $eventRepo->createQueryBuilder('c')
            ->where('c.date > :date')->setParameter('date', new \DateTime())
            ->orderBy('c.date', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('user/commandes.html.twig', [
            'events' => $events
        ]);
    }
}