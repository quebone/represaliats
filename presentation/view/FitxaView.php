<?php
namespace Represaliats\Presentation\View;

use Represaliats\Service\PersonaService;

class FitxaView extends View
{
	private $service;
	
	public function __construct() {
		parent::__construct();
		$this->service = new PersonaService();
	}
	
	public function getPersona(int $id): array {
		try {
			$persona = $this->service->getPersona($id);
			$data = $persona->toArray();
			$data["persones"] = $this->getPersones($id);
			$data["oficis"] = $this->getEntity("Ofici", ["nom"=>"ASC"]);
			$data["carrers"] = $this->getEntity("Carrer", ["nomAnterior" => "ASC"]);
			$data["municipis"] = $this->getEntity("Municipi", ["nom"=>"ASC"]);
			$data["paisos"] = $this->getEntity("Pais", ["nom"=>"ASC"]);
			$data["situacions"] = $this->getEntity("TipusSituacio", ["nom"=>"ASC"]);
			$data["partits"] = $this->getEntity("Partit", ["nom"=>"ASC"]);
			$data["sindicats"] = $this->getEntity("Sindicat", ["nom"=>"ASC"]);
			$data["comites"] = $this->getEntity("Comite", ["nom"=>"ASC"]);
			$data["estatsMilitars"] = $this->getEntity("EstatMilitar", ["nom"=>"ASC"]);
			$data["destinsEstatMilitar"] = $this->getEntity("DestiEstatMilitar", ["nom"=>"ASC"]);
			$data["fonts"] = $this->getEntity("Font", ["nom"=>"ASC"]);
			$data["acusacions"] = $this->getEntity("Acusacio", ["tipus"=>"ASC"]);
			$data["llibertats"] = $this->getEntity("Llibertat", ["tipus"=>"ASC"]);
			$data["execucions"] = $this->getEntity("Execucio", ["tipus"=>"ASC"]);
			$data["llocsExecucio"] = $this->getEntity("LlocExecucio", ["nom"=>"ASC"]);
			$data["organismes"] = $this->getEntity("Organisme", ["nom"=>"ASC"]);
			return $data;
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}
	
	public function getPersones($id = null): array {
		$data = [];
		$persones = $this->dao->getByFilter("Persona", [], ["cognoms" => "ASC"]);
		foreach ($persones as $persona) {
			if ($id == null || ($id != null && $id != $persona->getId())) {
				array_push($data, [
						"id"=>$persona->getId(),
						"nom"=>$persona->getNom(),
						"cognoms"=>$persona->getCognoms()
				]);
			}
		}
		return $data;
	}
	
	public function getParelles($id): array {
		$data = [];
		$persona = $this->dao->getById("Persona", $id);
		$parelles = array_merge($persona->getParelles()->toArray(), $persona->getMyParelles()->toArray());
		foreach ($parelles as $parella) {
			array_push($data, ["id"=>$parella->getId(), "data" => [
					"id"=>$parella->getId(),
			]]);
		}
		return $data;
	}
	
	public function getFills($id): array {
		$data = [];
		$persona = $this->dao->getById("Persona", $id);
		foreach ($persona->getFills() as $fill) {
			array_push($data, ["id"=>$fill->getId(), "data" => [
					"id"=>$fill->getId(),
			]]);
		}
		return $data;
	}

	public function getOficis($id): array {
		$data = [];
		$persona = $this->dao->getById("Persona", $id);
		foreach ($persona->getOficis() as $ofici) {
			array_push($data, ["id"=>$ofici->getId(), "data" => [
					"id"=>$ofici->getId(),
			]]);
		}
		return $data;
	}
	
	public function getPartits($id): array {
		$data = [];
		$persona = $this->dao->getById("Persona", $id);
		foreach ($persona->getPartits() as $partit) {
			array_push($data, ["id"=>$partit->getId(), "data" => [
					"id"=>$partit->getId(),
			]]);
		}
		return $data;
	}

	public function getSindicats($id): array {
		$data = [];
		$persona = $this->dao->getById("Persona", $id);
		foreach ($persona->getSindicats() as $sindicat) {
			array_push($data, ["id"=>$sindicat->getId(), "data" => [
					"id"=>$sindicat->getId(),
			]]);
		}
		return $data;
	}
	
	public function getComites($id): array {
	    $data = [];
	    $persona = $this->dao->getById("Comite", $id);
	    foreach ($persona->getComites() as $comite) {
	        array_push($data, ["id"=>$comite->getId(), "data" => [
	            "id"=>$comite->getId(),
	        ]]);
	    }
	    return $data;
	}
	
	public function getDatesAjuntament($id): array {
		$data = [];
		$persona = $this->dao->getById("Persona", $id);
		foreach ($persona->getDatesAjuntament() as $ajuntament) {
			array_push($data, ["id"=>$ajuntament->getId(), "data" => [
					"dataInici"=>$ofici->getId(),
			]]);
		}
		return $data;
	}

	public function getLlibertats($id): array {
		$data = [];
		$sumari = $this->dao->getById("Sumari", $id);
		foreach ($sumari->getLlibertats() as $llibertat) {
			array_push($data, $llibertat->toArray());
		}
		return $data;
	}
}