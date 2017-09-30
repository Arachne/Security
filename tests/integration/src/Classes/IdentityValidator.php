<?php

declare(strict_types=1);

namespace Tests\Integration\Classes;

use Arachne\Security\Authentication\IdentityValidatorInterface;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class IdentityValidator implements IdentityValidatorInterface
{
    public function validateIdentity(IIdentity $identity): ?IIdentity
    {
        $identity->validated = true;

        return $identity;
    }
}
