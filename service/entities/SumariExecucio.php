<?php
namespace Represaliats\Service\Entities;

use Represaliats\DAO;

/**
 * Classe que modela la relació entre un sumari i el tipus d'execució
 *
 * @Entity @Table(name="sumari_execucio")
 */

class SumariExecucio implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @ManyToOne(targetEntity="Execucio") **/
	private $execucio;
	/** @Column(type="date", nullable=true) **/
	private $dataExecucio;
	/** @ManyToOne(targetEntity="LlocExecucio") **/
	private $llocExecucio;
	
	public function __construct() {
		$dao = DAO::getInstance();
		$this->execucio = $dao->getById("Execucio", 1);
		$this->llocExecucio = $dao->getById("LlocExecucio", 1);
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getExecucio(): Execucio {
		return $this->execucio;
	}
	
	public function setExecucio(Execucio $execucio) {
		$this->execucio = $execucio;
	}
	
	public function getDataExecucio(): ?string {
		if ($this->dataExecucio != null) return $this->dataExecucio->format("Y-m-d");
		return null;
	}
	
	public function setDataExecucio($dataExecucio) {
		if (is_string($dataExecucio)) $dataExecucio = \DateTime::createFromFormat("Y-m-d", $dataExecucio);
		$this->dataExecucio = $dataExecucio;
	}

	public function getLlocExecucio(): ?LLocExecucio {
		return $this->llocExecucio;
	}
	
	public function setLlocExecucio(LLocExecucio $lloc) {
		$this->llocExecucio = $lloc;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"execucio" => $this->execucio->toArray(),
				"dataExecucio" => $this->getDataExecucio(),
				"llocExecucio" => $this->llocExecucio->toArray(),
		];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}