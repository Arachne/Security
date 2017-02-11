<?php

namespace Arachne\Security\DI;

use Arachne\DIHelpers\CompilerExtension;
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
        $this->validateConfig($this->defaults);
        Validators::assertField($this->config, 'firewalls', 'array');

        foreach ($this->compiler->getExtensions('Arachne\Security\DI\FirewallProviderInterface') as $extension) {
            $firewalls = $extension->getFirewalls();
            Validators::assert($firewalls, 'array');
            $this->config['firewalls'] = array_merge($this->config['firewalls'], $firewalls);
        }

        foreach ($this->config['firewalls'] as $firewall => $class) {
            if (!is_string($firewall)) {
                $this->addFirewall($class);
            } else {
                $this->addFirewall($firewall, $class);
            }
        }

        if ($extension = $this->getExtension('Arachne\DIHelpers\DI\ResolversExtension', false)) {
            $extension->add(self::TAG_FIREWALL, 'Arachne\Security\Authentication\FirewallInterface');
            $extension->add(self::TAG_AUTHORIZATOR, 'Arachne\Security\Authorization\AuthorizatorInterface');
        } elseif ($extension = $this->getExtension('Arachne\DIHelpers\DI\DIHelpersExtension', false)) {
            $extension->addResolver(self::TAG_FIREWALL, 'Arachne\Security\Authentication\FirewallInterface');
            $extension->addResolver(self::TAG_AUTHORIZATOR, 'Arachne\Security\Authorization\AuthorizatorInterface');
        } else {
            throw new AssertionException('Cannot add resolvers because arachne/di-helpers is not properly installed.');
        }
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();

        foreach ($builder->findByTag(self::TAG_IDENTITY_VALIDATOR) as $name => $firewall) {
            if ($builder->hasDefinition($this->prefix('storage.'.$firewall))) {
                $builder->getDefinition($this->prefix('storage.'.$firewall))
                    ->setArguments([
                        'namespace' => $firewall,
                        'identityValidator' => '@'.$name,
                    ]);
            } else {
                throw new AssertionException("Identity validator '$name' of firewall '$firewall' could not be passed to corresponding storage.");
            }
        }
    }

    public function addFirewall($firewall, $class = 'Arachne\Security\Authentication\Firewall')
    {
        $builder = $this->getContainerBuilder();

        $service = $builder->addDefinition($this->prefix('firewall.'.$firewall))
            ->setClass($class)
            ->addTag(self::TAG_FIREWALL, $firewall);

        if ($class === 'Arachne\Security\Authentication\Firewall' || is_subclass_of($class, 'Arachne\Security\Authentication\Firewall')) {
            $builder->addDefinition($this->prefix('storage.'.$firewall))
                ->setClass('Arachne\Security\Authentication\UserStorage')
                ->setArguments([
                    'namespace' => $firewall,
                ])
                ->setAutowired(false);

            $service->setArguments([
                'storage' => $this->prefix('@storage.'.$firewall),
            ]);
        }
    }
}
