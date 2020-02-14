<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(EventRepository $eventRepo, Request $request)
    {
        $events = $eventRepo->createQueryBuilder('e')
            ->where('e.date > :date')->setParameter('date', new \DateTime())
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
        
        $news = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            $this->addFlash('success', 'Votre inscription a la newsletter a bien été prise en compte.');
        }
        
        return $this->render('front/accueil.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
        ]);
    }


    public function header($currentPage)
    {
        return $this->render('front/header.html.twig', [
            'currentPage' => $currentPage,
        ]);
    }

    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function change_locale($locale, Request $request, UrlMatcherInterface $matcher)
    {
        $referer = $request->headers->get('referer');
        $referer_relative = parse_url($referer, PHP_URL_PATH);
        $route = $matcher->match($referer_relative)['_route'];

        if($route && $route != 'change_locale'){
            return $this->redirectToRoute($route, ['_locale' => $locale]);
        }else{
            return $this->redirectToRoute('accueil', ['_locale' => $locale]);
        }
    }
}
