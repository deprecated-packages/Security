<?php

namespace Symnedi\Security\Tests\Core\Authorization;

use InvalidArgumentException;
use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Core\Authorization\AccessDecisionManagerFactory;


class AccessDecisionManagerFactoryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var AccessDecisionManagerFactory
	 */
	private $accessDecisionManagerFactory;


	protected function setUp()
	{
		$this->accessDecisionManagerFactory = new AccessDecisionManagerFactory([]);
	}


	public function testAtLeastOneVoter()
	{
		$this->setExpectedException(InvalidArgumentException::class);
		$this->accessDecisionManagerFactory->create();
	}


	public function testCreateWithOneVoter()
	{
		$voterMock = Mockery::mock(VoterInterface::class);
		$this->accessDecisionManagerFactory->addVoter($voterMock);
		$accessDecisionManager = $this->accessDecisionManagerFactory->create();
		$this->assertInstanceOf(AccessDecisionManager::class, $accessDecisionManager);
	}

}
