<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Event;

use Exception;
use Nette\Application\Application;
use Symfony\Component\EventDispatcher\Event;


class ApplicationExceptionEvent extends Event
{

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var Exception
	 */
	private $exception;


	public function __construct(Application $application, Exception $exception = NULL)
	{
		$this->application = $application;
		$this->exception = $exception;
	}


	/**
	 * @return Application
	 */
	public function getApplication()
	{
		return $this->application;
	}


	/**
	 * @return Exception
	 */
	public function getException()
	{
		return $this->exception;
	}

}
