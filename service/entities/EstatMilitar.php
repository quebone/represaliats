<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Selectable;

/**
 * Classe que modela un estat militar
 *
 * @Entity @Table(name="estats_militars")
 */

class EstatMilitar implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50, unique=true) **/
	private $nom;
	/**
	 * Persones que han tingut aquest estat militar
	 * @OneToMany(targetEntity="Persona", mappedBy="estatMilitar")
	 */
	private $persones;
	
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
	
	public function getPersones(): Selectable {
		return $this->persones;
	}
	
	public function setPersones(ArrayCollection $persones) {
		$this->persones = $persones;
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