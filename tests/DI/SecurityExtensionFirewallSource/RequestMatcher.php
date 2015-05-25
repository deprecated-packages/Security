<?php

namespace Symnedi\Security\Tests\DI\SecurityExtensionFirewallSource;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;


class RequestMatcher implements RequestMatcherInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function matches(Request $request)
	{
		$url = $request->getPathInfo();
		// match all, just for testing purposes only
		return strpos($url, '/') === 0;
	}

}
