<?php

declare (strict_types = 1);

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Http;

use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\Http\FirewallMapInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


final class FirewallMapFactory implements FirewallMapFactoryInterface
{

	/**
	 * @var RequestMatcherInterface[]
	 */
	private $requestMatchers = [];

	/**
	 * @var FirewallHandlerInterface[][]
	 */
	private $firewallHandlers = [];


	public function addRequestMatcher(RequestMatcherInterface $requestMatcher)
	{
		$this->requestMatchers[$requestMatcher->getFirewallName()] = $requestMatcher;
	}


	public function addFirewallHandler(FirewallHandlerInterface $firewallHandler)
	{
		$this->firewallHandlers[$firewallHandler->getFirewallName()][] = $firewallHandler;
	}


	public function create() : FirewallMapInterface
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
