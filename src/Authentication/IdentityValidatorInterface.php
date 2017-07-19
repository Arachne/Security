<?php

namespace Arachne\Security\Authentication;

use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface IdentityValidatorInterface
{
    /**
     * @return IIdentity|null
     */
    public function validateIdentity(IIdentity $identity);
}
