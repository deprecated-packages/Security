<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Nette\Application\Application;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symnedi\Security\Nette\Events;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


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

		$this->bindNetteEvents();

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
		$this->loadMediator(FirewallMapFactoryInterface::class, FirewallHandlerInterface::class, 'addFirewallHandler');
		$this->loadMediator(FirewallMapFactoryInterface::class, RequestMatcherInterface::class, 'addRequestMatcher');
		$this->loadMediator(EventDispatcherInterface::class, EventSubscriberInterface::class, 'addSubscriber');
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

		$mediatorDefinition = $containerBuilder->getDefinition($containerBuilder->getByType($mediatorClass));
		foreach ($containerBuilder->findByType($colleagueClass) as $colleagueDefinition) {
			$mediatorDefinition->addSetup($adderMethod, ['@' . $colleagueDefinition->getClass()]);
		}
	}


	private function bindNetteEvents()
	{
		$containerBuilder = $this->getContainerBuilder();

		if ( ! $containerBuilder->getByType(Application::class)) {
			return;
		}

		$application = $containerBuilder->getDefinition($containerBuilder->getByType(Application::class));

		// array of events!

		$application->addSetup('$service->onRequest[] = ?;', [
			new Statement('
				function ($app, $presenter) {
			        $event = new Symnedi\Security\Event\ApplicationRequestEvent($app, $presenter);
			        ?->dispatch(?, $event);
			    }', [
				'@Symfony\Component\EventDispatcher\EventDispatcherInterface',
				Events::ON_APPLICATION_REQUEST
			])
		]);
	}

}
