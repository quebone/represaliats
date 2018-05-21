<?php
namespace Represaliats\Presentation\Controller;

use Represaliats\Service\PersonaService;
use Represaliats\Service\FilterService;
use Represaliats\Presentation\View\MainView;
use Represaliats\Service\Entities\Filtre;

class FitxesController extends Controller
{
	public function __construct() {
		parent::__construct();
		$this->service = new PersonaService();
	}
	
	public function addFitxa($post) {
		$nom = $post["nom"];
		$cognoms= $post["cognoms"];
		try {
			$persona = $this->service->addPersona($nom, $cognoms);
			return json_encode($persona->getResum());
		} catch (\Exception $e) {
			return json_encode($e->getMessage());
		}
	}
	
	public function applyFilters($post) {
	    $entity = $post["entity"];
	    $filtre = new Filtre("tmp", $post['filters']);
	    $service = new FilterService($entity, $filtre->getFieldsClean());
	    $instances = $service->applyFilters();
	    $data = [];
	    foreach ($instances as $instance) {
	        $data[] = $instance->getResum();
	    }
	    $view = new MainView();
	    $data = $view->sortEntity($data, "cognoms");
	    return json_encode($data);
	}
	
	public function addFiltre($post) {
	    $controller = new FiltresController();
	    return $controller->addFiltre($post);
	}
	
	public function deleteFiltre($post) {
	    $controller = new FiltresController();
	    return $controller->deleteFiltre($post);
	}

	public function getFiltre($post) {
	    $controller = new FiltresController();
	    return $controller->getFiltre($post);
	}
	
	public function getSpreadSheet($post) {
	    $controller = new FitxaController();
	    return $controller->getSpreadSheet($post);
	}
}