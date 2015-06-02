<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\EventSubscriber;

use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\Request;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Bridge\Nette\Events;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;


/**
 * Mimics @see Symfony\Component\Security\Http\Firewall
 */
class FirewallSubscriber implements Subscriber
{

	/**
	 * @var FirewallMapInterface
	 */
	private $firewallMap;

	/**
	 * @var SymfonyRequestAdapterFactory
	 */
	private $symfonyRequestAdapterFactory;


	public function __construct(
		FirewallMapInterface $firewallMap,
		SymfonyRequestAdapterFactory $symfonyRequestAdapterFactory
	) {
		$this->firewallMap = $firewallMap;
		$this->symfonyRequestAdapterFactory = $symfonyRequestAdapterFactory;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [Events::ON_APPLICATION_REQUEST];
	}


	public function onRequest(Application $application, Request $applicationRequest)
	{
		$symfonyRequest = $this->symfonyRequestAdapterFactory->create();

		/** @var FirewallHandlerInterface[] $listeners */
		list($listeners) = $this->firewallMap->getListeners($symfonyRequest);
		foreach ($listeners as $listener) {
			$listener->handle($application, $applicationRequest);
		}
	}

}
