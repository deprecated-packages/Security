<?php

declare(strict_types=1);

namespace Symnedi\Security\Tests\DI\SecurityExtension;

use Nette\Application\Application;
use Symnedi\Security\Tests\ContainerFactory;
use Symnedi\Security\Tests\DI\AbstractSecurityExtensionTestCase;

final class ListenerTest extends AbstractSecurityExtensionTestCase
{
    /**
     * @expectedException \Nette\Application\AbortException
     */
    public function testDispatch()
    {
        $container = (new ContainerFactory())->createWithConfig(__DIR__ . '/ListenerSource/config.neon');

        /** @var Application $application */
        $application = $container->getByType(Application::class);
        $application->run();
    }
}
