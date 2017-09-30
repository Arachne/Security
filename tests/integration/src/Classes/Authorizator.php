<?php

declare(strict_types=1);

namespace Tests\Integration\Classes;

use Arachne\Security\Authorization\AuthorizatorInterface;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Authorizator implements AuthorizatorInterface
{
    public function isAllowed($resource, string $privilege): bool
    {
    }
}
