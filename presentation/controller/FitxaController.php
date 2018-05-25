<?php
namespace Represaliats\Presentation\Controller;

use Represaliats\Presentation\View\FitxaView;
use Represaliats\Service\FitxaService;
use Represaliats\Presentation\View\FitxaPdfView;
use Represaliats\Service\PersonaService;
use Represaliats\Presentation\View\FitxaSpreadsheetView;

class FitxaController extends Controller
{
	private $view;
	
	public function __construct() {
		parent::__construct();
		$this->view = new FitxaView();
		$this->service = new FitxaService();
	}

	public function addLinkedEntity($post) {
		$id = $post["id"];
		$entity = isset($post["entity"]) ? $post["entity"] : "Persona";
		$linkedProperty = $post["linkedProperty"];
		$linkedEntityName = $post["linkedEntityName"];
		$linkedEntityId = $post["linkedEntityId"];
		return json_encode($this->service->addLinkedEntity($entity, $id, $linkedProperty, $linkedEntityName, $linkedEntityId));
	}
	
	public function addNewLinkedEntity($post) {
		$post["linkedEntityId"] = null;
		return $this->addLinkedEntity($post);
	}
	
	public function changeLinkedEntity($post) {
		$id = $post["id"];
		$entity = isset($post["entity"]) ? $post["entity"] : "Persona";
		$linkedProperty = $post["linkedProperty"];
		$linkedEntityName = $post["linkedEntityName"];
		$linkedEntityId = $post["linkedEntityId"];
		$oldLinkedEntityId = $post["oldLinkedEntityId"];
		return json_encode($this->service->changeLinkedEntity($entity, $id, $linkedProperty, $linkedEntityName, $linkedEntityId, $oldLinkedEntityId));
	}
	
	public function removeLinkedEntity($post) {
		$id = $post["id"];
		$entity = isset($post["entity"]) ? $post["entity"] : "Persona";
		$linkedProperty = $post["linkedProperty"];
		$linkedEntityName = $post["linkedEntityName"];
		$linkedEntityId = $post["linkedEntityId"];
		try {
			return json_encode($this->service->removeLinkedEntity($entity, $id, $linkedProperty, $linkedEntityName, $linkedEntityId));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function changeSimple($post): ?string {
		$id = $post["id"];
		$entityName = $post["entityName"];
		$property = $post["property"];
		$value = $post["value"];
		if (!strcmp($value, "")) $value = null;
		if (!strcmp($value, "false")) $value = false;
		try {
			return json_encode($this->service->changeSimple($entityName, $id, $property, $value));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function changeSelect($post): ?string {
		$id = $post["id"];
		$entityName = $post["entityName"];
		$property = $post["property"];
		$value = $post["value"];
		$linkedEntityName = $post["linkedEntityName"];
		try {
			return json_encode($this->service->changeSelect($entityName, $id, $property, $value, $linkedEntityName));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function addEntity($post): ?string {
		$id = $post["id"];
		$entityName = $post["entityName"];
		$property = $post["property"];
		try {
			return json_encode($this->service->addEntity($entityName, $id, $property));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function removeEntity($post) {
		$id = $post["id"];
		$entity = isset($post["entity"]) ? $post["entity"] : "Persona";
		$linkedProperty = $post["linkedProperty"];
		$linkedEntityName = $post["linkedEntityName"];
		$linkedEntityId = $post["linkedEntityId"];
		try {
			return json_encode($this->service->removeEntity($entity, $id, $linkedProperty, $linkedEntityName, $linkedEntityId));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function addSumari($post) {
		$id = $post["id"];
		return json_encode($this->service->addSumari($id));
	}
	
	public function addInforme($post) {
		$id = $post["id"];
		try {
			return json_encode($this->service->addInforme($id));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function getValues($post) {
		$id = $post["id"];
		$entityName = $post["entityName"];
		try {
			return json_encode($this->service->getValues($entityName, $id));
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function getParelles($post) {
		$id = $post["id"];
		$data = array_merge($this->service->getInstances("Persona", $id, "parelles"), $this->service->getInstances("Persona", $id, "myParelles"));
		return json_encode($data);
	}

	public function getFills($post) {
		$id = $post["id"];
		return json_encode($this->service->getInstances("Persona", $id, "fills"));
	}
	
	public function getOficis($post) {
		$id = $post["id"];
		return json_encode($this->service->getInstances("Persona", $id, "oficis"));
	}
	
	public function getTipusSituacio($post) {
	    $id = $post["id"];
	    return json_encode($this->service->getInstances("Persona", $id, "tipusSituacio"));
	}
	
	public function getPartits($post) {
		$id = $post["id"];
		return json_encode($this->service->getInstances("Persona", $id, "partits"));
	}

	public function getSindicats($post) {
		$id = $post["id"];
		return json_encode($this->service->getInstances("Persona", $id, "sindicats"));
	}
	
	public function getComites($post) {
	    $id = $post["id"];
	    return json_encode($this->service->getInstances("Persona", $id, "comites"));
	}
	
	public function getAjuntaments($post) {
	    $id = $post["id"];
	    return json_encode($this->service->getInstances("Persona", $id, "ajuntaments"));
	}
	
	public function getLlibertats($post) {
		$id = $post["id"];
		return json_encode($this->service->getInstances("Sumari", $id, "llibertats"));
	}
	
	public function getCommutacions($post) {
		$id = $post["id"];
		return json_encode($this->service->getInstances("Sumari", $id, "commutacions"));
	}
	
	public function getPeticionsInforme($post) {
		$id = $post["id"];
		return json_encode($this->service->getInformes($id));
	}
	
	public function printPdf($post) {
	    $id = intval($post['id']);
	    $service = new PersonaService();
	    $data = $service->getPersona($id);
	    $pdf = new FitxaPdfView($data);
	    $pdf->getPdf();
	}
	
	public function getSpreadSheet($post) {
	    $id = intval($post['id']);
	    $service = new PersonaService();
	    $data = $service->getPersona($id);
	    $xls = new FitxaSpreadsheetView($data);
	    return $xls->getSpreadsheet();
	}
}