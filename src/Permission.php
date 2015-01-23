<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\Security;

use Nette\Security\IIdentity;
use Nette\Security\Permission as BasePermission;
use Nette\Utils\Callback;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Permission extends BasePermission
{

	/** @var IIdentity */
	private $identity;

	/**
	 * @param IIdentity $identity
	 */
	public function setIdentity(IIdentity $identity = NULL)
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
	 * @param callable $assertion
	 * @return self
	 */
	public function allow($roles = self::ALL, $resources = self::ALL, $privileges = self::ALL, $assertion = NULL)
	{
		if ($assertion !== NULL) {
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
	 * @param callable $assertion
	 * @return self
	 */
	public function deny($roles = self::ALL, $resources = self::ALL, $privileges = self::ALL, $assertion = NULL)
	{
		if ($assertion !== NULL) {
			$assertion = function () use ($assertion) {
				return Callback::invoke($assertion, $this->identity, $this->getQueriedResource(), $this->getQueriedRole());
			};
		}
		return parent::deny($roles, $resources, $privileges, $assertion);
	}

}
