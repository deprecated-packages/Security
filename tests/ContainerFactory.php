<?php

namespace Symnedi\Security\Tests;

use Nette\Configurator;
use Nette\DI\Container;
use Tracy\Debugger;


class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		return $this->createWithConfig(__DIR__ . '/config/default.neon');
	}


	/**
	 * @param string $config
	 * @return Container
	 */
	public function createWithConfig($config)
	{
		$configurator = new Configurator;
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->setDebugMode(Debugger::PRODUCTION);
		$configurator->addConfig($config);
		return $configurator->createContainer();
	}

}
