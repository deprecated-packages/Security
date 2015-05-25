<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Bridge\SymfonyHttpFoundation;

use Nette\Http\IRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;


class NetteRequestAdapter implements IRequest
{

	/**
	 * @var SymfonyRequest
	 */
	private $symfonyRequest;


	public function __construct(SymfonyRequest $symfonyRequest)
	{
		$this->symfonyRequest = $symfonyRequest;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getUrl()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getQuery($key = NULL, $default = NULL)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getPost($key = NULL, $default = NULL)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFile($key)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFiles()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getCookie($key, $default = NULL)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getCookies()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMethod()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isMethod($method)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getHeader($header, $default = NULL)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getHeaders()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isSecured()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function isAjax()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getRemoteAddress()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getRemoteHost()
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function getRawBody()
	{
	}

}
