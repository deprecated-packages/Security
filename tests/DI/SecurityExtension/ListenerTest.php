<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use Nette\Application\AbortException;
use Nette\Application\Application;
use Symnedi\Security\Tests\ContainerFactory;
use Symnedi\Security\Tests\DI\AbstractSecurityExtensionTestCase;


class ListenerTestCase extends AbstractSecurityExtensionTestCase
{

	public function testDispatch()
	{
		$container = (new ContainerFactory)->createWithConfig(__DIR__ . '/ListenerSource/config.neon');

		/** @var Application $application */
		$application = $container->getByType(Application::class);
		$this->setExpectedException(AbortException::class);
		$application->run();
	}

}
