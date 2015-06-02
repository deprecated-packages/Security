<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use Kdyby\Events\EventManager;
use Symfony\Component\Security\Http\FirewallMap;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Http\FirewallMapFactory;
use Symnedi\Security\Tests\DI\AbstractSecurityExtensionTestCase;
use Symnedi\Security\Tests\DI\SecurityExtension\FirewallSource\FirewallHandler;
use Symnedi\Security\Tests\DI\SecurityExtension\FirewallSource\RequestMatcher;


class FirewallTest extends AbstractSecurityExtensionTestCase
{

	public function testRegisterProperFirewall()
	{
		$extension = $this->getExtension();

		$containerBuilder = $extension->getContainerBuilder();
		$containerBuilder->addDefinition('eventManager')
			->setClass(EventManager::class);

		$containerBuilder->addDefinition('requestMatcher')
			->setClass(RequestMatcher::class);

		$containerBuilder->addDefinition('firewallListener')
			->setClass(FirewallHandler::class);

		$extension->loadConfiguration();

		$containerBuilder->prepareClassList();

		$firewallDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(FirewallMapFactoryInterface::class)
		);
		$this->assertSame(FirewallMapFactory::class, $firewallDefinition->getClass());

		$extension->beforeCompile();
		$this->assertCount(2, $firewallDefinition->getSetup());
	}

}
