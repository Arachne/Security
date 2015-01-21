<?php

namespace Tests\Integration\Classes;

use Arachne\Security\AuthorizatorInterface;
use Nette\Object;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Authorizator extends Object implements AuthorizatorInterface
{

	public function isAllowed($resource, $privilege, IIdentity $identity)
	{
	}

}
