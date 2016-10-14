<?php

namespace Symnedi\Security\Tests\DI\SecurityExtensionSource;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class SomeVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $object, array $attributes)
    {
    }
}
