<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela un organisme que fa una petició d'informe
 *
 * @Entity @Table(name="organismes")
 */

class Organisme implements IEntity
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
	
	public function __toString(): string {
	    return json_encode($this);
	}
}