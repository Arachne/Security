<?php

namespace Tests\Integration;

use Arachne\Codeception\Module\NetteDIModule;
use Arachne\Security\Authorization\AuthorizatorInterface;
use Codeception\Test\Unit;
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

    public function _before()
    {
        $this->resolver = $this->tester->grabService(Container::class)->getService('arachne.serviceCollections.1.arachne.security.authorizator');
    }

    public function testIdentityValidator()
    {
        $this->assertInstanceOf(AuthorizatorInterface::class, call_user_func($this->resolver, 'admin'));
    }
}
