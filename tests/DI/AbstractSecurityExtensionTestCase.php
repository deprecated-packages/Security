<?php

declare(strict_types=1);

namespace Symnedi\Security\Tests\DI;

use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Symnedi\Security\DI\SecurityExtension;

abstract class AbstractSecurityExtensionTestCase extends TestCase
{
    /**
     * @return SecurityExtension
     */
    protected function getExtension()
    {
        $extension = new SecurityExtension();
        $extension->setCompiler(new Compiler(new ContainerBuilder()), 'compiler');

        return $extension;
    }
}
