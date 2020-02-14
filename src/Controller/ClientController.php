<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Security\UserAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use App\Entity\User;
use App\Form\RegisterClientType;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */

    ////////////////////////////////
    /// Fonctions enregistrement ///
    ////////////////////////////////

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler,
                             UserAuthenticator $authenticator, EmailService $emailService):
    Response {
        $user = new User();
        $form = $this->createForm(RegisterClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du Mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // Set de la date d'enregistrement
            $now = new DateTime();
            $user
                ->setCreatedAt($now)
                ->setToken($this->generateToken())
                ->setActive(0);


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $emailService->register($user);

            $this->addFlash('success', "Votre inscription a bien été prise en compte. Nous vous avons envoyé un email de confirmation. Merci de cliquer sur le lien afin de finaliser votre inscription.");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/register-validate-email", name="register_validate_email")
     */

    ////////////////////////////
    /// Validation des mails ///
    ////////////////////////////

    public function register_validate_email(Request $request, UserRepository $userRepo)
    {
        $error = false;
        // Récupérer GET email - Token
        $email = $request->query->get('email');
        $token = $request->query->get('token');

        $user = $userRepo->findOneBy(array('email' => $email));

        if(!$user){
            $this->addFlash('danger', "Votre adresse email ne correspond à aucun compte.");
        }elseif ($user->getActive() == 1){
            $this->addFlash('danger', "Votre compte a déjà été activé, vous pouvez  vous connecter");
        }elseif($token != $user->getToken()){
            $this->addFlash('danger', "Une erreur est survenue. Contactez notre service commercial.");
        }else{
            $user->setActive(1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "Votre compte a bien été validé, vous pouvez à présent vous connecter.");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('emails/security/register_validate_email.html.twig', [
            'error' => $error
        ]);
    }
    /**
     * @Route("/login", name="app_login")
     */

    ///////////////////////
    /// Fonctions login ///
    ///////////////////////

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // Laisser vide //
    }
    /**
     * @Route("/password-forgotten", name="password_forgotten")
     */

    ///////////////////////////
    /// Fonction mdp oublié ///
    ///////////////////////////

    public function password_forgotten(Request $request, UserRepository $userRepo, EmailService $emailService)
    {
        if($request->isMethod('POST')){
            $email = $request->request->get('email');
            $user = $userRepo->findOneBy(array('email' => $email));

            if(!$user){
                $this->addFlash('danger', "Votre adresse email ne correspond à aucun compte.");
            }else{
                $user->setToken($this->generateToken());
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $link = $this->generateUrl('password_update', ['email' => $user->getEmail(), 'token' => $user->getToken()],UrlGeneratorInterface::ABSOLUTE_URL);
                $emailService->password_forgotten($user, $link);

                $this->addFlash('success', "Nous vous avons envoyé un email contenant le lien pour modifier votre mot de passe");
                return $this->redirectToRoute('password_forgotten', ['send' => 'ok']);
            }
        }

        return $this->render('emails/security/password_forgotten.html.twig', []);
    }


    /**
     * @Route("/password-update", name="password_update")
     */
    public function password_update(Request $request, UserRepository $userRepo,  UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $success = "Votre mot de passe a bien été modifié";

        if(!$user){
            $email = $request->query->get('email');
            $token = $request->query->get('token');
            $user = $userRepo->findOneBy(array('email' => $email));

            if(!$user || $token != $user->getToken()){
                throw new \Exception("Page interdite !");
            }
        }

        $form = $this->createForm(RegisterClientType::class, $user);
        $form
            ->remove('prenom')
            ->remove('nom')
            ->remove('email')
            ->remove('adresse')
            ->remove('ville')
            ->remove('codepostal')
            ->remove('telephone')
            ->remove('agreeTerms')
            ->remove('save')
        ;

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user
                ->setToken($this->generateToken())
                ->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', $success);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('emails/security/password_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateToken()
    {
        return substr(bin2hex(random_bytes(50)), 0, 32);
    }
}
