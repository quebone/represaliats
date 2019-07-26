<?php
namespace Represaliats\Service\Entities;

use Represaliats\DAO;

/**
 * Classe que modela la relació entre una persona i el comitè de que va formar part
 *
 * @Entity @Table(name="membre_comite")
 */

class MembreComite implements IEntity
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;
    /** @ManyToOne(targetEntity="Persona", inversedBy="comites") **/
    private $persona;
    /** @Column(type="date", nullable=true) **/
    private $dataEntrada;
    /** @Column(type="date", nullable=true) **/
    private $dataSortida;
    /** @Column(type="string", length=50, nullable=true) **/
    private $causa;
    /** @ManyToOne(targetEntity="Partit") **/
    private $partit;
    /** @ManyToOne(targetEntity="Sindicat") **/
    private $sindicat;
    
    public function __construct() {
        $dao = DAO::getInstance();
        $this->partit = $dao->getByFilter("Partit", ["nom" => DEFAULTVALUE])[0];
        $this->sindicat = $dao->getByFilter("Sindicat", ["nom" => DEFAULTVALUE])[0];
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
    
    public function getPartit(): Partit {
      return $this->partit;
    }
    
    public function setPartit(Partit $partit) {
        $this->partit = $partit;
    }
    
    public function changePartit(Partit $old, Partit $new) {
        $this->partit = $new;
    }
    
    public function getSindicat(): Sindicat {
      return $this->sindicat;
    }
    
    public function setSindicat(Sindicat $sindicat) {
        $this->sindicat = $sindicat;
    }
    
    public function changeSindicat(Sindicat $old, Sindicat $new) {
        $this->sindicat = $new;
    }
    
    public function toArray(): array {
        return ["id" => $this->id, "data" => [
            "dataEntrada" => $this->getDataEntrada(),
            "dataSortida" => $this->getDataSortida(),
            "causa" => $this->causa,
            "partit" => $this->partit->getId(),
            "sindicat" => $this->sindicat->getId(),
        ]];
    }
    
    public function getValues(): array {
        return ["id" => $this->id, "data" => [
            "dataEntrada" => $this->getDataEntrada(),
            "dataSortida" => $this->getDataSortida(),
            "causa" => $this->causa,
            "partit" => $this->partit->getId(),
            "sindicat" => $this->sindicat->getId(),
        ]];
    }
    
    public function __toString(): string {
        return json_encode($this);
    }
}