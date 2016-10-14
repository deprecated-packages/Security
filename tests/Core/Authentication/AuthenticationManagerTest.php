<?php

declare(strict_types=1);

namespace Symnedi\Security\Tests\Core\Authentication;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symnedi\Security\Core\Authentication\AuthenticationManager;

final class AuthenticationManagerTest extends TestCase
{
    public function testCase()
    {
        $tokenMock = $this->prophesize(TokenInterface::class);
        $authenticationManager = new AuthenticationManager();

        $resolvedToken = $authenticationManager->authenticate($tokenMock->reveal());
        $this->assertSame($resolvedToken, $tokenMock->reveal());
    }
}
