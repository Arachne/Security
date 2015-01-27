<?php

namespace Tests\Integration;

use Arachne\DIHelpers\ResolverInterface;
use Arachne\Security\Firewall;
use Kdyby\FakeSession\Session;
use Nette\DI\Container;
use Nette\Security\Identity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class FirewallResolverTest extends Test
{

	/** @var ResolverInterface */
	private $resolver;

	public function _before()
	{
		$this->resolver = $this->guy->grabService(Container::class)->getService('arachne.dihelpers.resolver.arachne.security.firewall');
	}

	public function testIdentityValidator()
	{
		$session = $this->guy->grabService(Session::class);
		$session->exists = TRUE;

		$section = $session->getSection('Nette.Http.UserStorage/admin');
		$section->authenticated = TRUE;
		$section->identity = new Identity(1);
		$section->identity->validated = FALSE;

		$firewall = $this->resolver->resolve('admin');

		$this->assertInstanceOf(Firewall::class, $firewall);
		$this->assertTrue($firewall->getIdentity()->validated);
	}

}
