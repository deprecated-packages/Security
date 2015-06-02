<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Core\Authentication\Token;

use Nette\Security\Identity;
use Nette\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symnedi\Security\Exception\NotImplementedException;


class NetteTokenAdapter implements TokenInterface
{

	/**
	 * @var User
	 */
	private $user;


	public function __construct(User $user)
	{
		$this->user = $user;
	}



	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getRoles()
	{
		return $this->user->getRoles();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getCredentials()
	{
		return $this->user->getIdentity();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getUser()
	{
		return $this->user;
	}


	/**
	 * {@inheritdoc}
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getUsername()
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function isAuthenticated()
	{
		return $this->user->isLoggedIn();
	}


	/**
	 * {@inheritdoc}
	 */
	public function setAuthenticated($isAuthenticated)
	{
		$this->user->getStorage()->setAuthenticated($isAuthenticated);
	}


	/**
	 * {@inheritdoc}
	 */
	public function eraseCredentials()
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAttributes()
	{
		/** @var Identity $identity */
		$identity = $this->user->getIdentity();
		return $identity->getData();
	}


	/**
	 * {@inheritdoc}
	 */
	public function setAttributes(array $attributes)
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function hasAttribute($name)
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getAttribute($name)
	{
		throw new NotImplementedException;
	}


	/**
	 * {@inheritdoc}
	 */
	public function setAttribute($name, $value)
	{
		throw new NotImplementedException;
	}

}
