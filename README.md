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

Then in place, where you need to validate access, just use `AccessDecisionManager`:


```php
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;


class Presenter
{

	/**
	 * @var AccessDecisionManager
	 */
	private $accessDecisionManager;

	public function __construct(AccessDecisionManager $accessDecisionManager)
	{
		$this->accessDecisionManager = $accessDecisionManager;
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

Original [Symfony firewalls](http://symfony.com/doc/current/components/security/firewall.html) pretty simplified.

All you need to create is a matcher and a listener.


```yaml
// config.neon
services:
	- AppSomeModule\Security\AdminFirewall\RequestMatcher
	- AppSomeModule\Security\AdminFirewall\SecurityListener
```

Then bound them together in configuration.

```yaml
// config.neon
symfonySecurity:
	firewalls:
		adminFirewall:
			requestMatcher: @AppSomeModule\Security\AdminFirewall\RequestMatcher
			securityListener: @AppSomeModule\Security\AdminFirewall\SecurityListener
```


## Testing

```sh
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
