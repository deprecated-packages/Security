<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\EventDispatcher\Event\ApplicationRequestEvent;
use Symnedi\EventDispatcher\NetteApplicationEvents;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;


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
		return [NetteApplicationEvents::ON_REQUEST => 'onRequest'];
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
