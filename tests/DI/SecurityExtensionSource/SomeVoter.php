<?php

namespace Symnedi\Security\Tests\DI\SecurityExtensionSource;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class SomeVoter implements VoterInterface
{
    /**
     * @param TokenInterface $token
     * @param mixed $object
     * @param array $attributes
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
    }
}
