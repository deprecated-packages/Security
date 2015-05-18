<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Nette\DI\CompilerExtension;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


class SecurityExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
		$this->compiler->parseServices($builder, $services);
	}


	public function beforeCompile()
	{
		$this->loadAccessDecisionManagerFactory();
	}


	private function loadAccessDecisionManagerFactory()
	{
		$builder = $this->getContainerBuilder();
		$builder->prepareClassList();

		$accessDecisionManagerFactoryDefinition = $builder->getDefinition(
			$builder->getByType(AccessDecisionManagerFactoryInterface::class)
		);

		foreach ($builder->findByType(VoterInterface::class) as $voterDefinition) {
			$accessDecisionManagerFactoryDefinition->addSetup('addVoter', ['@' . $voterDefinition->getClass()]);
		}
	}

}
