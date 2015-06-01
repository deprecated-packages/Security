<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use Nette\DI\MissingServiceException;
use PHPUnit_Framework_TestCase;
use Symnedi\Security\Tests\ContainerFactory;


class MissingEventDispatcherTest extends PHPUnit_Framework_TestCase
{

	public function testCreate()
	{
		$this->setExpectedException(MissingServiceException::class);
		(new ContainerFactory)->createWithConfig(__DIR__ . '/MissingEventDispatcherTest/config.neon');
	}

}
