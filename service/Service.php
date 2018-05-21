<?php
namespace Represaliats\Service;

use Represaliats\DAO;

class Service
{
	protected $dao;
	
	public function __construct() {
		$this->dao = DAO::getInstance();
	}
	
	/**
	 * Canvia el valor de tipus int/bool/string d'una propietat d'una entitat
	 * @param string $entityName
	 * @param string $id
	 * @param string $propertyName
	 * @param mixed $value
	 * @param string $propertyEntity
	 * @throws \Exception
	 * @return int
	 */
	public function changeValue(string $entityName, string $id, string $propertyName, $value, string $propertyEntity=null): array {
	    try {
	        $entity = $this->dao->getById($entityName, $id);
	    } catch (\Exception $e) {
	        $entity = $this->dao->create($entityName);
	        $this->dao->persist($entity);
	    }
		try {
		    if ($propertyEntity != null) {
		        try {
		            $value = $this->dao->getById(ucfirst($propertyEntity), $value);
		        } catch (\Exception $e) {
		            throw $e;
		        }
		    }
			$entity->{"set" . ucfirst($propertyName)}($value);
			$this->dao->flush();
			return $entity->toArray();
		} catch (\Exception $e) {
		    throw $e;
// 			throw new \Exception("Error al canviar la propietat " . $propertyName . " de " . $entityName);
		}
	}
	
	/**
	 * Canvia el valor de tipus entitat d'una propietat d'una entitat
	 * @param string $entityName
	 * @param string $id
	 * @param string $propertyName
	 * @param mixed $value
	 * @param string $propertyEntity
	 * @throws \Exception
	 * @return int
	 */
	public function changeOption(string $entityName, string $id, string $propertyName, string $propertyEntity, $value):int {
		$entity = $this->dao->getById($entityName, $id);
		if ($entity == null) {
			try {
				$entity = $this->dao->create($entityName);
				$this->dao->persist($entity);
			} catch (\Exception $e) {
				throw new \Exception("L'entitat " . $entity . " no existeix");
			}
		}
		try {
			$property = $this->dao->getById($propertyEntity, $value);
			$entity->{"set" . ucfirst($propertyName)}($property);
			$this->dao->flush();
			return $entity->getId();
		} catch (\Exception $e) {
			throw new \Exception("La propietat " . $property . " no existeix");
		}
	}
	
	/**
	 * Elimina una instància d'una entitat
	 * @param string $entityName
	 * @param string $id
	 * @throws \Exception
	 */
	public function deleteEntity(string $entityName, string $id) {
		try {
		    $entity = $this->dao->getById($entityName, $id);
		    $this->dao->remove($entity);
			$this->dao->flush();
		} catch (\Exception $e) {
			throw $e;
		}
	}
	
	public function addLinkedEntity(string $entityName, string $id, string $linkedProperty, string $linkedEntityName, ?string $linkedEntityId): ?array {
		try {
			$entity = $this->dao->getById($entityName, $id);
			if ($linkedEntityId == null) {
				$class = "Represaliats\\Service\\Entities\\" . $linkedEntityName;
				$linkedEntity = new $class();
				$this->dao->persist($linkedEntity);
			} else {
				$linkedEntity = $this->dao->getById($linkedEntityName, $linkedEntityId);
			}
			$entity->{"add" . ucfirst($linkedProperty)}($linkedEntity);
			$this->dao->flush();
			return $linkedEntity->toArray();
		} catch (\Exception $e) {
			return null;
		}
	}
	
	/**
	 * Canvia una entitat enllaçada per una altra
	 * @param string $entityName
	 * @param string $id
	 * @param string $linkedProperty
	 * @param string $linkedEntityName
	 * @param string $linkedEntityId
	 * @param string $oldLinkedEntityId
	 * @return bool
	 */
	public function changeLinkedEntity(string $entityName,
			string $id,
			string $linkedProperty,
			string $linkedEntityName,
			string $linkedEntityId,
			string $oldLinkedEntityId) {
		try {
			$entity = $this->dao->getById($entityName, $id);
			$linkedEntity = $this->dao->getById($linkedEntityName, $linkedEntityId);
			$oldLinkedEntity = $this->dao->getById($linkedEntityName, $oldLinkedEntityId);
			$entity->{"change" . ucfirst($linkedProperty)}($oldLinkedEntity, $linkedEntity);
			$this->dao->flush();
			return $linkedEntityId;
		} catch (\Doctrine\DBAL\DBALException $e) {
			return $oldLinkedEntityId;
		} catch (\Exception $e) {
			return $oldLinkedEntityId;
		}
	}
	
	/**
	 * Ordena un array d'instàncies segons un camp
	 * @param array $instances
	 * @param string $field
	 * @return array
	 */
	public function order(array $instances, string $field): array {
	    $result = usort($instances, function($a, $b) use ($field) {
	        $a = $a->{'get' . ucfirst($field)}();
	        $b = $b->{'get' . ucfirst($field)}();
	        if (is_int($a) && is_int($b)) {
	            return $a == $b ? 0 : (a > b? 1 : -1);
	        } else if (is_string($a) && is_string($b)) {
	            return strcmp($a, $b);
	        } else return 0;
	    });
	    return $instances;
	}
}