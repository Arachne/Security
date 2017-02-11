<?php

namespace Arachne\Security\Authorization;

use Nette\Security\IResource;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface AuthorizatorInterface
{
    /**
     * @param string|IResource $resource
     * @param string           $privilege
     *
     * @return bool
     */
    public function isAllowed($resource, $privilege);
}
