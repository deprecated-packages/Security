<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Core\Authorization;

use Symnedi\Security\Contract\Core\Authorization\AccessDecisionManagerFactoryInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


class AccessDecisionManagerFactory implements AccessDecisionManagerFactoryInterface
{

	/**
	 * @var VoterInterface[]
	 */
	private $voters = [];


	/**
	 * {@inheritdoc}
	 */
	public function addVoter(VoterInterface $voter)
	{
		$this->voters[] = $voter;
	}


	/**
	 * {@inheritdoc}
	 */
	public function create()
	{
		return new AccessDecisionManager($this->voters, AccessDecisionManager::STRATEGY_UNANIMOUS, TRUE);
	}

}