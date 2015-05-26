<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Http;

use Kdyby\Events\EventManager;
use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\Request;
use Symfony\Component\Security\Http\Firewall as SymfonyFirewall;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;
use Symnedi\Security\Contract\Http\ListenerInterface;


/**
 * Mimics @see Symfony\Component\Security\Http\Firewall
 */
class Firewall implements Subscriber
{

	/**
	 * @var FirewallMapInterface
	 */
	private $firewallMap;

	/**
	 * @var EventManager
	 */
	private $eventManager;

	/**
	 * @var SymfonyRequestAdapterFactory
	 */
	private $symfonyRequestAdapterFactory;


	public function __construct(
		FirewallMapInterface $firewallMap,
		EventManager $eventManager,
		SymfonyRequestAdapterFactory $symfonyRequestAdapterFactory
	) {
		$this->firewallMap = $firewallMap;
		$this->eventManager = $eventManager;
		$this->symfonyRequestAdapterFactory = $symfonyRequestAdapterFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [Application::class . '::onRequest'];
	}


	public function onRequest(Application $application, Request $applicationRequest)
	{
		$symfonyRequest = $this->symfonyRequestAdapterFactory->create();

		/** @var ListenerInterface[] $listeners */
		list($listeners) = $this->firewallMap->getListeners($symfonyRequest);

		foreach ($listeners as $listener) {
			$listener->handle($application, $applicationRequest);
		}
	}

}
