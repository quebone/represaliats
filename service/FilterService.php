<?php
namespace Represaliats\Service;

use Represaliats\DAO;

/**
 * Processa filtres assignats a les propietats d'una entitat
 * @author carles
 *
 */
class FilterService
{
    private $entity;
    private $filters;
    private $dao;
    
    /**
     * @param string $entity
     * @param array $filters structure: [[$fields, [$values]], ...]
     */
    public function __construct(string $entity, array $filters) {
        $this->entity = ucfirst(strtolower($entity));
        $this->filters = $filters;
        $this->dao = DAO::getInstance();
    }
    
    /**
     * Converteix una cadena de camps separats per punt en un array
     * @param string $fields
     * @return array
     */
    private function parseFields(string $fields): array {
        return explode(".", $fields);
    }
    
    private function isArrayCollection(int $index): bool {
        return in_array($index, $this->collections);
    }
    
    /**
     * Retorna els tipus d'objecte de cada camp del filtre
     * @param array $fields
     * @return array
     */
    private function getFilterTypes(array $fields): array {
        $data = [];
        $entity = ENTITIESNS . $this->entity;
        foreach ($fields as $key => $property) {
            $tmpEntity = ($key == 0 ? $entity : $data[$key-1]["type"]);
            $method = new \ReflectionMethod($tmpEntity, "get" . ucfirst($property));
            if ($method->getReturnType() == "Doctrine\\Common\\Collections\\Selectable") {
                $method = new \ReflectionMethod($tmpEntity, "remove" . ucfirst($property));
                $objType = $method->getParameters()[0]->getType()->getName();
                $isCollection = true;
            } else {
                $objType = $method->getReturnType()->getName();
                $isCollection = false;
            }
            $data[$key] = ["property" => $property, "type" => $objType, "isCollection" => $isCollection];
        }
        return $data;
    }
    
    /**
     * Retorna les instàncies que contenen un valor
     * @param array $objTypes
     * @param int $index
     * @param array $values
     * @return array
     */
    private function getInstances(array $objTypes, int $index, array $values): array {
        $instances = [];
        $entity = $index > 0 ? $objTypes[$index-1]["type"] : $this->entity;
        $property = $objTypes[$index]["property"];
        if ($objTypes[$index]["isCollection"]) {
            $tmp = $this->dao->getByFilter($entity);
            foreach ($tmp as $key => $object) {
                $collection = $object->{"get" . ucfirst($property)}()->getValues();
                $found = false;
                foreach ($values as $value) {
                    if (in_array($value, $collection)) $found = true;
                }
                if (!$found) unset($tmp[$key]);
            }
            $instances = array_merge($instances, $tmp);
        } else {
            try {
                foreach ($values as $value) {
                    $data = [];
                    if (!strcmp($property, "id")) {
                        $data[] = $this->dao->getById($entity, $value);
                    } else {
                        $data = $this->dao->getByFilter($entity, [$property => $value]);
                    }
                    $instances = array_merge($instances, $data);
                }
            } catch (\Exception $e) {
                //TODO
            }
        }
        return $instances;
    }
    
    /**
     * Retorna totes les instàncies que corresponen als filtres
     * @return array
     */
    public function applyFilters(): array {
        if (count($this->filters) == 0) return $this->dao->getByFilter($this->entity);
        $instances = [];
        $firstFilter = true;
        foreach ($this->filters as $filter) {
            $fields = $this->parseFields($filter[0]);
            $arrValues = $filter[1];
            $objTypes = $this->getFilterTypes($fields);
            $valueInstances = [];
            foreach ($arrValues as $values) {
                $valueInstances[] = $values;
                for ($i = count($fields)-1; $i >= 0; $i--) {
                    $valueInstances = $this->getInstances($objTypes, $i, $valueInstances);
                    if (count($valueInstances) == 0) break;
                }
            }
            if ($firstFilter) {
                $instances = $valueInstances;
                $firstFilter = false;
            } else {
                $instances = array_intersect($instances, $valueInstances);
            }
        }
        return $instances;
    }
}