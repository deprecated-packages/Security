<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Core\Authentication;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


/**
 * Dummy implementation with no custom logic,
 * just to pass Token back.
 */
class AuthenticationManager implements AuthenticationManagerInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function authenticate(TokenInterface $token)
	{
		return $token;
	}

}
