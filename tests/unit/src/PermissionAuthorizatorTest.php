<?php

declare(strict_types=1);

namespace Tests\Unit;

use Arachne\Security\Authentication\FirewallInterface;
use Arachne\Security\Authorization\Permission;
use Arachne\Security\Authorization\PermissionAuthorizator;
use Codeception\Test\Unit;
use Eloquent\Phony\Mock\Handle\InstanceHandle;
use Eloquent\Phony\Phpunit\Phony;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionAuthorizatorTest extends Unit
{
    /**
     * @var InstanceHandle
     */
    private $firewallHandle;

    /**
     * @var InstanceHandle
     */
    private $permissionHandle;

    /**
     * @var PermissionAuthorizator
     */
    private $authorizator;

    protected function _before(): void
    {
        $this->firewallHandle = Phony::mock(FirewallInterface::class);
        $this->permissionHandle = Phony::mock(Permission::class);
        $this->authorizator = new PermissionAuthorizator($this->firewallHandle->get(), $this->permissionHandle->get());
    }

    public function testAddedRoles(): void
    {
        $this->permissionHandle
            ->addRole
            ->calledWith(PermissionAuthorizator::AUTHENTICATED_ROLE);

        $this->permissionHandle
            ->addRole
            ->calledWith(PermissionAuthorizator::GUEST_ROLE);
    }

    public function testRoles(): void
    {
        $identityHandle = Phony::mock(IIdentity::class);
        $identityHandle
            ->getRoles
            ->returns(
                [
                    'role1',
                    'role2',
                    'role3',
                ]
            );

        $identity = $identityHandle->get();

        $this->firewallHandle->getIdentity->returns($identityHandle);

        $this->permissionHandle
            ->isAllowed
            ->with('role1', 'resource', 'privilege')
            ->returns(false);

        $this->permissionHandle
            ->isAllowed
            ->with('role2', 'resource', 'privilege')
            ->returns(true);

        self::assertTrue($this->authorizator->isAllowed('resource', 'privilege'));

        $this->permissionHandle
            ->setIdentity
            ->calledWith($identity);
    }

    public function testIdentityWithNoRoles(): void
    {
        $identityHandle = Phony::mock(IIdentity::class);
        $identityHandle
            ->getRoles
            ->returns([]);

        $identity = $identityHandle->get();

        $this->firewallHandle
            ->getIdentity
            ->returns($identity);

        $this->permissionHandle
            ->isAllowed
            ->with(PermissionAuthorizator::AUTHENTICATED_ROLE, 'resource', 'privilege')
            ->returns(true);

        self::assertTrue($this->authorizator->isAllowed('resource', 'privilege'));

        $this->permissionHandle
            ->setIdentity
            ->calledWith($identity);
    }

    public function testGuestRole(): void
    {
        $this->firewallHandle
            ->getIdentity
            ->returns(null);

        $this->permissionHandle
            ->isAllowed
            ->with(PermissionAuthorizator::GUEST_ROLE, 'resource', 'privilege')
            ->returns(false);

        self::assertFalse($this->authorizator->isAllowed('resource', 'privilege'));

        $this->permissionHandle
            ->setIdentity
            ->calledWith(null);
    }
}
