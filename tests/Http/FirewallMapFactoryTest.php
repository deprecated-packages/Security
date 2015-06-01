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

	/**
	 * @var FirewallMapFactoryInterface
	 */
	private $firewallMapFactory;


	protected function setUp()
	{
		$this->firewallMapFactory = new FirewallMapFactory;
	}


	public function testCreate()
	{
		$firewallListenerMock = Mockery::mock(RequestMatcherInterface::class, [
			'getFirewallName' => 'someFirewall'
		]);
		$this->firewallMapFactory->addRequestMatcher($firewallListenerMock);

		$requestMatcherMock = Mockery::mock(FirewallListenerInterface::class, [
			'getFirewallName' => 'someFirewall'
		]);
		$this->firewallMapFactory->addFirewallListener($requestMatcherMock);

		$this->assertInstanceOf(FirewallMapInterface::class, $this->firewallMapFactory->create());
	}

}
