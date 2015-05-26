<?php

/**
 * This file is part of Symnedi.
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Symnedi\Security\EventSubscriber;

use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class CheckRequirementsSubscriber implements Subscriber
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
	public function getSubscribedEvents()
	{
		return [Application::class . '::onPresenter'];
	}


	public function onPresenter(Application $application, Presenter $presenter)
	{
		$this->authorizationChecker->isGranted('access', $presenter->getReflection());
	}

}
