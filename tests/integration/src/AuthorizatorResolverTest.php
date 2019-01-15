<?php

declare(strict_types=1);

namespace Tests\Integration;

use Arachne\Security\Authorization\AuthorizatorInterface;
use Codeception\Test\Unit;
use Contributte\Codeception\Module\NetteDIModule;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class AuthorizatorResolverTest extends Unit
{
    /**
     * @var NetteDIModule
     */
    protected $tester;

    /**
     * @var callable
     */
    private $resolver;

    public function _before(): void
    {
        $this->resolver = $this->tester->grabService(Container::class)->getService('arachne.serviceCollections.1.arachne.security.authorizator');
    }

    public function testIdentityValidator(): void
    {
        self::assertInstanceOf(AuthorizatorInterface::class, call_user_func($this->resolver, 'admin'));
    }
}
