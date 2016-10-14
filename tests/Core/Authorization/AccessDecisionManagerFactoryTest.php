<?php

declare(strict_types=1);

namespace Symnedi\Security\Tests\Core\Authorization;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Core\Authorization\AccessDecisionManagerFactory;

final class AccessDecisionManagerFactoryTest extends TestCase
{
    /**
     * @var AccessDecisionManagerFactory
     */
    private $accessDecisionManagerFactory;

    protected function setUp()
    {
        $this->accessDecisionManagerFactory = new AccessDecisionManagerFactory([]);
    }

    public function testCreateWithOneVoter()
    {
        $voterMock = $this->prophesize(VoterInterface::class);
        $this->accessDecisionManagerFactory->addVoter($voterMock->reveal());
        $accessDecisionManager = $this->accessDecisionManagerFactory->create();
        $this->assertInstanceOf(AccessDecisionManager::class, $accessDecisionManager);
    }
}
