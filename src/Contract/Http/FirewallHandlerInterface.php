<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\Http;

use Nette\Application\Application;
use Nette\Http\IRequest;
use Symnedi\Security\Contract\DI\ModularFirewallInterface;


/**
 * Mimics @see \Symfony\Component\Security\Http\Firewall\ListenerInterface
 */
interface FirewallHandlerInterface extends ModularFirewallInterface
{

	function handle(Application $application, IRequest $request);

}
