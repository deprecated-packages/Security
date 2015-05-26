<?php

namespace Symnedi\Security\Tests\EventSubscriber;

use Kdyby\Events\EventManager;
use Mockery;
use Nette\Application\Application;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\Request;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;
use Symnedi\Security\Contract\Http\FirewallListenerInterface;
use Symnedi\Security\EventSubscriber\FirewallSubscriber;


class FirewallSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var FirewallSubscriber
	 */
	private $firewall;


	protected function setUp()
	{
		$listener = Mockery::mock(FirewallListenerInterface::class, [
			'handle' => function () {
				throw new ForbiddenRequestException;
			}
		]);

		$firewallMapMock = Mockery::mock(FirewallMapInterface::class, [
			'getListeners' => [[$listener], '']
		]);

		$eventManagerMock = Mockery::mock(EventManager::class);
		$symfonyRequestAdapterFactory = Mockery::mock(SymfonyRequestAdapterFactory::class, [
			'create' => Mockery::mock(SymfonyHttpRequest::class)
		]);
		$this->firewall = new FirewallSubscriber($firewallMapMock, $eventManagerMock, $symfonyRequestAdapterFactory);
	}


	public function testGetSubscribedEvents()
	{
		$this->assertSame([Application::class . '::onRequest'], $this->firewall->getSubscribedEvents());
	}


	public function testOnRequest()
	{
		$applicationMock = Mockery::mock(Application::class);
		$applicationRequestMock = Mockery::mock(Request::class);

		$this->firewall->onRequest($applicationMock, $applicationRequestMock);
	}

}
