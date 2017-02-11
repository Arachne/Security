<?php

namespace Tests\Unit;

use Arachne\Security\Authentication\FirewallInterface;
use Arachne\Security\Authentication\IdentityValidatorInterface;
use Arachne\Security\Authentication\UserStorage;
use Codeception\Test\Unit;
use Eloquent\Phony\Mock\Handle\InstanceHandle;
use Eloquent\Phony\Phpunit\Phony;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class UserStorageTest extends Unit
{
    /**
     * @var UserStorage
     */
    private $userStorage;

    /**
     * @var InstanceHandle
     */
    private $sessionHandle;

    /**
     * @var InstanceHandle
     */
    private $identityValidatorHandle;

    protected function _before()
    {
        $this->sessionHandle = Phony::mock(Session::class);
        $this->sessionHandle
            ->exists
            ->returns(true);

        $section = Phony::partialMock(
            SessionSection::class,
            [
                $this->sessionHandle->get(),
                'Nette.Http.UserStorage/test',
            ]
        );

        $this->sessionHandle
            ->getSection
            ->with('Nette.Http.UserStorage/test')
            ->returns($section);

        $this->identityValidatorHandle = Phony::mock(IdentityValidatorInterface::class);
        $this->userStorage = new UserStorage('test', $this->sessionHandle->get(), $this->identityValidatorHandle->get());
    }

    public function testInvalidIdentity()
    {
        $identityHandle = Phony::mock(IIdentity::class);

        $identity = $identityHandle->get();

        /** @var Session $session */
        $session = $this->sessionHandle->get();
        $section = $session->getSection('Nette.Http.UserStorage/test');
        $section->identity = $identity;
        $section->authenticated = true;

        $this->assertFalse($this->userStorage->isAuthenticated());
        $this->assertSame($identity, $this->userStorage->getIdentity());
        $this->assertSame(FirewallInterface::LOGOUT_INVALID_IDENTITY, $this->userStorage->getLogoutReason());

        $this->identityValidatorHandle
            ->validateIdentity
            ->calledWith($identity);
    }

    public function testNewIdentity()
    {
        $identityHandle = Phony::mock(IIdentity::class);
        $newIdentityHandle = Phony::mock(IIdentity::class);

        $identity = $identityHandle->get();
        $newIdentity = $newIdentityHandle->get();

        /** @var Session $session */
        $session = $this->sessionHandle->get();
        $section = $session->getSection('Nette.Http.UserStorage/test');
        $section->identity = $identity;
        $section->authenticated = true;

        $this->identityValidatorHandle
            ->validateIdentity
            ->returns($newIdentity);

        $this->assertTrue($this->userStorage->isAuthenticated());
        $this->assertSame($newIdentity, $this->userStorage->getIdentity());

        $this->identityValidatorHandle
            ->validateIdentity
            ->calledWith($identity);
    }
}
