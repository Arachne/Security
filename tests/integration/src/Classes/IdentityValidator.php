<?php

namespace Tests\Integration\Classes;

use Arachne\Security\Authentication\IdentityValidatorInterface;
use Nette\Object;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class IdentityValidator extends Object implements IdentityValidatorInterface
{

	public function validateIdentity(IIdentity $identity)
	{
		$identity->validated = TRUE;
		return $identity;
	}

}
