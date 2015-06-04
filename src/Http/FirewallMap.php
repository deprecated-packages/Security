<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Http;

use Nette\Http\IRequest;
use Symfony\Component\Security\Http\Firewall\ExceptionListener;
use Symnedi\Security\Contract\Http\FirewallMapInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


/**
 * Mimics @see Symfony\Component\Security\Http\FirewallMap
 */
class FirewallMap implements FirewallMapInterface
{

	/**
	 * @var array|RequestMatcherInterface[][]
	 */
	private $map = [];


	/**
	 * {@inheritdoc}
	 */
	public function add(
		RequestMatcherInterface $requestMatcher = NULL,
		array $listeners = [],
		ExceptionListener $exceptionListener = NULL
	) {
		$this->map[] = [$requestMatcher, $listeners, $exceptionListener];
	}


	/**
	 * {@inheritdoc}
	 */
	public function getListeners(IRequest $request)
	{
		foreach ($this->map as $elements) {
			if ($elements[0] === NULL || $elements[0]->matches($request)) {
				return [$elements[1], $elements[2]];
			}
		}

		return [[], NULL];
	}

}
