<?php

namespace Arachne\Security;

use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface IdentityValidatorInterface
{

	/**
	 * @return IIdentity
	 */
	public function validateIdentity(IIdentity $identity);

}
