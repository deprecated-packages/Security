<?php

namespace Symnedi\Security\Tests\Core\Authentication;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symnedi\Security\Core\Authentication\AuthenticationManager;

class AuthenticationManagerTest extends PHPUnit_Framework_TestCase
{
    public function testCase()
    {
        $tokenMock = $this->prophesize(TokenInterface::class);
        $authenticationManager = new AuthenticationManager();

        $resolvedToken = $authenticationManager->authenticate($tokenMock->reveal());
        $this->assertSame($resolvedToken, $tokenMock->reveal());
    }
}
