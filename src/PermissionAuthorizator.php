<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\Security;

use Arachne\Security\AuthorizatorInterface;
use Arachne\Security\Permission;
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
	 * @param Permission $permission
	 */
	public function __construct(FirewallInterface $firewall, Permission $permission)
	{
		$this->firewall = $firewall;
		$this->permission = $permission;
	}

	/**
	 * @param string|IResource $resource
	 * @param string $privilege
	 * @retrun bool
	 */
	public function isAllowed($resource, $privilege)
	{
		$identity = $this->firewall->getIdentity();
		$this->permission->setIdentity($identity);
		$roles = $identity ? $identity->getRoles() : [ $this->guestRole ];

		foreach ($roles as $role) {
			if ($this->permission->isAllowed($role, $resource, $privilege)) {
				return TRUE;
			}
		}
		return FALSE;
	}

}
