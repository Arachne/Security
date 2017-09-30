<?php

declare(strict_types=1);

namespace Arachne\Security\Authentication;

use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface IdentityValidatorInterface
{
    public function validateIdentity(IIdentity $identity): ?IIdentity;
}
