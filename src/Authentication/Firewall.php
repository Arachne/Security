<?php

declare(strict_types=1);

namespace Arachne\Security\Authentication;

use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Firewall implements FirewallInterface
{
    /**
     * @var IUserStorage
     */
    private $storage;

    public function __construct(IUserStorage $storage)
    {
        $this->storage = $storage;
    }

    public function login(IIdentity $identity): void
    {
        $this->storage->setIdentity($identity);
        $this->storage->setAuthenticated(true);
    }

    public function logout(): void
    {
        $this->storage->setAuthenticated(false);
    }

    public function getIdentity(): ?IIdentity
    {
        return $this->storage->isAuthenticated() ? $this->storage->getIdentity() : null;
    }

    public function getExpiredIdentity(): ?IIdentity
    {
        return $this->storage->getIdentity();
    }

    public function getLogoutReason(): ?int
    {
        return $this->storage->getLogoutReason();
    }

    /**
     * @param string|int|\DateTime $time
     */
    public function setExpiration($time): void
    {
        $this->storage->setExpiration($time);
    }
}
