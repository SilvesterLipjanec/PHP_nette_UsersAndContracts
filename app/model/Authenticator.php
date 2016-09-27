<?php

namespace App\Model;

use Nette;
use Nette\Security;


/**
 * Users authenticator.
 */
class Authenticator implements Security\IAuthenticator
{
	use Nette\SmartObject;

	/** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($login, $password) = $credentials;
		$row = $this->database->table('users')->where('login', $login)->fetch();

		if (!$row) {
			throw new Security\AuthenticationException('Login je nesprávny.', self::IDENTITY_NOT_FOUND);

		} elseif (!Security\Passwords::verify($password,Security\Passwords::hash($row->password))) {
			throw new Security\AuthenticationException('Nesprávne heslo.', self::INVALID_CREDENTIAL);
		}

		$arr = $row->toArray();
		unset($arr['password']);
		return new Security\Identity($row->login, NULL, $arr);
	}

}
