<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Event;

use Nette\Application\IResponse;
use Nette\Application\UI\Presenter;
use Symfony\Component\EventDispatcher\Event;


class PresenterResponseEvent extends Event
{

	/**
	 * @var Presenter
	 */
	private $presenter;

	/**
	 * @var IResponse
	 */
	private $response;


	public function __construct(Presenter $presenter, IResponse $response = NULL)
	{
		$this->presenter = $presenter;
		$this->response = $response;
	}


	/**
	 * @return Presenter
	 */
	public function getPresenter()
	{
		return $this->presenter;
	}


	/**
	 * @return IResponse
	 */
	public function getResponse()
	{
		return $this->response;
	}

}
