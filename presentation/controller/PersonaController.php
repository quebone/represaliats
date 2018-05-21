<?php
namespace Represaliats\Presentation\Controller;

use Represaliats\Presentation\View\FitxaView;

class PersonaController extends Controller
{
	private $view;
	
	public function __construct() {
		parent::__construct();
		$this->view = new FitxaView();
	}
	
	public function getPersona(int $id): array {
		try {
			$data = $this->view->getPersona($id);
			return $data;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}
}