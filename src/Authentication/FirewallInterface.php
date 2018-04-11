<?php

declare(strict_types=1);

namespace Arachne\Security\Authentication;

use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface FirewallInterface
{
    const LOGOUT_MANUAL = 1;
    const LOGOUT_INACTIVITY = 2;
    const LOGOUT_INVALID_IDENTITY = 3;
    const LOGOUT_BROWSER_CLOSED = 4;

    public function login(IIdentity $identity): void;

    public function logout(): void;

    public function getIdentity(): ?IIdentity;

    public function getExpiredIdentity(): ?IIdentity;

    public function getLogoutReason(): ?int;

    /**
     * @param string|int|\DateTimeInterface $time
     */
    public function setExpiration($time): void;
}
