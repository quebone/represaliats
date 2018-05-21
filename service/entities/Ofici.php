<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela un ofici
 *
 * @Entity @Table(name="oficis")
 */

class Ofici implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50, unique=true) **/
	private $nom;
	    
	public function __construct() {
		
	}

	public function getId(): int {
		return $this->id;
	}
	
	public function getNom(): string {
		return $this->nom;
	}
	
	public function setNom(string $nom) {
		$this->nom = $nom;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"nom" => $this->nom,
		];
	}

	public function getValues(): array {
		return ["id" => $this->id, "data" => ["id" => $this->id]];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}