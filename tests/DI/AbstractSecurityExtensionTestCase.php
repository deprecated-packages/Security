<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Tests\DI;

use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use Symnedi\Security\DI\SecurityExtension;


abstract class AbstractSecurityExtensionTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @return SecurityExtension
	 */
	protected function getExtension()
	{
		$extension = new SecurityExtension;
		$extension->setCompiler(new Compiler(new ContainerBuilder), 'compiler');
		return $extension;
	}

}
