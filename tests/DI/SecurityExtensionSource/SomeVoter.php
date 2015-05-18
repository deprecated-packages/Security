<?php

namespace Symnedi\Security\Tests\DI\SecurityExtensionSource;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;


class SomeVoter implements VoterInterface
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
	}

}
