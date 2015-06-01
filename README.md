# Symnedi/Security

[![Build Status](https://img.shields.io/travis/Symnedi/Security.svg?style=flat-square)](https://travis-ci.org/Symnedi/Security)
[![Quality Score](https://img.shields.io/scrutinizer/g/Symnedi/Security.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/Security)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Symnedi/Security.svg?style=flat-square)](https://scrutinizer-ci.com/g/Symnedi/Security)
[![Downloads this Month](https://img.shields.io/packagist/dm/symnedi/security.svg?style=flat-square)](https://packagist.org/packages/symnedi/security)
[![Latest stable](https://img.shields.io/packagist/v/symnedi/security.svg?style=flat-square)](https://packagist.org/packages/symnedi/security)


## Install

Via Composer:

```sh
$ composer require symnedi/security
```

Register the extension in `config.neon`:

```yaml
extensions:
	symfonySecurity: Symnedi\Security\DI\SecurityExtension
```


## Usage

### Voters

First, [read Symfony cookbook](http://symfony.com/doc/current/cookbook/security/voters_data_permission.html)

Then create your voter implementing `Symfony\Component\Security\Core\Authorization\Voter\VoterInterface`
and register it as service in your `config.neon`:

```yaml
services:
	- App\SomeModule\Security\Voter\MyVoter
```

Then in place, where you need to validate access, just use `AuthorizationChecker`:


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

All you need to create is a matcher and a listener.

First, we create matcher that will match all sites in admin module - urls starting with `/admin`:

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

Then we create listener, that will check user is logged and with 'admin' role.
Otherwise redirect.

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


## Testing

```sh
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
