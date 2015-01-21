<?php

namespace Arachne\Security;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Http\UserStorage as BaseUserStorage;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class UserStorage extends BaseUserStorage
{

	/** @var IdentityValidatorInterface */
	private $identityValidator;

	/**
	 * @param string $namespace
	 * @param Session $session
	 * @param IdentityValidatorInterface $identityValidator
	 */
	public function __construct($namespace, Session $session, IdentityValidatorInterface $identityValidator = NULL)
	{
		parent::__construct($session);
		$this->setNamespace($namespace);
		$this->identityValidator = $identityValidator;
	}

	/**
	 * @param bool $need
	 * @return SessionSection
	 */
	protected function getSessionSection($need)
	{
		$section = parent::getSessionSection($need);

		if ($this->identityValidator && $section && $section->authenticated) {
			$identity = $this->identityValidator->validateIdentity($section->identity);

			if ($identity instanceof IIdentity) {
				$section->identity = $identity;

			} else {
				$section->authenticated = FALSE;
				$section->reason = FirewallInterface::LOGOUT_INVALID_IDENTITY;
				if ($section->expireIdentity) {
					unset($section->identity);
				}
				unset($section->expireTime, $section->expireDelta, $section->expireIdentity,
					$section->expireBrowser, $section->browserCheck, $section->authTime);
			}
		}

		return $section;
	}

}
