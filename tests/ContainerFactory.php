<?php

namespace Symnedi\Security\Tests;

use Nette\Configurator;
use Nette\DI\Container;
use Tracy\Debugger;

final class ContainerFactory
{
    public function create() : Container
    {
        return $this->createWithConfig(__DIR__.'/config/default.neon');
    }

    public function createWithConfig(string $config) : Container
    {
        $configurator = new Configurator();
        $configurator->setTempDirectory(TEMP_DIR);
        $configurator->setDebugMode(Debugger::PRODUCTION);
        $configurator->addConfig($config);

        return $configurator->createContainer();
    }
}
