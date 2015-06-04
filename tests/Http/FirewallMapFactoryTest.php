<?php

namespace Symnedi\Security\Tests\Http;

use Mockery;
use PHPUnit_Framework_TestCase;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\Http\FirewallMapInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;
use Symnedi\Security\Http\FirewallMapFactory;


class FirewallMapFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testCreate()
	{
		$firewallMapFactory = $this->createLoadedFirewallMapFactory();
		$firewallMap = $firewallMapFactory->create();
		$this->assertInstanceOf(FirewallMapInterface::class, $firewallMap);
	}


	/**
	 * @return FirewallMapFactoryInterface
	 */
	private function createLoadedFirewallMapFactory()
	{
		$firewallMapFactory = new FirewallMapFactory;

		$requestMatcherMock = Mockery::mock(RequestMatcherInterface::class, [
			'getFirewallName' => 'someFirewall'
		]);
		$firewallMapFactory->addRequestMatcher($requestMatcherMock);

		$firewallHandlerMock = Mockery::mock(FirewallHandlerInterface::class, [
			'getFirewallName' => 'someFirewall'
		]);
		$firewallMapFactory->addFirewallHandler($firewallHandlerMock);

		return $firewallMapFactory;
	}

}
