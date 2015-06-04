<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\Http;

use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


interface FirewallMapFactoryInterface
{

	function addRequestMatcher(RequestMatcherInterface $requestMatcher);


	function addFirewallHandler(FirewallHandlerInterface $firewallHandler);


	/**
	 * @return FirewallMapInterface
	 */
	function create();

}
