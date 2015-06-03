<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Bridge\SymfonyHttpFoundation\Request;

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
	public function create()
	{
		return new SymfonyRequest(
			$this->netteRequest->getQuery(), // The GET parameters
			$this->netteRequest->getPost(), // The POST parameters
			[], // The request attributes (parameters parsed from the PATH_INFO, ...)
			$this->netteRequest->getCookies(), // The COOKIE parameters
			$this->netteRequest->getFiles() // The FILES parameters
			// The SERVER parameters
			// The raw body data
		);
	}

}
