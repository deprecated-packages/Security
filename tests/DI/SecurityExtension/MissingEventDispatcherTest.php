<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use Nette\DI\MissingServiceException;
use Symnedi\Security\Tests\DI\AbstractSecurityExtensionTest;


class MissingEventDispatcherTest extends AbstractSecurityExtensionTest
{

	public function testRegisterProperFirewall()
	{
		$extension = $this->getExtension();
		$extension->setConfig([
			'firewalls' => [
				'customMatcher' => [
					'requestMatcher' => '@Symnedi\Security\Tests\DI\SecurityExtensionSource\RequestMatcher',
					'securityListener' => '@Symnedi\Security\Tests\DI\SecurityExtensionSource\FirewallListener'
				]
			]
		]);

		$extension->loadConfiguration();
		$this->setExpectedException(MissingServiceException::class);
		$extension->beforeCompile();
	}

}
