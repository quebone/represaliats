<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\UserService;

class UserView extends View
{
	private $service;
	
	public function __construct() {
		parent::__construct();
		$this->service = new UserService();
	}
	
	public function getCurrentUser($username, $password) {
		$user = $this->service->getUser($username, $password);
		return $user->toArray();
	}
}