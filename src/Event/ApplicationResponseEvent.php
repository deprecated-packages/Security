<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Event;

use Nette\Application\Application;
use Nette\Application\IResponse;
use Symfony\Component\EventDispatcher\Event;


class ApplicationResponseEvent extends Event
{

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var IResponse
	 */
	private $response;


	public function __construct(Application $application, IResponse $response)
	{
		$this->application = $application;
		$this->response = $response;
	}


	/**
	 * @return Application
	 */
	public function getApplication()
	{
		return $this->application;
	}


	/**
	 * @return IResponse
	 */
	public function getResponse()
	{
		return $this->response;
	}

}
