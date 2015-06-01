<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Kdyby\Events\DI\EventsExtension;
use Kdyby\Events\EventManager;
use Nette\DI\CompilerExtension;
use Nette\DI\MissingServiceException;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Contract\Http\FirewallListenerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;
use Symnedi\Security\EventSubscriber\FirewallSubscriber;


class SecurityExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);
	}


	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$this->loadAccessDecisionManagerFactoryWithVoters();

		if ($containerBuilder->findByType(FirewallListenerInterface::class)) {
			$this->loadFirewallMap();
		}
	}


	private function loadAccessDecisionManagerFactoryWithVoters()
	{
		$this->loadMediator(AccessDecisionManagerFactoryInterface::class, VoterInterface::class, 'addVoter');
	}


	private function loadFirewallMap()
	{
		$this->validateEventDispatcherPresence();

		$this->loadMediator(EventManager::class, FirewallSubscriber::class, 'addEventSubscriber');

		$this->loadMediator(FirewallMapFactoryInterface::class, FirewallListenerInterface::class, 'addFirewallListener');
		$this->loadMediator(FirewallMapFactoryInterface::class, RequestMatcherInterface::class, 'addRequestMatcher');
	}


	private function validateEventDispatcherPresence()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		if ( ! $containerBuilder->findByType(EventManager::class)) {
			throw new MissingServiceException(
				sprintf(
					'Instance of "%s" required by firewalls is missing. You might need to register %s extension".',
					EventManager::class,
					EventsExtension::class
				)
			);
		}
	}


	/**
	 * @param string $mediatorClass
	 * @param string $colleagueClass
	 * @param string $adderMethod
	 */
	private function loadMediator($mediatorClass, $colleagueClass, $adderMethod)
	{
		$containerBuilder = $this->getContainerBuilder();

		$mediator = $containerBuilder->getDefinition($containerBuilder->getByType($mediatorClass));
		foreach ($containerBuilder->findByType($colleagueClass) as $colleague) {
			$mediator->addSetup($adderMethod, ['@' . $colleague->getClass()]);
		}
	}

}
