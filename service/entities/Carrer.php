<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Selectable;

/**
 * Classe que modela un carrer
 *
 * @Entity @Table(name="carrers")
 */

class Carrer implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=40, nullable=true) **/
	private $nomActual;
	/** @Column(type="string", length=40, nullable=true) **/
	private $nomComite;
	/** @Column(type="string", length=40, nullable=true) **/
	private $nomFranquista;
	/** @Column(type="string", length=40, nullable=true) **/
	private $nomAnterior;
	/**
	 * @OneToMany(targetEntity="Persona", mappedBy="carrer")
	 */
	private $persones;
	
	public function __construct() {
		$this->persones = new ArrayCollection();
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getNomActual(): ?string {
		return $this->nomActual;
	}
	
	public function setNomActual(string $nomActual) {
		$this->nomActual = $nomActual;
	}
	
	public function getNomComite(): ?string {
		return $this->nomComite;
	}
	
	public function setNomComite(string $nomComite) {
		$this->nomComite = $nomComite;
	}
	
	public function getNomFranquista(): ?string {
		return $this->nomFranquista;
	}
	
	public function setNomFranquista(string $nomFranquista) {
		$this->nomFranquista = $nomFranquista;
	}
	
	public function getNomAnterior(): ?string {
		return $this->nomAnterior;
	}
	
	public function setNomAnterior(string $nomAnterior) {
		$this->nomAnterior = $nomAnterior;
	}
	
	public function getNom(): ?string {
	    return $this->nomAnterior;
	}
	
	public function getPersones(): Selectable {
		return $this->persones;
	}
	
	public function setPersones(ArrayCollection $persones) {
		$this->persones = $persones;
	}
	
	public function toArray():array {
		$data = [
				"id" => $this->id,
				"nomActual" => $this->nomActual,
				"nomComite" => $this->nomComite,
				"nomFranquista" => $this->nomFranquista,
				"nomAnterior" => $this->nomAnterior,
		];
		return $data;
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}