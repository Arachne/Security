<?php

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

    /**
     * @param IIdentity $identity
     */
    public function login(IIdentity $identity)
    {
        $this->storage->setIdentity($identity);
        $this->storage->setAuthenticated(true);
    }

    public function logout()
    {
        $this->storage->setAuthenticated(false);
    }

    /**
     * @return IIdentity|null
     */
    public function getIdentity()
    {
        return $this->storage->isAuthenticated() ? $this->storage->getIdentity() : null;
    }

    /**
     * @return IIdentity|null
     */
    public function getExpiredIdentity()
    {
        return $this->storage->getIdentity();
    }

    /**
     * @return int|null
     */
    public function getLogoutReason()
    {
        return $this->storage->getLogoutReason();
    }

    /**
     * @param string|int|\DateTime $time
     */
    public function setExpiration($time)
    {
        $this->storage->setExpiration($time);
    }
}
