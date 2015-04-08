<?php

namespace Tests\Unit;

use Arachne\Security\Authentication\FirewallInterface;
use Arachne\Security\Authentication\IdentityValidatorInterface;
use Arachne\Security\Authentication\UserStorage;
use Codeception\TestCase\Test;
use Kdyby\FakeSession\SessionSection;
use Mockery;
use Mockery\MockInterface;
use Nette\Http\Session;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class UserStorageTest extends Test
{

	/** @var UserStorage */
	private $userStorage;

	/** @var MockInterface */
	private $session;

	/** @var MockInterface */
	private $identityValidator;

	protected function _before()
	{
		$section = Mockery::mock(SessionSection::class);
		$section->makePartial();

		$this->session = Mockery::mock(Session::class);
		$this->session
			->shouldReceive('exists')
			->once()
			->andReturn(TRUE);
		$this->session
			->shouldReceive('getSection')
			->twice()
			->with('Nette.Http.UserStorage/test')
			->andReturn($section);

		$this->identityValidator = Mockery::mock(IdentityValidatorInterface::class);
		$this->userStorage = new UserStorage('test', $this->session, $this->identityValidator);
	}

	public function testInvalidIdentity()
	{
		$identity = Mockery::mock(IIdentity::class);

		$section = $this->session->getSection('Nette.Http.UserStorage/test');
		$section->identity = $identity;
		$section->authenticated = TRUE;

		$this->identityValidator
			->shouldReceive('validateIdentity')
			->with($identity)
			->andReturn();

		$this->assertFalse($this->userStorage->isAuthenticated());
		$this->assertSame($identity, $this->userStorage->getIdentity());
		$this->assertSame(FirewallInterface::LOGOUT_INVALID_IDENTITY, $this->userStorage->getLogoutReason());
	}

	public function testNewIdentity()
	{
		$identity = Mockery::mock(IIdentity::class);
		$newIdentity = Mockery::mock(IIdentity::class);

		$section = $this->session->getSection('Nette.Http.UserStorage/test');
		$section->identity = $identity;
		$section->authenticated = TRUE;

		$this->identityValidator
			->shouldReceive('validateIdentity')
			->with($identity)
			->andReturn($newIdentity);

		$this->assertTrue($this->userStorage->isAuthenticated());
		$this->assertSame($newIdentity, $this->userStorage->getIdentity());
	}

}
