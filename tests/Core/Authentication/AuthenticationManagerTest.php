<?php

namespace Symnedi\Security\Tests\Core\Authentication;

use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symnedi\Security\Core\Authentication\AuthenticationManager;


class AuthenticationManagerTest extends PHPUnit_Framework_TestCase
{

	public function testCase()
	{
		$tokenMock = Mockery::mock(TokenInterface::class);
		$authenticationManager = new AuthenticationManager;

		$resolvedToken = $authenticationManager->authenticate($tokenMock);
		$this->assertSame($resolvedToken, $tokenMock);
	}

}
