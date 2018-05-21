<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela una commutacio de pena
 *
 * @Entity @Table(name="commutacions")
 */

class Commutacio implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=30, nullable=true) **/
	private $tipusCommutacio;
	/** @Column(type="date", nullable=true) **/
	private $dataCommutacio;
	/**
	 * @ManyToOne(targetEntity="Sumari", inversedBy="commutacions")
	 * @JoinColumn(name="sumari_id", referencedColumnName="id")
	 */
	private $sumari;

	public function __construct() {

	}

	public function getId(): int {
		return $this->id;
	}

	public function getTipusCommutacio(): ?string {
		return $this->tipusCommutacio;
	}

	public function setTipusCommutacio(?string $tipus) {
		$this->tipusCommutacio = $tipus;
	}

	public function getSumari(): Sumari {
		return $this->sumari;
	}
	
	public function setSumari(Sumari $sumari) {
		$this->sumari = $sumari;
	}

	public function getDataCommutacio(): ?string {
		if ($this->dataCommutacio != null) return $this->dataCommutacio->format("Y-m-d");
		return null;
	}
	
	public function setDataCommutacio($dataCommutacio) {
		if (is_string($dataCommutacio)) $dataCommutacio = \DateTime::createFromFormat("Y-m-d", $dataCommutacio);
		$this->dataCommutacio = $dataCommutacio;
	}
	
	public function toArray(): array {
		return ["id" => $this->id,
				"tipusCommutacio" => $this->tipusCommutacio,
				"dataCommutacio" => $this->getDataCommutacio(),
		];
	}

	public function getValues(): array {
		return ["id" => $this->id, "data" => [
				"tipusCommutacio" => $this->tipusCommutacio,
				"dataCommutacio" => $this->getDataCommutacio(),
		]];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}