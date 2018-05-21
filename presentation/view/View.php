<?php
namespace Represaliats\Presentation\View;

use Represaliats\DAO;

class View
{
	protected $dao;
	
	public function __construct() {
		$this->dao = DAO::getInstance();
	}
	
	/**
	 * Retorna totes les instàncies d'un tipus
	 * @param string $entityName
	 * @param array $order
	 * @return array
	 */
	public function getEntity(string $entityName, $order=[]): array {
		$data = [];
		$instances = $this->dao->getByFilter($entityName, [], $order);
		foreach ($instances as $instance) {
			array_push($data, $instance->toArray());
		}
		return $data;
	}
	
	/**
	 * ordena ascendentment un array segons un camp
	 * @param array $entity
	 * @param string $field
	 * @return array
	 */
	public function sortEntity(array $entity, string $field):array {
		usort($entity, $this->sortField($field));
		return $entity;
	}
	
	private function sortField($key) {
		return function($a, $b) use ($key) {
			if (is_numeric($a[$key])) {
				return ($a[$key] == $b[$key] ? 0 : ($a[$key] > $b[$key] ? 1 : -1 ));
			} else {
			    return strcmp($this->removeAccents($a[$key]), $this->removeAccents($b[$key]));
			}
		};
	}
	
	private function removeAccents(string $input): string {
	    $transliterationTable = ['á'=>'a', 'Á'=>'A', 'à'=>'a', 'À'=>'A', 'é'=>'e', 'É'=>'e', 'è'=>'e', 'È'=>'E', 'È'=>'E',
	        'í'=>'i', 'Í'=>'I', 'ó'=>'o', 'Ó'=>'O', 'ò'=>'o', 'Ò'=>'O', 'ú'=>'u', 'Ú'=>'U'];
	    $output = str_replace(array_keys($transliterationTable), array_values($transliterationTable), $input);
	    return trim(strtolower($output));
	}
	
	/**
	 * Elimina l'element d'un array amb el valor per defecte
	 * @param array $entities
	 * @param string $field
	 * @return array
	 */
	public function removeDefault(array $entities, string $field): array {
		foreach ($entities as $key=>$value) {
			if (!strcmp($entities[$key][$field], DEFAULTVALUE)) {
				unset($entities[$key]);
			}
		}
		return $entities;
	}
}