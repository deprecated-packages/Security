<?php

namespace Symnedi\Security\Tests\EventSubscriber;

use Nette\Application\Application;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\IPresenter;
use Nette\Application\Request as ApplicationRequest;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;
use Symnedi\EventDispatcher\Event\ApplicationPresenterEvent;
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
		$listenerMock = $this->prophesize(FirewallHandlerInterface::class);
		$listenerMock->handle(Argument::cetera())->willReturn(function () {
			throw new ForbiddenRequestException;
		});

		$firewallMapMock = $this->prophesize(FirewallMapInterface::class);
		$firewallMapMock->getListeners(Argument::type(Request::class))->willReturn(
			[[$listenerMock->reveal()], '']
		);

		$request = new Request((new UrlScript)->setScriptPath('admin/script.php'));
		$this->firewall = new FirewallSubscriber($firewallMapMock->reveal(), $request);
	}


	public function testGetSubscribedEvents()
	{
		$this->assertSame(
			[NetteApplicationEvents::ON_PRESENTER => 'onPresenter'],
			$this->firewall->getSubscribedEvents()
		);
	}


	public function testOnPresenter()
	{
		$requestMock = $this->prophesize(ApplicationRequest::class);
		$requestMock->getParameters()->willReturn(['parameter' => 'value']);

		$applicationPresenterEventMock = new ApplicationPresenterEvent(
			$this->prophesize(Application::class)->reveal(),
			$this->prophesize(IPresenter::class)->reveal()
		);

		$this->firewall->onPresenter($applicationPresenterEventMock);
	}

}
