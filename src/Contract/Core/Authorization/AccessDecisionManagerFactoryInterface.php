<?php

declare(strict_types=1);

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\Core\Authorization;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

interface AccessDecisionManagerFactoryInterface
{
    public function addVoter(VoterInterface $voter);

    public function create() : AccessDecisionManagerInterface;
}
