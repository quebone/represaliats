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
    /** @ManyToOne(targetEntity="Comite") **/
    private $comite;
    /** @Column(type="string", length=50, nullable=true) **/
    private $causa;
    /** @ManyToOne(targetEntity="Partit") **/
    private $partit;
    /** @ManyToOne(targetEntity="Sindicat") **/
    private $sindicat;
    
    public function __construct() {
        $dao = DAO::getInstance();
        $this->comite = $dao->getById("Comite", 1);
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
    
    public function getComite(): Comite {
      return $this->comite;
    }
    
    public function setComite(Comite $comite) {
        $this->comite = $comite;
    }
    
    public function changeComite(Comite $old, Comite $new) {
        $this->comite = $new;
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
            "comite" => $this->comite->getId(),
            "causa" => $this->causa,
            "partit" => $this->partit->getId(),
            "sindicat" => $this->sindicat->getId(),
        ]];
    }
    
    public function getValues(): array {
        return ["id" => $this->id, "data" => [
            "comite" => $this->comite->getId(),
            "causa" => $this->causa,
            "partit" => $this->partit->getId(),
            "sindicat" => $this->sindicat->getId(),
        ]];
    }
    
    public function __toString(): string {
        return json_encode($this);
    }
}