<?php
namespace Represaliats\Service\Entities;

use Represaliats\DAO;
use Represaliats\Service\Utils;

/**
 * Classe que modela una vista de dades
 *
 * @Entity @Table(name="vistes")
 */

class Vista implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=32) **/
	private $name;
	/** @ManyToOne(targetEntity="Filtre") **/
	private $filtre;
	/** @Column(type="string", length=2048) **/
    private $fields;
        
	public function __construct(string $name, array $fields=[]) {
	    $this->name = $name;
	    $dao = DAO::getInstance();
	    $this->filtre = $dao->getById('Filtre', 1);
	    $this->setFields($fields);
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getName(): string {
		return $this->name;
	}
	
	public function setName(string $name) {
		$this->name = $name;
	}
	
	public function getFiltre(): Filtre {
	    return $this->filtre;
	}
	
	public function setFiltre(Filtre $filtre) {
	    $this->filtre = $filtre;
	}
	
	public function getFields(): array {
	    return json_decode($this->fields);
	}
	
	public function setFields(array $fields) {
	    $this->fields = json_encode($fields);
	}
	
	public function getLabels(): array {
	    $data = [];
	    $fields = $this->getFields();
	    foreach ($fields as $field) $data[] = $field[0];
	    return $data;
	}
	
	public function getFieldsContent(): array {
	    $data = [];
	    $fields = $this->getFields();
	    foreach ($fields as $field) {
	        $attributes = $field[1];
	        $data[] = Utils::strToArray($field[1], "%");
	    }
	    return $data;
	}
	
	public function toArray(): array {
		return [
		    "id" => $this->id,
		    "name" => $this->name,
		    "filtre" => $this->filtre->toArray(),
		    "fields" => $this->getFields(),
		];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}