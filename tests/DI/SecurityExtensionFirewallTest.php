<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Tests\DI;

use Kdyby\Events\EventManager;
use Nette\Utils\AssertionException;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Security\Http\FirewallMap;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Tests\DI\SecurityExtensionFirewallSource\FirewallListener;


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
					'requestMatcher' => '@' . RequestMatcher::class,
					'securityListener' => '@' . FirewallListener::class
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
