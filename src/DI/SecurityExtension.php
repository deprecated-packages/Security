<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Kdyby\Events\DI\EventsExtension;
use Kdyby\Events\EventManager;
use Kdyby\Events\SymfonyDispatcher;
use Nette\DI\CompilerExtension;
use Nette\DI\MissingServiceException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
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
		$this->removeKdybySymfonyProxy();

		if ($containerBuilder->findByType(FirewallHandlerInterface::class)) {
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

		$this->loadEventManager();

		// dealt manually
//		$this->loadMediator(EventManager::class, FirewallSubscriber::class, 'addEventSubscriber');

		$this->loadMediator(FirewallMapFactoryInterface::class, FirewallHandlerInterface::class, 'addFirewallHandler');
		$this->loadMediator(FirewallMapFactoryInterface::class, RequestMatcherInterface::class, 'addRequestMatcher');

		// Symfony\EventDispatcher
		$this->loadMediator(EventDispatcherInterface::class, EventSubscriberInterface::class, 'addSubscriber');
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


	private function removeKdybySymfonyProxy()
	{
		$containerBuilder = $this->getContainerBuilder();

		// todo: determine Kdyby\EventsExtension: 0 = prefix
		if ($containerBuilder->hasDefinition('0.symfonyProxy')) {
			$containerBuilder->removeDefinition('0.symfonyProxy');
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


	private function loadEventManager()
	{
		$containerBuilder = $this->getContainerBuilder();
		$eventManagerDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(EventManager::class));
		$eventManagerDefinition->addSetup('addEventSubscriber', ['@firewallSubscriber']);
	}

}
