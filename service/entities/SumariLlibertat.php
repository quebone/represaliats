<?php
namespace Represaliats\Service\Entities;

use Represaliats\DAO;

/**
 * Classe que modela la relació entre un sumari i el tipus de llibertat
 *
 * @Entity @Table(name="sumari_llibertat")
 */

class SumariLlibertat implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="date", nullable=true) **/
	private $dataLlibertat;
	/** @ManyToOne(targetEntity="Sumari", inversedBy="llibertats") **/
	private $sumari;
	/** @ManyToOne(targetEntity="Llibertat") **/
	private $llibertat;
	
	public function __construct() {
		$dao = DAO::getInstance();
		$this->llibertat = $dao->getById("Llibertat", 1);
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getDataLlibertat(): ?string {
		if ($this->dataLlibertat != null) return $this->dataLlibertat->format("Y-m-d");
		return null;
	}
	
	public function setDataLlibertat($dataLlibertat) {
		if (is_string($dataLlibertat)) $dataLlibertat = \DateTime::createFromFormat("Y-m-d", $dataLlibertat);
		$this->dataLlibertat = $dataLlibertat;
	}
	
	public function getSumari(): Sumari {
		return $this->sumari;
	}
	
	public function setSumari(Sumari $sumari) {
		$this->sumari = $sumari;
	}
	
	public function getLlibertat(): Llibertat {
		return $this->llibertat;
	}
	
	public function setLlibertat(Llibertat $llibertat) {
		$this->llibertat = $llibertat;
	}
	
	public function changeLlibertat(Llibertat $old, Llibertat $new) {
		$this->llibertat = $new;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"dataLlibertat" => $this->getDataLlibertat(),
				"llibertat" => $this->llibertat->toArray(),
		];
	}

	public function getValues(): array {
		return ["id" => $this->id, "data" => [
				"llibertat" => $this->llibertat->getId(),
				"dataLlibertat" => $this->getDataLlibertat(),
		]];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}