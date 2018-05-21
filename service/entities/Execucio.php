<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela una execució
 *
 * @Entity @Table(name="execucions")
 */

class Execucio implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=20, unique=true) **/
	private $tipus;
	
	public function __construct() {
	
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getTipus(): string {
		return $this->tipus;
	}
	
	public function setTipus(string $tipus) {
		$this->tipus = $tipus;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"tipus" => $this->tipus,
		];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}