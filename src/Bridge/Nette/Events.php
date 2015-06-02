<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Bridge\Nette;


/**
 * Events in Nette App life cycle
 */
class Events
{

	/**
	 * @var string
	 */
	const ON_APPLICATION_REQUEST = 'Nette\Application\Application::onRequest';

	/**
	 * @var string
	 */
	const ON_PRESENTER_STARTUP = 'Nette\Application\UI\Presenter::onStartup';

}
