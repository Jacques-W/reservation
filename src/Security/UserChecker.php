<?php



namespace App\Security;


use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        // if ($user->isDeleted()) {
        //     throw new AccountDeletedException();
        // }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }


        if ($user->getActive() != 1) {
            throw new CustomUserMessageAuthenticationException("Vous devez activer votre compte en cliquant sur le lien que nous vous avons envoyé par email.");
        }
    }
}