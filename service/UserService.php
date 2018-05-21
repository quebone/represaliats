<?php
namespace Represaliats\Service;

use Represaliats\Service\Entities\User;

class UserService extends Service
{
	public function __construct() {
		parent::__construct();
	}
	
	public function addUser($nom, $cognoms, $username): ?User {
		try {
			$user = new User($nom, $cognoms, $username);
			$this->dao->persistAndFlush($user);
			return $user;
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	public function isRegistered($username, $password): bool {
		$user = $this->dao->getByFilter("User", ["username"=>$username, "password"=>sha1($password)])[0];
		return $user != null;
	}
	
	public function getUser($username, $password): User {
		return $this->dao->getByFilter("User", ["username"=>$username, "password"=>$password])[0];
	}
}