<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Tests\DI;

use Kdyby\Events\EventManager;
use Nette\Utils\AssertionException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Http\FirewallMap;
use Symfony\Component\Security\Http\FirewallMapInterface;


class SecurityExtensionFirewallTest extends AbstractSecurityExtensionTest
{

	public function testLoadConfigurationWithNoFirewalls()
	{
		$extension = $this->getExtension();
		$extension->loadConfiguration();

		$containerBuilder = $extension->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$this->assertNull($containerBuilder->getByType(FirewallMapInterface::class));
	}


	public function testLoadConfigurationWithIncorrectParameters()
	{
		$extension = $this->getExtension();
		$extension->setConfig([
			'firewalls' => ['...']
		]);
		$this->setExpectedException(AssertionException::class);
		$extension->loadConfiguration();
	}


	public function testRegisterProperFirewall()
	{
		$extension = $this->getExtension();
		$extension->setConfig([
			'firewalls' => [
				'customMatcher' => [
					'requestMatcher' => '@Symnedi\Security\Tests\DI\SecurityExtensionSource\RequestMatcher',
					'securityListener' => '@Symnedi\Security\Tests\DI\SecurityExtensionSource\FirewallListener'
				]
			]
		]);

		$containerBuilder = $extension->getContainerBuilder();

		$containerBuilder->addDefinition('eventManager')
			->setClass(EventManager::class);

		$extension->loadConfiguration();

		$firewallDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(FirewallMapInterface::class)
		);
		$this->assertSame(FirewallMap::class, $firewallDefinition->getClass());

		$extension->beforeCompile();
		$this->assertCount(1, $firewallDefinition->getSetup());
	}

}
