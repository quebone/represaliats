<?php
namespace Represaliats\Service\Entities;

use Represaliats\DAO;

/**
 * Classe que modela una participació a un ajuntament
 *
 * @Entity @Table(name="ajuntaments")
 */

class Ajuntament implements IEntity
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;
    /** @ManyToOne(targetEntity="Persona", inversedBy="ajuntaments") **/
    private $persona;
    /** @Column(type="date", nullable=true) **/
    private $dataEntrada;
    /** @Column(type="date", nullable=true) **/
    private $dataSortida;
    /** @Column(type="string", length=50, nullable=true) **/
    private $causa;
    
    public function __construct() {
        $dao = DAO::getInstance();
    }
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getPersona(): Persona {
        return $this->persona;
    }
    
    public function setPersona($persona) {
        $this->persona = $persona;
    }
    
    public function getDataEntrada(): ?string {
        if ($this->dataEntrada != null) return $this->dataEntrada->format("Y-m-d");
        return null;
    }
    
    public function setDataEntrada($dataEntrada) {
        if (is_string($dataEntrada)) $dataEntrada = \DateTime::createFromFormat("Y-m-d", $dataEntrada);
        $this->dataEntrada = $dataEntrada;
    }
    
    public function getDataSortida(): ?string {
        if ($this->dataSortida != null) return $this->dataSortida->format("Y-m-d");
        return null;
    }
    
    public function setDataSortida($dataSortida) {
        if (is_string($dataSortida)) $dataSortida = \DateTime::createFromFormat("Y-m-d", $dataSortida);
        $this->dataSortida = $dataSortida;
    }
    
    public function getCausa() {
        return $this->causa;
    }
    
    public function setCausa(?string $causa) {
        $this->causa = $causa;
    }
    
    public function toArray(): array {
        return ["id" => $this->id, "data" => [
            "dataEntrada" => $this->getDataEntrada(),
            "dataSortida" => $this->getDataSortida(),
            "causa" => $this->causa,
        ]];
    }
    
    public function getValues(): array {
        return ["id" => $this->id, "data" => [
            "dataEntrada" => $this->getDataEntrada(),
            "dataSortida" => $this->getDataSortida(),
            "causa" => $this->causa,
        ]];
    }
    
    public function __toString(): string {
        return json_encode($this);
    }
}