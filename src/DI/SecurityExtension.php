<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Kdyby\Events\EventManager;
use Nette\DI\CompilerExtension;
use Nette\DI\MissingServiceException;
use Nette\Utils\Validators;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Http\Firewall;


class SecurityExtension extends CompilerExtension
{

	/**
	 * @var array[]
	 */
	private $defaults = [
		'firewalls' => []
	];


	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);

		$config = $this->getConfig($this->defaults);
		$this->validateConfigTypes($config);

		if (count($config['firewalls'])) {
			$this->loadFirewalls($config['firewalls']);
		}
	}


	public function beforeCompile()
	{
		$this->loadAccessDecisionManagerFactory();

		$config = $this->getConfig($this->defaults);
		if (count($config['firewalls'])) {
			$this->validateEventDispatcherPresence();
			$this->addFirewallToEventDispatcher();
		}
	}


	private function loadAccessDecisionManagerFactory()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$accessDecisionManagerFactoryDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(AccessDecisionManagerFactoryInterface::class)
		);

		foreach ($containerBuilder->findByType(VoterInterface::class) as $voterDefinition) {
			$accessDecisionManagerFactoryDefinition->addSetup('addVoter', ['@' . $voterDefinition->getClass()]);
		}
	}


	private function validateConfigTypes(array $config)
	{
		if (count($config['firewalls'])) {
			Validators::assert($config['firewalls'], 'array');

			foreach ($config['firewalls'] as $name => $firewall) {
				Validators::assert($name, 'string');
				Validators::assertField($firewall, 'requestMatcher', 'string');
				Validators::assertField($firewall, 'securityListener', 'string');
			}
		}
	}


	private function loadFirewalls(array $firewalls)
	{
		$containerBuilder = $this->getContainerBuilder();

		$services = $this->loadFromFile(__DIR__ . '/firewallServices.neon');
		$this->compiler->parseServices($containerBuilder, $services);

		$containerBuilder->prepareClassList();
		$firewallMapDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(FirewallMapInterface::class)
		);

		foreach ($firewalls as $name => $firewall) {
			$firewallMapDefinition->addSetup('add', [
				$firewall['requestMatcher'],
				[$firewall['securityListener']]
			]);
		}
	}


	private function addFirewallToEventDispatcher()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$firewallDefinition = $containerBuilder->getDefinition(
			$containerBuilder->getByType(Firewall::class)
		);

		$eventManagerDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(EventManager::class));
		$eventManagerDefinition->addSetup('addEventSubscriber', ['@' . $firewallDefinition->getClass()]);
	}


	private function validateKdybyEventsExtensionPresence()
	{
		// @todo, replace EventDispatcherInterface
	}


	private function validateEventDispatcherPresence()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		if ( ! $containerBuilder->findByType(EventManager::class)) {
			throw new MissingServiceException(
				sprintf('Instance of "%s" required by firewalls is missing".', EventManager::class)
			);
		}
	}

}
