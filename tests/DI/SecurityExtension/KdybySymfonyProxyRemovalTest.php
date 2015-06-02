<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use PHPUnit_Framework_TestCase;
use Symnedi\Security\Tests\ContainerFactory;


class KdybySymfonyProxyRemovalTest extends PHPUnit_Framework_TestCase
{

	public function testRemoval()
	{
		(new ContainerFactory)->createWithConfig(__DIR__ . '/KdybySymfonyProxyRemovalSource/config.neon');
	}

}
