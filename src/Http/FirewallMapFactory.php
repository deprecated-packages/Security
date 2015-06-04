<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Http;

use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


class FirewallMapFactory implements FirewallMapFactoryInterface
{

	/**
	 * @var RequestMatcherInterface[]
	 */
	private $requestMatchers = [];

	/**
	 * @var FirewallHandlerInterface[][]
	 */
	private $firewallHandlers = [];


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
	public function addFirewallHandler(FirewallHandlerInterface $firewallHandler)
	{
		$this->firewallHandlers[$firewallHandler->getFirewallName()][] = $firewallHandler;
	}


	/**
	 * {@inheritdoc}
	 */
	public function create()
	{
		$firewallMap = new FirewallMap;
		foreach ($this->requestMatchers as $firewallName => $requestMatcher) {
			if (isset($this->firewallHandlers[$firewallName])) {
				$firewallMap->add($requestMatcher, $this->firewallHandlers[$firewallName]);
			}
		}
		return $firewallMap;
	}

}
