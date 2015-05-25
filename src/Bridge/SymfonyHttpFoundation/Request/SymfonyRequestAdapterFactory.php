<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Bridge\SymfonyHttpFoundation\Request;

use Nette\Application\Request as ApplicationRequest;
use Nette\Http\IRequest as NetteRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;


class SymfonyRequestAdapterFactory
{

	/**
	 * @var NetteRequest
	 */
	private $netteRequest;


	public function __construct(NetteRequest $netteRequest)
	{
		$this->netteRequest = $netteRequest;
	}


	/**
	 * @return SymfonyRequest
	 */
	public function createFromNette(NetteRequest $netteRequest)
	{
		return new SymfonyRequest($netteRequest->getQuery());
	}


	/**
	 * @return SymfonyRequest
	 */
	public function createFromNetteApplicationRequest(ApplicationRequest $applicationRequest)
	{
		// we only need HttpRequest
		return new SymfonyRequest($this->netteRequest->getQuery());
	}

}
