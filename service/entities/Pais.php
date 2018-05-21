<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Classe que modela un país
 *
 * @Entity @Table(name="paisos")
 */

class Pais implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50, unique=true) **/
	private $nom;
	 
	public function __construct() {
		$this->persones = new ArrayCollection();
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
	
	public function toArray():array {
		return [
				"id" => $this->id,
				"nom" => $this->nom,
		];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}