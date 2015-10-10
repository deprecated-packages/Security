<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\EventSubscriber;

use Nette\Http\IRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symnedi\EventDispatcher\Event\ApplicationPresenterEvent;
use Symnedi\EventDispatcher\NetteApplicationEvents;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapInterface;


/**
 * Mimics @see Symfony\Component\Security\Http\Firewall
 */
final class FirewallSubscriber implements EventSubscriberInterface
{

	/**
	 * @var FirewallMapInterface
	 */
	private $firewallMap;

	/**
	 * @var IRequest
	 */
	private $request;


	public function __construct(FirewallMapInterface $firewallMap, IRequest $request)
	{
		$this->firewallMap = $firewallMap;
		$this->request = $request;
	}


	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return [NetteApplicationEvents::ON_PRESENTER => 'onPresenter'];
	}


	public function onPresenter(ApplicationPresenterEvent $applicationPresenterEvent)
	{
		/** @var FirewallHandlerInterface[] $listeners */
		list($listeners) = $this->firewallMap->getListeners($this->request);
		foreach ($listeners as $listener) {
			$listener->handle($applicationPresenterEvent->getApplication(), $this->request);
		}
	}

}
