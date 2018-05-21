<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\MainService;

class MainView extends View
{
	private $service;
	
	public function __construct() {
		parent::__construct();
		$this->service = new MainService();
	}
	
	public function getResumFitxes():array {
		$data = [];
		$persones = $this->dao->getByFilter("Persona");
		foreach ($persones as $persona) {
			array_push($data, $persona->getResum());
		}
		return $data;
	}
}