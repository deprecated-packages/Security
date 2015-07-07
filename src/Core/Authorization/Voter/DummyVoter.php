<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Core\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


class DummyVoter implements VoterInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function supportsAttribute($attribute)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function supportsClass($class)
	{
	}


	/**
	 * {@inheritdoc}
	 */
	public function vote(TokenInterface $token, $object, array $attributes)
	{
		return self::ACCESS_ABSTAIN;
	}

}
