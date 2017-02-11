<?php

namespace Arachne\Security\Authorization;

use Arachne\Security\Authentication\FirewallInterface;
use Nette\Object;
use Nette\Security\IResource;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionAuthorizator extends Object implements AuthorizatorInterface
{
    /** @var string */
    public $guestRole = 'guest';

    /** @var FirewallInterface */
    private $firewall;

    /** @var Permission */
    private $permission;

    /**
     * @param FirewallInterface $firewall
     * @param Permission        $permission
     */
    public function __construct(FirewallInterface $firewall, Permission $permission)
    {
        $this->firewall = $firewall;
        $this->permission = $permission;
    }

    /**
     * @param string|IResource $resource
     * @param string           $privilege
     * @retrun bool
     */
    public function isAllowed($resource, $privilege)
    {
        $identity = $this->firewall->getIdentity();
        $this->permission->setIdentity($identity);
        $roles = $identity ? $identity->getRoles() : [$this->guestRole];
        $roles[] = null;

        foreach ($roles as $role) {
            if ($this->permission->isAllowed($role, $resource, $privilege)) {
                return true;
            }
        }

        return false;
    }
}
