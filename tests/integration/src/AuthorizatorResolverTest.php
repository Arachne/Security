<?php

namespace Tests\Integration;

use Arachne\Security\Authorization\AuthorizatorInterface;
use Codeception\Test\Unit;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class AuthorizatorResolverTest extends Unit
{
    protected $tester;

    /**
     * @var callable
     */
    private $resolver;

    public function _before()
    {
        $this->resolver = $this->tester->grabService(Container::class)->getService('arachne.servicecollections.1.arachne.security.authorizator');
    }

    public function testIdentityValidator()
    {
        $this->assertInstanceOf(AuthorizatorInterface::class, ($this->resolver)('admin'));
    }
}
