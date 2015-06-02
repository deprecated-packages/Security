<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Event;

use Nette\Application\Application;
use Symfony\Component\EventDispatcher\Event;


class ApplicationEvent extends Event
{

	/**
	 * @var Application
	 */
	private $application;


	public function __construct(Application $application)
	{
		$this->application = $application;
	}


	/**
	 * @return Application
	 */
	public function getApplication()
	{
		return $this->application;
	}

}
