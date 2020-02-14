<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\EmailService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Response;

class ProController extends AbstractController
{
    /**
     * @Route("/registerPro", name="app_registerPro")
     */
    public function registerPro(Request $request, UserRepository $userRepo, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator,
        EmailService $emailService): Response{
            $user = new User();
            $form = $this->createForm(ProType::class, $user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $now = new DateTime();
                $user
                    ->setCreatedAt($now)
                    ->setRoles(["ROLE_PRO"])
                    ->setToken($this->generateToken())
                    ->setActive(0);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $emailService->register($user);

                $this->addFlash('success', 'Votre inscription a bien été prise en compte. Nous vous avons envoyé un email de confirmation. Merci de cliquer sur le lien afin de finaliser votre inscription.');

                return $this->redirectToRoute('app_login');
            }

        return $this->render('pro/registerPro.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pro/", name="pro_panel")
     */
    public function pro()
    {

        return $this->render('pro/pro_panel.html.twig', [
            'controller_name' => 'ProController',
        ]);
    }
    private function generateToken()
    {
        return substr(bin2hex(random_bytes(50)), 0, 32);
    }
}
