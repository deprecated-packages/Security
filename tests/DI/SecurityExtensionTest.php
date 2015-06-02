<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Tests\DI;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symnedi\Security\Core\Authorization\AccessDecisionManagerFactory;
use Symnedi\Security\Tests\DI\SecurityExtensionSource\SomeVoter;


class SecurityExtensionTest extends AbstractSecurityExtensionTestCase
{

	public function testLoadConfiguration()
	{
		$extension = $this->getExtension();
		$extension->loadConfiguration();

		$containerBuilder = $extension->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$accessDecisionManagerDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(AccessDecisionManager::class)
		);
		$this->assertSame(AccessDecisionManager::class, $accessDecisionManagerDefinition->getClass());
	}


	public function testLoadVoters()
	{
		$extension = $this->getExtension();
		$containerBuilder = $extension->getContainerBuilder();

		$extension->loadConfiguration();

		$containerBuilder->addDefinition('someVoter')
			->setClass(SomeVoter::class);
		$containerBuilder->prepareClassList();

		$accessDecisionManagerFactoryDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(AccessDecisionManagerFactory::class)
		);

		$this->assertCount(0, $accessDecisionManagerFactoryDefinition->getSetup());

		$extension->beforeCompile();

		$this->assertCount(2, $accessDecisionManagerFactoryDefinition->getSetup());
	}

}
