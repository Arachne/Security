<?php

declare(strict_types=1);

namespace Arachne\Security\Authorization;

use Arachne\Security\Authentication\FirewallInterface;
use Nette\Security\IResource;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionAuthorizator implements AuthorizatorInterface
{
    const AUTHENTICATED_ROLE = '__authenticated';
    const GUEST_ROLE = '__guest';

    /**
     * @var FirewallInterface
     */
    private $firewall;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param FirewallInterface $firewall
     * @param Permission        $permission
     */
    public function __construct(FirewallInterface $firewall, Permission $permission)
    {
        $this->firewall = $firewall;
        $this->permission = $permission;
        $this->permission->addRole(self::AUTHENTICATED_ROLE);
        $this->permission->addRole(self::GUEST_ROLE);
    }

    /**
     * @param string|IResource $resource
     */
    public function isAllowed($resource, string $privilege): bool
    {
        $identity = $this->firewall->getIdentity();
        $this->permission->setIdentity($identity);
        if ($identity !== null) {
            $roles = $identity->getRoles();
            // Add a role to make sure even identities without any roles will invoke permission.
            $roles[] = self::AUTHENTICATED_ROLE;
        } else {
            $roles = [self::GUEST_ROLE];
        }

        foreach ($roles as $role) {
            if ($this->permission->isAllowed($role, $resource, $privilege)) {
                return true;
            }
        }

        return false;
    }
}
