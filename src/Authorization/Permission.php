<?php

namespace Arachne\Security\Authorization;

use Nette\Security\IIdentity;
use Nette\Security\Permission as BasePermission;
use Nette\Utils\Callback;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Permission extends BasePermission
{
    /**
     * @var IIdentity
     */
    private $identity;

    /**
     * @param IIdentity $identity
     */
    public function setIdentity(IIdentity $identity = null)
    {
        $this->identity = $identity;
    }

    /**
     * Allows one or more Roles access to [certain $privileges upon] the specified Resource(s).
     * If $assertion is provided, then it must return TRUE in order for rule to apply.
     *
     * @param string|array|Permission::ALL $roles
     * @param string|array|Permission::ALL $resources
     * @param string|array|Permission::ALL $privileges
     * @param callable|null                $assertion
     *
     * @return self
     */
    public function allow($roles = self::ALL, $resources = self::ALL, $privileges = self::ALL, $assertion = null)
    {
        if ($assertion !== null) {
            $assertion = function () use ($assertion) {
                return Callback::invoke($assertion, $this->identity, $this->getQueriedResource(), $this->getQueriedRole());
            };
        }

        return parent::allow($roles, $resources, $privileges, $assertion);
    }

    /**
     * Denies one or more Roles access to [certain $privileges upon] the specified Resource(s).
     * If $assertion is provided, then it must return TRUE in order for rule to apply.
     *
     * @param string|array|Permission::ALL $roles
     * @param string|array|Permission::ALL $resources
     * @param string|array|Permission::ALL $privileges
     * @param callable|null                $assertion
     *
     * @return self
     */
    public function deny($roles = self::ALL, $resources = self::ALL, $privileges = self::ALL, $assertion = null)
    {
        if ($assertion !== null) {
            $assertion = function () use ($assertion) {
                return Callback::invoke($assertion, $this->identity, $this->getQueriedResource(), $this->getQueriedRole());
            };
        }

        return parent::deny($roles, $resources, $privileges, $assertion);
    }
}
