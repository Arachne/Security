<?php

namespace Tests\Unit;

use Arachne\Security\Authorization\Permission;
use Codeception\MockeryModule\Test;
use Mockery;
use Nette\Security\IIdentity;
use Nette\Security\IResource;
use Nette\Security\IRole;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionTest extends Test
{

	/** @var Permission */
	private $permission;

	protected function _before()
	{
		$this->permission = new Permission();
	}

	public function testAllow()
	{
		$identity = Mockery::mock(IIdentity::class);

		$resource = Mockery::mock(IResource::class);
		$resource->shouldReceive('getResourceId')
			->once()
			->andReturn('resource');

		$role = Mockery::mock(IRole::class);
		$role->shouldReceive('getRoleId')
			->once()
			->andReturn('role');

		$mock = Mockery::mock();
		$mock->shouldReceive('assert')
			->once()
			->with($identity, $resource, $role)
			->andReturn(true);

		$this->permission->addResource('resource');
		$this->permission->addRole('role');
		$this->permission->allow('role', 'resource', 'privilege', function (IIdentity $identity, IResource $resource, IRole $role) use ($mock) {
			return $mock->assert($identity, $resource, $role);
		});

		$this->permission->setIdentity($identity);

		$this->assertTrue($this->permission->isAllowed($role, $resource, 'privilege'));
	}

	public function testDeny()
	{
		$identity = Mockery::mock(IIdentity::class);

		$resource = Mockery::mock(IResource::class);
		$resource->shouldReceive('getResourceId')
			->once()
			->andReturn('resource');

		$role = Mockery::mock(IRole::class);
		$role->shouldReceive('getRoleId')
			->once()
			->andReturn('role');

		$mock = Mockery::mock();
		$mock->shouldReceive('assert')
			->once()
			->with($identity, $resource, $role)
			->andReturn(true);

		$this->permission->addResource('resource');
		$this->permission->addRole('role');
		$this->permission->allow('role', 'resource');
		$this->permission->deny('role', 'resource', 'privilege', function (IIdentity $identity, IResource $resource, IRole $role) use ($mock) {
			return $mock->assert($identity, $resource, $role);
		});

		$this->permission->setIdentity($identity);

		$this->assertFalse($this->permission->isAllowed($role, $resource, 'privilege'));
	}

}
