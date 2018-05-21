<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela un rang de dates
 *
 * @Entity @Table(name="rangs_dates")
 */

class RangDates implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="date", nullable=true) **/
	private $dataInici;
	/** @Column(type="date", nullable=true) **/
	private $dataFi;
	
	public function __construct() {
	
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getDataInici(): ?string {
		if ($this->dataInici != null) return $this->dataInici->format("Y-m-d");
		return null;
	}
	
	public function setDataInici($dataInici) {
		if (is_string($dataInici)) $dataInici = \DateTime::createFromFormat("Y-m-d", $dataInici);
		$this->dataInici = $dataInici;
	}
	
	public function getDataFi(): ?string {
		if ($this->dataFi != null) return $this->dataFi->format("Y-m-d");
		return null;
	}
	
	public function setDataFi($dataFi) {
		if (is_string($dataFi)) $dataFi = \DateTime::createFromFormat("Y-m-d", $dataFi);
		$this->dataFi= $dataFi;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"dataInici" => $this->getDataInici(),
				"dataFi" => $this->getDataFi(),
		];
	}

	public function getValues(): array {
		return ["id" => $this->id, "data" => ["dataInici" => $this->getDataInici(), "dataFi" => $this->getDataFi()]];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}