<?php

namespace Symnedi\Security\Tests;

use Nette\Configurator;
use Nette\DI\Container;

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
        $configurator->addConfig($config);

        return $configurator->createContainer();
    }
}
