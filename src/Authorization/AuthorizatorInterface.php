<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\Security\Authorization;

use Nette\Security\IResource;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface AuthorizatorInterface
{

	/**
	 * @param string|IResource $resource
	 * @param string $privilege
	 * @return bool
	 */
	public function isAllowed($resource, $privilege);

}
