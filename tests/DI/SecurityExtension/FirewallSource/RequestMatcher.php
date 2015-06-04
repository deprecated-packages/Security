<?php

namespace Symnedi\Security\Tests\DI\SecurityExtension\FirewallSource;

use Nette\Http\Request;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


class RequestMatcher implements RequestMatcherInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function getFirewallName()
	{
		return 'adminFirewall';
	}


	/**
	 * {@inheritdoc}
	 */
	public function matches(Request $request)
	{
		$url = $request->getUrl();
		// match all, just for testing purposes only
		return strpos($url, '/') === 0;
	}

}
