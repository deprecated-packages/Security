<?php

namespace Symnedi\Security\Tests\EventSubscriber;

use Mockery;
use Nette\Application\Application;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\Request;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Event\ApplicationRequestEvent;
use Symnedi\Security\EventSubscriber\FirewallSubscriber;
use Symnedi\Security\Nette\Events;


class FirewallSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var FirewallSubscriber
	 */
	private $firewall;


	protected function setUp()
	{
		$listener = Mockery::mock(FirewallHandlerInterface::class, [
			'handle' => function () {
				throw new ForbiddenRequestException;
			}
		]);

		$firewallMapMock = Mockery::mock(FirewallMapInterface::class, [
			'getListeners' => [[$listener], '']
		]);

		$symfonyRequestAdapterFactory = Mockery::mock(SymfonyRequestAdapterFactory::class, [
			'create' => Mockery::mock(SymfonyHttpRequest::class)
		]);
		$this->firewall = new FirewallSubscriber($firewallMapMock, $symfonyRequestAdapterFactory);
	}


	public function testGetSubscribedEvents()
	{
		$this->assertSame([
			Events::ON_APPLICATION_REQUEST => 'onRequest'
		], $this->firewall->getSubscribedEvents());
	}


	public function testOnRequest()
	{
		$applicationRequestEventMock = Mockery::mock(ApplicationRequestEvent::class, [
			'getApplication' => Mockery::mock(Application::class),
			'getRequest' => Mockery::mock(Request::class)
		]);
		$this->firewall->onRequest($applicationRequestEventMock);
	}

}
