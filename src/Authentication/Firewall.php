<?php

namespace Arachne\Security\Authentication;

use Nette\Object;
use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Firewall extends Object implements FirewallInterface
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
     * @return IIdentity
     */
    public function getIdentity()
    {
        return $this->storage->isAuthenticated() ? $this->storage->getIdentity() : null;
    }

    /**
     * @return IIdentity
     */
    public function getExpiredIdentity()
    {
        return $this->storage->getIdentity();
    }

    /**
     * @return int
     */
    public function getLogoutReason()
    {
        return $this->storage->getLogoutReason();
    }

    /**
     * @param string|int|\DateTime $time
     * @param bool                 $browserClosed
     */
    public function setExpiration($time, $browserClosed = true)
    {
        $this->storage->setExpiration($time, $browserClosed ? IUserStorage::BROWSER_CLOSED : 0);
    }
}
