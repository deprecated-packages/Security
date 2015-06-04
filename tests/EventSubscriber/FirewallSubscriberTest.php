<?php

namespace Symnedi\Security\Tests\EventSubscriber;

use Mockery;
use Nette\Application\Application;
use Nette\Application\ForbiddenRequestException;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use PHPUnit_Framework_TestCase;
use Symnedi\EventDispatcher\Event\ApplicationRequestEvent;
use Symnedi\EventDispatcher\NetteApplicationEvents;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapInterface;
use Symnedi\Security\EventSubscriber\FirewallSubscriber;


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

		$request = new Request((new UrlScript)->setScriptPath('admin/script.php'));
		$this->firewall = new FirewallSubscriber($firewallMapMock, $request);
	}


	public function testGetSubscribedEvents()
	{
		$this->assertSame(
			[NetteApplicationEvents::ON_REQUEST => 'onRequest'],
			$this->firewall->getSubscribedEvents()
		);
	}


	public function testOnRequest()
	{
		$applicationRequestEventMock = Mockery::mock(ApplicationRequestEvent::class, [
			'getApplication' => Mockery::mock(Application::class),
			'getRequest' => Mockery::mock(Request::class, [
				'getParameters' => [
					'parameter' => 'value'
				]
			])
		]);
		$this->firewall->onRequest($applicationRequestEventMock);
	}

}
