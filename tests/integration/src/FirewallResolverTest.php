<?php

namespace Tests\Integration;

use Arachne\Codeception\Module\NetteDIModule;
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
        $this->resolver = $this->tester->grabService(Container::class)->getService('arachne.serviceCollections.1.arachne.security.firewall');
    }

    public function testIdentityValidator(): void
    {
        /** @var Session $session */
        $session = $this->tester->grabService(Session::class);

        $section = $session->getSection('Nette.Http.UserStorage/admin');
        $section->authenticated = true;
        $section->identity = new Identity(1);
        $section->identity->validated = false;

        $firewall = call_user_func($this->resolver, 'admin');

        self::assertInstanceOf(Firewall::class, $firewall);
        self::assertTrue($firewall->getIdentity()->validated);
    }
}
