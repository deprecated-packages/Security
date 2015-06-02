<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Nette;
use Nette\Application\Application;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\Statement;
use Nette\PhpGenerator\ClassType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symnedi\Security\Event\ApplicationRequestEvent;
use Symnedi\Security\Nette\ApplicationEvents;
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

		// todo: move to EventDispatcher
		$this->removeKdybySymfonyProxy();

		// todo: move to EventDispatcher
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

		foreach ($containerBuilder->findByType(EventDispatcherInterface::class) as $name => $eventDispatcherDefinition)
		{
			if ($eventDispatcherDefinition->getFactory()->getEntity() === 'Kdyby\Events\SymfonyDispatcher') {
				// hotfix of https://github.com/nette/di/pull/71
				// also remove from definition class reference
				$classRemover = function (ContainerBuilder $containerBuilder, $name, $class) {
					if (isset($containerBuilder->classes[$class][TRUE])) {
						foreach ($containerBuilder->classes[$class][TRUE] as $key => $definitionName) {
							if ($name === $definitionName) {
								unset($containerBuilder->classes[$class][TRUE][$key]);
							}
						}
					}
				};
				$class = $containerBuilder->getDefinition($name)->getClass();
				$classRemover = \Closure::bind($classRemover, NULL, $containerBuilder);
				$classRemover($containerBuilder, $name, $class);

				$containerBuilder->removeDefinition($name);
			}
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

		// class->property
		// eventClass(args)
		// eventName

		if ( ! $containerBuilder->getByType(Application::class)) {
			return;
		}

		$application = $containerBuilder->getDefinition($containerBuilder->getByType(Application::class));

		// array of events!

		$application->addSetup('$service->onRequest[] = ?;', [
			new Statement('
				function ($app, $presenter) {
					$class = ?;
			        $event = new $class($app, $presenter);
			        ?->dispatch(?, $event);
			    }', [
				ApplicationRequestEvent::class,
				'@' . EventDispatcherInterface::class,
				ApplicationEvents::ON_APPLICATION_REQUEST
			])
		]);
	}

}
