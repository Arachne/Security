<?php

namespace Tests\Integration;

use Arachne\DIHelpers\ResolverInterface;
use Arachne\Security\AuthorizatorInterface;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class AuthorizatorResolverTest extends Test
{

	/** @var ResolverInterface */
	private $resolver;

	public function _before()
	{
		$this->resolver = $this->guy->grabService(Container::class)->getService('arachne.security.authorizatorResolver');
	}

	public function testIdentityValidator()
	{
		$this->assertInstanceOf(AuthorizatorInterface::class, $this->resolver->resolve('admin'));
	}

}
