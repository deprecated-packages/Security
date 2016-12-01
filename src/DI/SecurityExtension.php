<?php

declare(strict_types=1);

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symnedi\Security\Contract\Http\FirewallHandlerInterface;
use Symnedi\Security\Contract\Http\FirewallMapFactoryInterface;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;

final class SecurityExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        Compiler::loadDefinitions(
            $this->getContainerBuilder(),
            $this->loadFromFile(__DIR__ . '/services.neon')['services']
        );
    }

    public function beforeCompile()
    {
        $containerBuilder = $this->getContainerBuilder();

        $this->loadAccessDecisionManagerFactoryWithVoters();

        if ($containerBuilder->findByType(FirewallHandlerInterface::class)) {
            $this->loadFirewallMap();
        }
    }

    private function loadAccessDecisionManagerFactoryWithVoters()
    {
        $this->loadMediator(AccessDecisionManagerFactoryInterface::class, VoterInterface::class, 'addVoter');
    }

    private function loadFirewallMap()
    {
        $this->loadMediator(FirewallMapFactoryInterface::class, FirewallHandlerInterface::class, 'addFirewallHandler');
        $this->loadMediator(FirewallMapFactoryInterface::class, RequestMatcherInterface::class, 'addRequestMatcher');
    }

    private function loadMediator(string $mediatorClass, string $colleagueClass, string $adderMethod)
    {
        $containerBuilder = $this->getContainerBuilder();

        $mediatorDefinition = $containerBuilder->getDefinition($containerBuilder->getByType($mediatorClass));
        foreach ($containerBuilder->findByType($colleagueClass) as $colleagueDefinition) {
            $mediatorDefinition->addSetup($adderMethod, ['@' . $colleagueDefinition->getClass()]);
        }
    }
}
