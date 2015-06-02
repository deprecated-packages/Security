<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Event;

use Nette\Application\Application;
use Nette\Application\Request;
use Symfony\Component\EventDispatcher\Event;


class ApplicationRequestEvent extends Event
{

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var Request
	 */
	private $request;


	public function __construct(Application $application, Request $request)
	{
		$this->application = $application;
		$this->request = $request;
	}


	/**
	 * @return Application
	 */
	public function getApplication()
	{
		return $this->application;
	}


	/**
	 * @return Request
	 */
	public function getRequest()
	{
		return $this->request;
	}

}
