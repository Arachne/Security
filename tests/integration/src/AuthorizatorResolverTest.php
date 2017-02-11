<?php

namespace Tests\Integration;

use Arachne\DIHelpers\ResolverInterface;
use Arachne\Security\Authorization\AuthorizatorInterface;
use Codeception\TestCase\Test;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class AuthorizatorResolverTest extends Test
{
    protected $tester;

	/**
     * @var ResolverInterface
     */
	private $resolver;

	public function _before()
	{
		$this->resolver = $this->tester->grabService(Container::class)->getService('arachne.dihelpers.resolvers.tag.arachne.security.authorizator');
	}

	public function testIdentityValidator()
	{
		$this->assertInstanceOf(AuthorizatorInterface::class, $this->resolver->resolve('admin'));
	}

}
