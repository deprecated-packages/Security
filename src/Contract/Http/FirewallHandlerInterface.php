<?php

declare (strict_types = 1);

/*
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

	public function handle(Application $application, IRequest $request);

}
