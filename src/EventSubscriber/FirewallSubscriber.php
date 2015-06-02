<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\EventSubscriber;

use Nette\Application\Application;
use Nette\Application\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Event\ApplicationRequestEvent;
use Symnedi\Security\Nette\ApplicationEvents;
use Symnedi\Security\Nette\Events;


/**
 * Mimics @see Symfony\Component\Security\Http\Firewall
 */
class FirewallSubscriber implements EventSubscriberInterface
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
	public static function getSubscribedEvents()
	{
		return [ApplicationEvents::ON_APPLICATION_REQUEST => 'onRequest'];
	}


	public function onRequest(ApplicationRequestEvent $applicationRequestEvent)
	{
		$symfonyRequest = $this->symfonyRequestAdapterFactory->create();

		/** @var FirewallHandlerInterface[] $listeners */
		list($listeners) = $this->firewallMap->getListeners($symfonyRequest);
		foreach ($listeners as $listener) {
			$listener->handle($applicationRequestEvent->getApplication(), $applicationRequestEvent->getRequest());
		}
	}

}
