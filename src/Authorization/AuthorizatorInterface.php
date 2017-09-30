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
     */
    public function isAllowed($resource, string $privilege): bool;
}
