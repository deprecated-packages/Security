<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\HttpFoundation;

use Nette\Http\Request;
use Symnedi\Security\Contract\DI\ModularFirewallInterface;


/**
 * Mimics @see \Symfony\Component\HttpFoundation\RequestMatcherInterface
 */
interface RequestMatcherInterface extends ModularFirewallInterface
{

	/**
	 * Decides whether the rule(s) implemented by the strategy matches the supplied request.
	 *
	 * @param Request $request The request to check for a match
	 *
	 * @return bool true if the request matches, false otherwise
	 */
	function matches(Request $request);

}
