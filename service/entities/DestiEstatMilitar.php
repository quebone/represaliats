<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Selectable;

/**
 * Classe que modela un país de destí d'estat militar
 *
 * @Entity @Table(name="destins_estat_militar")
 */

class DestiEstatMilitar implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50, unique=true) **/
	private $nom;
	/**
	 * Persones que han tingut aquest destí d'estat militar
	 * @OneToMany(targetEntity="Persona", mappedBy="destiEstatMilitar")
	 */
	private $persones;
	
	public function __construct() {
		$this->persones = new ArrayCollection();
	}

	public function getId(): int {
		return $this->id;
	}

	public function getnom(): string {
		return $this->nom;
	}

	public function setnom(string $nom) {
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