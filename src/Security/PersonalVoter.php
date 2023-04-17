<?php
namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PersonalVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // Define qué atributos admite este voter
        return in_array($attribute, ['VIEW', 'EDIT']) && ($subject instanceof \App\Entity\Personal);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Obtener el usuario autenticado actual
        $user = $token->getUser();

        // Si el usuario no está autenticado, denegar el acceso
        if (!$user) {
            return false;
        }

        // Verificar si el usuario tiene el rol adecuado para acceder a esta función
        if ($attribute === 'VIEW') {
            return $user->isGranted('ROLE_ADMIN');
        } elseif ($attribute === 'EDIT') {
            return $user->getId() === $subject->getOwnerId();
        }

        return false;
    }
}
