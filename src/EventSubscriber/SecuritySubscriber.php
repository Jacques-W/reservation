<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;

class SecuritySubscriber implements EventSubscriberInterface
{

    private $tokenStorage;
    private $router;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $router
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelRequest(object $event)
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user instanceof User) {
                if ($user->getActive() != 1) {
                    $response = new RedirectResponse($this->router->generate('app_logout'));
                    $event->setResponse($response);
                }
            }
        }
    }
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
