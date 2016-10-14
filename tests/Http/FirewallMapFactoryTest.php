<?php

namespace Symnedi\Security\Tests\Http;

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
        $firewallMapFactory = new FirewallMapFactory();

        $requestMatcherMock = $this->prophesize(RequestMatcherInterface::class);
        $requestMatcherMock->getFirewallName()->willReturn('someFirewall');
        $firewallMapFactory->addRequestMatcher($requestMatcherMock->reveal());

        $firewallHandlerMock = $this->prophesize(FirewallHandlerInterface::class);
        $firewallHandlerMock->getFirewallName()->willReturn('someFirewall');
        $firewallMapFactory->addFirewallHandler($firewallHandlerMock->reveal());

        return $firewallMapFactory;
    }
}
