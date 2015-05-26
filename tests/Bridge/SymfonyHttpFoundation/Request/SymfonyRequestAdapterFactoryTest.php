<?php

namespace Symnedi\Security\Tests\Bridge\SymfonyHttpFoundation;

use Mockery;
use Nette\Http\IRequest;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symnedi\Security\Bridge\SymfonyHttpFoundation\Request\SymfonyRequestAdapterFactory;


class SymfonyRequestAdapterFactoryTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var SymfonyRequestAdapterFactory
	 */
	private $symfonyRequestAdapterFactory;


	protected function setUp()
	{
		$netteHttpRequestMock = Mockery::mock(IRequest::class, [
			'getQuery' => ['do' => 'delete'],
			'getPost' => ['name' => 'John'],
			'getCookies' => [],
			'getFiles' => [],
			'getRemoteHost' => 'localhost',
			'getHeaders' => ['header' => 1]
		]);
		$this->symfonyRequestAdapterFactory = new SymfonyRequestAdapterFactory($netteHttpRequestMock);
	}


	public function testCreateFromNette()
	{
		$symfonyHttpRequest = $this->symfonyRequestAdapterFactory->create();
		$this->assertInstanceOf(Request::class, $symfonyHttpRequest);

		$this->assertSame('server.name', $symfonyHttpRequest->getHost());
		$this->assertSame('', $symfonyHttpRequest->getBaseUrl());
		$this->assertNull($symfonyHttpRequest->getQueryString());
	}

}
