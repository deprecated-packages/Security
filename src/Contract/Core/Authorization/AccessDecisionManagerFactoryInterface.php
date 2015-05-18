<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Contract\Core\Authorization;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


interface AccessDecisionManagerFactoryInterface
{

	/**
	 * @param VoterInterface $voter
	 */
	function addVoter(VoterInterface $voter);


	/**
	 * @return AccessDecisionManagerInterface
	 */
	function create();

}
