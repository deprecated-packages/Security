<?php

declare(strict_types=1);

/*
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\Core\Authentication\Token;

use Nette\Security\Identity;
use Nette\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symnedi\Security\Exception\NotImplementedException;

final class NetteTokenAdapter implements TokenInterface
{
    /**
     * @var User
     */
    private $user;

    public function serialize()
    {
        throw new NotImplementedException();
    }

    public function unserialize($serialized)
    {
        throw new NotImplementedException();
    }

    public function __toString()
    {
        throw new NotImplementedException();
    }

    public function getRoles()
    {
        return $this->user->getRoles();
    }

    public function getCredentials()
    {
        return $this->user->getIdentity();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUsername()
    {
        throw new NotImplementedException();
    }

    public function isAuthenticated()
    {
        return $this->user->isLoggedIn();
    }

    public function setAuthenticated($isAuthenticated)
    {
        $this->user->getStorage()->setAuthenticated($isAuthenticated);
    }

    public function eraseCredentials()
    {
        throw new NotImplementedException();
    }

    public function getAttributes()
    {
        /** @var Identity $identity */
        $identity = $this->user->getIdentity();

        return $identity->getData();
    }

    public function setAttributes(array $attributes)
    {
        throw new NotImplementedException();
    }

    public function hasAttribute($name)
    {
        throw new NotImplementedException();
    }

    public function getAttribute($name)
    {
        throw new NotImplementedException();
    }

    public function setAttribute($name, $value)
    {
        throw new NotImplementedException();
    }
}
