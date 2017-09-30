<?php

namespace Arachne\Security\DI;

use Arachne\Security\Authentication\Firewall;
use Arachne\Security\Authentication\FirewallInterface;
use Arachne\Security\Authentication\UserStorage;
use Arachne\Security\Authorization\AuthorizatorInterface;
use Arachne\ServiceCollections\DI\ServiceCollectionsExtension;
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

    /**
     * @var array
     */
    public $defaults = [
        'firewalls' => [],
    ];

    public function loadConfiguration(): void
    {
        $this->validateConfig($this->defaults);
        Validators::assertField($this->config, 'firewalls', 'array');

        foreach ($this->config['firewalls'] as $firewall => $class) {
            if (!is_string($firewall)) {
                $this->addFirewall($class);
            } else {
                $this->addFirewall($firewall, $class);
            }
        }

        /** @var ServiceCollectionsExtension $serviceCollectionsExtension */
        $serviceCollectionsExtension = $this->getExtension(ServiceCollectionsExtension::class);
        $serviceCollectionsExtension->getCollection(ServiceCollectionsExtension::TYPE_RESOLVER, self::TAG_FIREWALL, FirewallInterface::class);
        $serviceCollectionsExtension->getCollection(ServiceCollectionsExtension::TYPE_RESOLVER, self::TAG_AUTHORIZATOR, AuthorizatorInterface::class);
    }

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();

        foreach ($builder->findByTag(self::TAG_IDENTITY_VALIDATOR) as $name => $firewall) {
            if ($builder->hasDefinition($this->prefix('storage.'.$firewall))) {
                $builder
                    ->getDefinition($this->prefix('storage.'.$firewall))
                    ->setArguments(
                        [
                            'namespace' => $firewall,
                            'identityValidator' => '@'.$name,
                        ]
                    );
            } else {
                throw new AssertionException("Identity validator '$name' of firewall '$firewall' could not be passed to corresponding storage.");
            }
        }
    }

    public function addFirewall(string $firewall, string $class = Firewall::class): void
    {
        $builder = $this->getContainerBuilder();

        $service = $builder
            ->addDefinition($this->prefix('firewall.'.$firewall))
            ->setType($class)
            ->addTag(self::TAG_FIREWALL, $firewall);

        if ($class === Firewall::class || is_subclass_of($class, Firewall::class)) {
            $builder
                ->addDefinition($this->prefix('storage.'.$firewall))
                ->setType(UserStorage::class)
                ->setArguments(
                    [
                        'namespace' => $firewall,
                    ]
                )
                ->setAutowired(false);

            $service->setArguments(
                [
                    'storage' => $this->prefix('@storage.'.$firewall),
                ]
            );
        }
    }

    /**
     * @throws AssertionException
     */
    private function getExtension(string $class): CompilerExtension
    {
        $extensions = $this->compiler->getExtensions($class);

        if (!$extensions) {
            throw new AssertionException(
                sprintf('Extension "%s" requires "%s" to be installed.', get_class($this), $class)
            );
        }

        return reset($extensions);
    }
}
