<?php

declare(strict_types=1);

namespace Tests\Integration\Classes;

use Arachne\Security\Authorization\AuthorizatorInterface;
use Nette\Security\IResource;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Authorizator implements AuthorizatorInterface
{
    /**
     * @param string|IResource $resource
     * @param string           $privilege
     *
     * @return bool
     */
    public function isAllowed($resource, string $privilege): bool
    {
    }
}
