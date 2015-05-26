<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use Nette\DI\MissingServiceException;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symnedi\Security\Tests\DI\AbstractSecurityExtensionTest;
use Symnedi\Security\Tests\DI\SecurityExtensionFirewallSource\FirewallListener;


class MissingEventDispatcherTest extends AbstractSecurityExtensionTest
{

	public function testRegisterProperFirewall()
	{
		$extension = $this->getExtension();
		$extension->setConfig([
			'firewalls' => [
				'customMatcher' => [
					'requestMatcher' => '@' . RequestMatcher::class,
					'securityListener' => '@' . FirewallListener::class
				]
			]
		]);

		$extension->loadConfiguration();
		$this->setExpectedException(MissingServiceException::class);
		$extension->beforeCompile();
	}

}
