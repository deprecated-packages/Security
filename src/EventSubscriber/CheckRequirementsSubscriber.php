<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\EventSubscriber;

use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symnedi\Security\Nette\ApplicationEvents;
use Symnedi\Security\Nette\Events;


class CheckRequirementsSubscriber implements EventSubscriberInterface
{

	/**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorizationChecker;


	public function __construct(AuthorizationCheckerInterface $authorizationChecker)
	{
		$this->authorizationChecker = $authorizationChecker;
	}


	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return [ApplicationEvents::ON_PRESENTER => 'onPresenter'];
	}


	public function onPresenter(Application $application, Presenter $presenter)
	{
		$this->authorizationChecker->isGranted('access', $presenter->getReflection());
	}

}
