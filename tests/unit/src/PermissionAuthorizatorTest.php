<?php

namespace Tests\Unit;

use Arachne\Security\Authentication\FirewallInterface;
use Arachne\Security\Authorization\Permission;
use Arachne\Security\Authorization\PermissionAuthorizator;
use Codeception\TestCase\Test;
use Mockery;
use Mockery\MockInterface;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionAuthorizatorTest extends Test
{

	/** @var MockInterface */
	private $firewall;

	/** @var MockInterface */
	private $permission;

	/** @var PermissionAuthorizator */
	private $authorizator;

	protected function _before()
	{
		$this->firewall = Mockery::mock(FirewallInterface::class);
		$this->permission = Mockery::mock(Permission::class);
		$this->authorizator = new PermissionAuthorizator($this->firewall, $this->permission);
	}

	public function testRoles()
	{
		$identity = Mockery::mock(IIdentity::class);
		$identity
			->shouldReceive('getRoles')
			->once()
			->andReturn([
				'role1',
				'role2',
				'role3',
			]);

		$this->firewall
			->shouldReceive('getIdentity')
			->once()
			->andReturn($identity);

		$this->permission
			->shouldReceive('setIdentity')
			->once()
			->with($identity);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with('role1', 'resource', 'privilege')
			->andReturn(false);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with('role2', 'resource', 'privilege')
			->andReturn(true);

		$this->assertTrue($this->authorizator->isAllowed('resource', 'privilege'));
	}

	public function testIdentityWithNoRoles()
	{
		$identity = Mockery::mock(IIdentity::class);
		$identity
			->shouldReceive('getRoles')
			->once()
			->andReturn([]);

		$this->firewall
			->shouldReceive('getIdentity')
			->once()
			->andReturn($identity);

		$this->permission
			->shouldReceive('setIdentity')
			->once()
			->with($identity);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with(null, 'resource', 'privilege')
			->andReturn(true);

		$this->assertTrue($this->authorizator->isAllowed('resource', 'privilege'));
	}

	public function testGuestRole()
	{
		$this->firewall
			->shouldReceive('getIdentity')
			->once()
			->andReturn(null);

		$this->permission
			->shouldReceive('setIdentity')
			->once()
			->with(null);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with('my_guest', 'resource', 'privilege')
			->andReturn(false);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with(null, 'resource', 'privilege')
			->andReturn(false);

		$this->authorizator->guestRole = 'my_guest';

		$this->assertFalse($this->authorizator->isAllowed('resource', 'privilege'));
	}

}
