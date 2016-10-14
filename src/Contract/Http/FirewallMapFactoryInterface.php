<?php

declare(strict_types=1);

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\Http;

use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;

interface FirewallMapFactoryInterface
{
    public function addRequestMatcher(RequestMatcherInterface $requestMatcher);

    public function addFirewallHandler(FirewallHandlerInterface $firewallHandler);

    public function create() : FirewallMapInterface;
}
