<?php

/**
 * This file is part of the Arachne.
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\Security\Authentication;

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
