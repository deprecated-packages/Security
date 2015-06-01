<?php

namespace Symnedi\Security\Tests\Http;

use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Contract\Http\FirewallListenerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
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

		$firewallListenerMock = Mockery::mock(RequestMatcherInterface::class, [
			'getFirewallName' => 'someFirewall'
		]);
		$firewallMapFactory->addRequestMatcher($firewallListenerMock);

		$requestMatcherMock = Mockery::mock(FirewallListenerInterface::class, [
			'getFirewallName' => 'someFirewall'
		]);
		$firewallMapFactory->addFirewallListener($requestMatcherMock);

		return $firewallMapFactory;
	}

}
