<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EventRepository;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     */
    public function accueil(EventRepository $eventRepo)
    {


        return $this->render('front/index.html.twig', [
            'event' => $eventRepo->findAll()
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


        //$session->set('_locale', $locale);
        // return $this->redirectToRoute('accueil', [

        $referer = $request->headers->get('referer');
        $referer_relative = parse_url($referer, PHP_URL_PATH);
        $route = $matcher->match($referer_relative)['_route'];
        if ($route && $route != 'change_locale') {
            return $this->redirectToRoute($route, ['_locale' => $locale]);
        } else {
            return $this->redirectToRoute('accueil', ['_locale' => $locale]);
        }
    }
}
