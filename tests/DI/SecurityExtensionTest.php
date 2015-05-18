<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Tests\DI;

use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Core\Authorization\AccessDecisionManagerFactory;
use Symnedi\Security\DI\SecurityExtension;
use Symnedi\Security\Tests\DI\SecurityExtensionSource\SomeVoter;


class SecurityExtensionTest extends PHPUnit_Framework_TestCase
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

		$this->assertCount(1, $accessDecisionManagerFactoryDefinition->getSetup());
	}


	/**
	 * @return SecurityExtension
	 */
	private function getExtension()
	{
		$extension = new SecurityExtension;
		$extension->setCompiler(new Compiler(new ContainerBuilder), 'compiler');
		return $extension;
	}

}
