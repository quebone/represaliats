<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela una llibertat
 *
 * @Entity @Table(name="llibertats")
 */

class Llibertat implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50, unique=true) **/
	private $tipus;
	/**
	 * Un sumari té una o cap llibertat
	 * @OneToOne(targetEntity="SumariLlibertat", mappedBy="llibertat")
	 */
	private $sumari;
	
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
	
	public function getSumari(): SumariLlibertat {
		return $this->sumari;
	}
	
	public function setSumari(SumariLlibertat $sumari) {
		$this->sumari = $sumari;
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