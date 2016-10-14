# Symnedi/Security

[![Build Status](https://img.shields.io/travis/Symnedi/Security.svg?style=flat-square)](https://travis-ci.org/Symnedi/Security)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symnedi/Security.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/Security)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symnedi/Security.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/Security)
[![Downloads](https://img.shields.io/packagist/dt/symnedi/security.svg?style=flat-square)](htptps://packagist.org/packages/symnedi/security)
[![Latest stable](https://img.shields.io/packagist/v/symnedi/security.svg?style=flat-square)](https://packagist.org/packages/symnedi/security)


## Install

```sh
composer require symnedi/security
```

Register the extension:

```neon
# app/config/config.neon
extensions:
	- Symnedi\Security\DI\SecurityExtension
	- Symnedi\EventDispatcher\DI\EventDispatcherExtension
```


## Usage

### Voters

First, [read Symfony cookbook](http://symfony.com/doc/current/cookbook/security/voters_data_permission.html)

Then create new voter implementing `Symfony\Component\Security\Core\Authorization\Voter\VoterInterface`
and register it as service in `config.neon`:

```yaml
services:
	- App\SomeModule\Security\Voter\MyVoter
```

Then in place, where we need to validate access, we'll just use `AuthorizationChecker`:


```php
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class Presenter
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
	 * @param PresenterComponentReflection $element
	 */
	public function checkRequirements($element)
	{
		if ($this->authorizationChecker->isGranted('access', $element) === FALSE) {
			throw new ForbiddenRequestException;
		}
	}

}
```


### Firewalls

Original [Symfony firewalls](http://symfony.com/doc/current/components/security/firewall.html) pretty simplified and with modular support by default.

All we need to create is a **matcher** and a **listener**.

#### Request Matcher 

This service will match all sites in admin module - urls starting with `/admin`:

```php
use Symfony\Component\HttpFoundation\Request;
use Symnedi\Security\Contract\HttpFoundation\RequestMatcherInterface;


class AdminRequestMatcher implements RequestMatcherInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function getFirewallName()
	{
		return 'adminSecurity';
	}
	
	
	/**
	 * {@inheritdoc}
	 */
	public function matches(Request $request)
	{
		$url = $request->getPathInfo();
		return strpos($url, '/admin') === 0;
	}

}
```


### Firewall Listener

It will ensure that user is logged in and has 'admin' role, otherwise redirect.

```php
use Nette\Application\AbortException;
use Nette\Application\Application;
use Nette\Application\Request;
use Nette\Security\User;
use Symnedi\Security\Contract\Http\FirewallListenerInterface;


class LoggedAdminFirewallListener implements FirewallListenerInterface
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
	public function getFirewallName()
	{
		return 'adminSecurity';
	}

	
	/**
	 * {@inheritdoc}
	 */
	public function handle(Application $application, Request $applicationRequest)
	{
		if ( ! $this->user->isLoggedIn()) {
			throw new AbortException;
		}

		if ( ! $this->user->isInRole('admin')) {
			throw new AbortException;
		}
	}

}
```


Then we register both services.

```yaml
services:
	- AdminRequestMatcher
	- LoggedAdminFirewallListener
```

That's it!

```bash
composer check-cs # see "scripts" section of composer.json for more details 
vendor/bin/phpunit
```


## Contributing

Rules are simple:

- new feature needs tests
- all tests must pass
- 1 feature per PR

We would be happy to merge your feature then!
