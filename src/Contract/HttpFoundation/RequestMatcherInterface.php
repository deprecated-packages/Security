<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\HttpFoundation;

use Symfony\Component\HttpFoundation\RequestMatcherInterface as SymfonyRequestMatcherInterface;
use Symnedi\Security\Contract\DI\ModularFirewallInterface;


/**
 * Mimics @see \Symfony\Component\HttpFoundation\RequestMatcherInterface
 */
interface RequestMatcherInterface extends SymfonyRequestMatcherInterface, ModularFirewallInterface
{

}
