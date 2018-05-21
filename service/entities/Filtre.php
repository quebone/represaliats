<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela un filtre
 *
 * @Entity @Table(name="filtres")
 */

class Filtre implements IEntity
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;
    /** @Column(type="string", length=32) **/
    private $name;
    /** @Column(type="string", length=256, nullable=true) **/
    private $fields;
    
    public function __construct(string $name, string $fields="") {
        $this->name = $name;
        $this->fields = $fields;
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
    
    public function getFields(): string {
        return $this->fields;
    }
    
    public function setFields(string $fields) {
        $this->fields = $fields;
    }
    
    public function getFieldsClean(): array {
        $filters = json_decode($this->getFields());
        foreach ($filters as $key=>$filter) {
            if (in_array("", $filter[1]) || in_array("0", $filter[1])) unset($filters[$key]);
        }
        return $filters;
    }
    
    public function toArray(): array {
        return [
            "id" => $this->id,
            "name" =>$this->name,
            "fields" => $this->fields,
        ];
    }
    
    public function __toString(): string {
        return json_encode($this);
    }
}