<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\Security;

use Nette\Object;
use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Firewall extends Object
{

	/** @var IUserStorage */
	private $storage;

	public function __construct(IUserStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return $this->storage->isAuthenticated();
	}

	/**
	 * @param IIdentity $identity
	 */
	public function login(IIdentity $identity)
	{
		$this->storage->setIdentity($identity);
		$this->storage->setAuthenticated(TRUE);
	}

	/**
	 * @return void
	 */
	public function logout()
	{
		$this->storage->setAuthenticated(FALSE);
	}

	/**
	 * @return IIdentity
	 */
	public function getIdentity()
	{
		return $this->isLoggedIn() ? $this->storage->getIdentity() : NULL;
	}

	/**
	 * @return IIdentity
	 */
	public function getExpiredIdentity()
	{
		return $this->storage->getIdentity();
	}

	/**
	 * @return int
	 */
	public function getLogoutReason()
	{
		return $this->storage->getLogoutReason();
	}

	/**
	 * @param string|int|\DateTime $time
	 * @param bool $browserClosed
	 */
	public function setExpiration($time, $browserClosed = TRUE)
	{
		$this->storage->setExpiration($time, $browserClosed ? IUserStorage::BROWSER_CLOSED : 0);
	}

}
