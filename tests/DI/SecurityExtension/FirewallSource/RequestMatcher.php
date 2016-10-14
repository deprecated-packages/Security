<?php

declare (strict_types = 1);

namespace Symnedi\Security\Tests\DI\SecurityExtension\FirewallSource;

use Nette\Http\IRequest;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


final class RequestMatcher implements RequestMatcherInterface
{

	public function getFirewallName() : string
	{
		return 'adminFirewall';
	}


	public function matches(IRequest $request) : bool
	{
		$url = $request->getUrl();
		// match all, just for testing purposes only
		return strpos($url->getScriptPath(), '/') === 0;
	}

}
