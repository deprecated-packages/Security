<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\Http;

use Nette\Http\IRequest;
use Symfony\Component\Security\Http\Firewall\ExceptionListener;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


/**
 * Mimics @see \Symfony\Component\Security\Http\FirewallMapInterface
 */
interface FirewallMapInterface
{

	/**
	 * @param RequestMatcherInterface $requestMatcher
	 * @param array $listeners
	 * @param ExceptionListener $exceptionListener
	 */
	function add(
		RequestMatcherInterface $requestMatcher = NULL,
		array $listeners = [],
		ExceptionListener $exceptionListener = NULL
	);


	/**
	 * Returns the authentication listeners.
	 *
	 * If there are no authentication listeners, the first inner array must be
	 * empty.
	 *
	 * If there is no exception listener, the second element of the outer array
	 * must be null.
	 *
	 * @param IRequest $request
	 *
	 * @return array of the format array(array(AuthenticationListener), ExceptionListener)
	 */
	function getListeners(IRequest $request);

}
