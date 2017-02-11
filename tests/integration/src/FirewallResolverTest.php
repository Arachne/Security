<?php

namespace Tests\Integration;

use Arachne\Security\Authentication\Firewall;
use Codeception\Test\Unit;
use Nette\DI\Container;
use Nette\Http\Session;
use Nette\Security\Identity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class FirewallResolverTest extends Unit
{
    protected $tester;

    /**
     * @var callable
     */
    private $resolver;

    public function _before()
    {
        $this->resolver = $this->tester->grabService(Container::class)->getService('arachne.servicecollections.1.arachne.security.firewall');
    }

    public function testIdentityValidator()
    {
        $session = $this->tester->grabService(Session::class);

        $section = $session->getSection('Nette.Http.UserStorage/admin');
        $section->authenticated = true;
        $section->identity = new Identity(1);
        $section->identity->validated = false;

        $firewall = call_user_func($this->resolver, 'admin');

        $this->assertInstanceOf(Firewall::class, $firewall);
        $this->assertTrue($firewall->getIdentity()->validated);
    }
}
