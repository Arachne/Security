<?php

namespace Arachne\Security\DI;

use Arachne\DIHelpers\DI\DIHelpersExtension;
use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class SecurityExtension extends CompilerExtension
{

	const TAG_AUTHORIZATOR = 'arachne.security.authorizator';
	const TAG_FIREWALL = 'arachne.security.firewall';
	const TAG_IDENTITY_VALIDATOR = 'arachne.security.identityValidator';

	/** @var array */
	public $defaults = [
		'firewalls' => [],
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		Validators::assertField($config, 'firewalls', 'array');

		foreach ($config['firewalls'] as $firewall) {
			$builder->addDefinition($this->prefix('storage.' . $firewall))
				->setClass('Arachne\Security\UserStorage')
				->addSetup('setNamespace', $firewall)
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('firewall.' . $firewall))
				->setClass('Arachne\Security\Firewall')
				->setArguments([
					'storage' => $this->prefix('@storage.' . $firewall),
				])
				->addTag(self::TAG_FIREWALL, $firewall)
				->setAutowired(FALSE);
		}

		$this->addDefinition($this->prefix('firewallResolver'))
			->addTag(DIHelpersExtension::TAG_RESOLVER, self::TAG_FIREWALL);

		$this->addDefinition($this->prefix('authorizatorResolver'))
			->addTag(DIHelpersExtension::TAG_RESOLVER, self::TAG_AUTHORIZATOR);
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		foreach ($builder->findByTag(self::TAG_IDENTITY_VALIDATOR) as $name => $firewall) {
			if ($builder->hasDefinition($this->prefix('storage.' . $firewall))) {
				$builder->getDefinition($this->prefix('storage.' . $firewall))
					->setArguments([
						'identityValidator' => '@' . $name,
						'namespace' => $firewall,
					]);
			} else {
				throw new AssertionException("Identity validator '$name' of firewall '$firewall' could not be passed to corresponding storage.");
			}
		}
	}

}
