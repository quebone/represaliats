<?php
namespace Represaliats\Service;

use Represaliats\Service\Entities\Sumari;

class FitxaService extends Service
{
	public function __construct() {
		parent::__construct();
	}
	
	public function addSumari(string $id) {
		try {
			$persona = $this->dao->getById("Persona", $id);
			$sumari = new Sumari();
			$persona->setSumari($sumari);
		} catch (\Exception $e) {
		}
	}
	
	public function changeSimple($entityName, $id, $property, $value) {
		try {
			$entity = $this->dao->getById($entityName, $id);
			$entity->{"set" . ucfirst($property)}($value);
			$this->dao->flush();
			return $entity->{"get" . ucfirst($property)}();
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	public function changeSelect($entityName, $id, $property, $value, $linkedEntityName) {
		try {
			$entity = $this->dao->getById($entityName, $id);
			$linkedEntity = $this->dao->getById($linkedEntityName, $value);
			$entity->{"set" . ucfirst($property)}($linkedEntity);
			$this->dao->flush();
			return $entity->{"get" . ucfirst($property)}()->getId();
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	public function addEntity($entityName, $id, $property) {
		try {
			$entity = $this->dao->getById($entityName, $id);
			$linkedEntity = $entity->{"add" . ucfirst($property)}();
			$this->dao->persistAndFlush($linkedEntity);
			return $linkedEntity->getValues();
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	public function addInforme($id) {
		try {
			$persona = $this->dao->getById("Persona", $id);
			$peticioInforme = $persona->addPeticionsInforme();
			$this->dao->persistAndFlush($peticioInforme);
			return ["peticio" => $peticioInforme->getValues(), "informe" => $peticioInforme->getInforme()->getValues()];
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	public function getInformes($id) {
		try {
			$data = [];
			$entity = $this->dao->getById("Persona", $id);
			$peticionsInforme = $entity->getPeticionsInforme();
			foreach ($peticionsInforme as $peticioInforme) {
				$data[] = ["peticio" => $peticioInforme->getValues(), "informe" => $peticioInforme->getInforme()->getValues()];
			}
			return $data;
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function getValues($entityName, $id) {
		try {
			$instance = $this->dao->getById($entityName, $id);
			return $instance->getValues();
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public function getInstances($entityName, $id, $property) {
		try {
			$data = [];
			$entity = $this->dao->getById($entityName, $id);
			$instances = $entity->{"get" . ucfirst($property)}();
			foreach ($instances as $instance) {
				$data[] = $instance->getValues();
			}
			return $data;
		} catch (\Exception $e) {
			throw $e;
		}
	}

	/**
	 * Elimina una entitat enllaçada
	 */
	public function removeEntity(string $entityName, string $id, string $linkedProperty, string $linkedEntityName, string $linkedEntityId): bool {
		try {
			$entity = $this->dao->getById($entityName, $id);
			$linkedEntity = $this->dao->getById($linkedEntityName, $linkedEntityId);
			$entity->{"remove" . ucfirst($linkedProperty)}($linkedEntity);
			$this->dao->flush();
			return true;
		} catch (\Exception $e) {
			throw $e;
		} catch (\Doctrine\DBAL\DBALException $e) {
			throw new \Exception($e->getMessage());
		}
	}
}