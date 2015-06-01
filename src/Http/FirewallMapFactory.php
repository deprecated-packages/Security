<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Http;

use Symfony\Component\Security\Http\FirewallMap;
use Symnedi\Security\Contract\Http\FirewallListenerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


class FirewallMapFactory implements FirewallMapFactoryInterface
{

	/**
	 * @var RequestMatcherInterface[]
	 */
	private $requestMatchers = [];

	/**
	 * @var FirewallListenerInterface[][]
	 */
	private $firewallListeners = [];


	/**
	 * {@inheritdoc}
	 */
	public function addRequestMatcher(RequestMatcherInterface $requestMatcher)
	{
		$this->requestMatchers[$requestMatcher->getFirewallName()] = $requestMatcher;
	}


	/**
	 * {@inheritdoc}
	 */
	public function addFirewallListener(FirewallListenerInterface $firewallListener)
	{
		$this->firewallListeners[$firewallListener->getFirewallName()][] = $firewallListener;
	}


	/**
	 * {@inheritdoc}
	 */
	public function create()
	{
		$firewallMap = new FirewallMap;
		foreach ($this->requestMatchers as $firewallName => $requestMatcher) {
			if (isset($this->firewallListeners[$firewallName])) {
				$listeners = $this->firewallListeners[$firewallName];
				$firewallMap->add($requestMatcher, $listeners);
			}
		}
		return $firewallMap;
	}

}
