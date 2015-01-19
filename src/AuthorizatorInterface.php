<?php

namespace Arachne\Security;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface AuthorizatorInterface
{

	/**
	 * @param string|IResource $resource
	 * @param string $privilege
	 * @param IIdentity $identity
	 * @return bool
	 */
	public function isAllowed($resource, $privilege, IIdentity $identity);

}
