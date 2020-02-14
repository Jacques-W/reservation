<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\ReservationType;
use App\Repository\ReservationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Knp\Snappy\Pdf;

class ReservationsController extends AbstractController
{
    /**
     * @Route("/reservation/{id}", name="reservation")
     */
    public function reservation($id, Request $request, ReservationsRepository $reservationsRepo)
    {
        $em = $this->getDoctrine()->getManager();
        $eventrepo = $em->getRepository(Event::class);
        $user = $this->getUser();
        $event = $eventrepo->find($id);

        if($request->isMethod('POST')){
            $resaids = $request->request->get('reservations');

            $reservations = $reservationsRepo->findBy(['id'=> $resaids]);

            foreach($reservations as $reservation){
            $reservation->setDatereservation(new \DateTime());
            $reservation->setUser($user);
            }

            $em->flush();
            return $this->redirectToRoute('accueil');
        }

        return $this->render('reservations/reservation.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier()
    {

        return $this->render('reservations/panier.html.twig', []);
    }

    //////////////////////
    /// Generation PDF ///
    //////////////////////

    /**
     * @Route("/pdf", name="_pdf")
     * @return Response
     */

    public function pdfAction(\Knp\Snappy\Pdf $snappy)
    {
        $html = $this->renderView("user/user_panel.html.twig", [
            "title" => "Votre Facture"
        ]);

        $filename = "custom_pdf_from_twig";

        return new Response(
            $snappy->generateFromHtml($html, 'pdflol.pdf'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            ]
        );
    }
}
