<?php

declare(strict_types=1);

namespace Arachne\Security\Authentication;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Http\UserStorage as BaseUserStorage;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class UserStorage extends BaseUserStorage
{
    /**
     * @var IdentityValidatorInterface|null
     */
    private $identityValidator;

    /**
     * @var SessionSection|null
     */
    private $sessionSection;

    public function __construct(string $namespace, Session $session, ?IdentityValidatorInterface $identityValidator = null)
    {
        parent::__construct($session);
        $this->setNamespace($namespace);
        $this->identityValidator = $identityValidator;
    }

    /**
     * @param bool $need
     */
    protected function getSessionSection($need): ?SessionSection
    {
        if ($this->sessionSection === null) {
            $section = parent::getSessionSection($need);

            if ($this->identityValidator !== null && $section !== null && $section->authenticated === true) {
                $identity = $this->identityValidator->validateIdentity($section->identity);

                if ($identity instanceof IIdentity) {
                    $section->identity = $identity;
                } else {
                    $section->authenticated = false;
                    $section->reason = FirewallInterface::LOGOUT_INVALID_IDENTITY;
                    if ($section->expireIdentity === true) {
                        unset($section->identity);
                    }
                    unset($section->expireTime, $section->expireDelta, $section->expireIdentity, $section->expireBrowser, $section->browserCheck, $section->authTime);
                }
            }

            $this->sessionSection = $section;
        }

        return $this->sessionSection;
    }
}
