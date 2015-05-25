<?php

namespace Symnedi\Security\Tests\DI\SecurityExtensionFirewallSource;

use Nette\Application\AbortException;
use Nette\Application\Application;
use Nette\Application\Request;
use Symnedi\Security\Contract\Http\ListenerInterface;


class FirewallListener implements ListenerInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function handle(Application $application, Request $applicationRequest)
	{
		throw new AbortException;
	}

}
