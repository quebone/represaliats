<?php
namespace Represaliats\Service;

use Represaliats\Service\Entities\Persona;

class PersonaService extends Service
{
	public function __construct() {
		parent::__construct();
	}
	
	public function personaExists($nom, $cognoms): bool {
		$persona = $this->dao->getByFilter("Persona", ["nom" => $nom, "cognoms" => $cognoms], [], true);
		return $persona != null;
	}
	
	public function addPersona($nom, $cognoms): Persona {
		if ($this->personaExists($nom, $cognoms)) throw new \Exception("Aquesta persona ja existeix");
		$persona = new Persona($nom, $cognoms);
		$this->dao->persistAndFlush($persona);
		return $persona;
	}
	
	public function removePersona($id) {
		$persona = $this->dao->getById("Persona", $id);
		$this->dao->remove($persona);
		$this->dao->flush();
	}

	public function getPersona($id): ?Persona {
		$persona = $this->dao->getById("Persona", $id);
		if ($persona == null) throw new \Exception("Aquesta fitxa no existeix");
		return $persona;
	}
	
	public function getAllPersones(): array {
	    return $this->dao->getByFilter('Persona', [], ['cognoms'=>'ASC']);
	}
}