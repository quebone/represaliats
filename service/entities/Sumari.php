<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Represaliats\DAO;
use Doctrine\Common\Collections\Selectable;

/**
 * Classe que modela un sumari
 *
 * @Entity @Table(name="sumaris")
 */

class Sumari implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="integer", nullable=true) **/
	private $numSumari;
	/** @Column(type="date", nullable=true) **/
	private $dataInici;
	/** @ManyToOne(targetEntity="Municipi") */
	private $llocDetencio;
	/** @Column(type="date", nullable=true) **/
	private $dataDetencio;
	/**
	 * @ManyToOne(targetEntity="Acusacio")
	 * @JoinColumn(name="acusacio_id", referencedColumnName="id")
	 */
	private $acusacio;
	/** @Column(type="boolean") **/
	private $consellGuerra;
	/**
	 * @ManyToOne(targetEntity="Municipi")
	 * @JoinColumn(name="municipi_consell_id", referencedColumnName="id")
	 */
	private $municipiConsell;
	/** @Column(type="date", nullable=true) **/
	private $dataConsellGuerra;
	/** @Column(type="string", length=50, nullable=true) **/
	private $penaInicial;
	/** @Column(type="date", nullable=true) **/
	private $dataPena;
	/**  @OneToMany(targetEntity="Commutacio", mappedBy="sumari", cascade="all") **/
	private $commutacions;
	/** @OneToMany(targetEntity="SumariLlibertat", mappedBy="sumari", cascade="all") **/
	private $llibertats;
	/** @Column(type="boolean") **/
	private $hasExecucio;
	/** @OneToOne(targetEntity="SumariExecucio", cascade="remove") **/
	private $execucio;
	/** @Column(type="string", length=2048, nullable=true) **/
	private $observacions;
	
	public function __construct() {
		$dao = DAO::getInstance();
		$this->llibertats = new ArrayCollection();
		$this->commutacions = new ArrayCollection();
		$this->consellGuerra =false;
		$this->hasExecucio = false;
		$this->llocDetencio = $dao->getById("Municipi", 1);
		$this->municipiConsell = $dao->getById("Municipi", 1);
		$this->execucio = new SumariExecucio();
		$dao->persist($this->execucio);
		$this->acusacio = $dao->getById("Acusacio", 1);
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getNumSumari(): ?int {
		return $this->numSumari;
	}
	
	public function setNumSumari(?int $numero) {
		$this->numSumari = $numero;
	}

	public function getDataInici(): ?string {
		if ($this->dataInici != null) return $this->dataInici->format("Y-m-d");
		return null;
	}
	
	public function setDataInici($dataInici) {
		if (is_string($dataInici)) $dataInici = \DateTime::createFromFormat("Y-m-d", $dataInici);
		$this->dataInici = $dataInici;
	}

	public function getLlocDetencio(): ?Municipi {
		return $this->llocDetencio;
	}
	
	public function setLlocDetencio(Municipi $llocDetencio) {
		$this->llocDetencio = $llocDetencio;
	}

	public function getDataDetencio(): ?string {
		if ($this->dataDetencio != null) return $this->dataDetencio->format("Y-m-d");
		return null;
	}
	
	public function setDataDetencio($dataDetencio) {
		if (is_string($dataDetencio)) $dataDetencio = \DateTime::createFromFormat("Y-m-d", $dataDetencio);
		$this->dataDetencio = $dataDetencio;
	}

	public function getConsellGuerra(): bool {
		return $this->consellGuerra;
	}
	
	public function setConsellGuerra(bool $consellGuerra) {
		$this->consellGuerra = $consellGuerra;
	}
	
	public function getMunicipiConsell(): ?Municipi {
		return $this->municipiConsell;
	}
	
	public function setMunicipiConsell(Municipi $municipiConsell) {
		$this->municipiConsell = $municipiConsell;
	}
	
	public function getDataConsellGuerra(): ?string {
		if ($this->dataConsellGuerra != null) return $this->dataConsellGuerra->format("Y-m-d");
		return null;
	}
	
	public function setDataConsellGuerra($dataConsellGuerra) {
		if (is_string($dataConsellGuerra)) $dataConsellGuerra = \DateTime::createFromFormat("Y-m-d", $dataConsellGuerra);
		$this->dataConsellGuerra = $dataConsellGuerra;
	}
	
	public function getAcusacio(): Acusacio {
		return $this->acusacio;
	}
	
	public function setAcusacio(Acusacio $acusacio) {
		$this->acusacio = $acusacio;
	}

	public function getPenaInicial(): ?string {
		return $this->penaInicial;
	}
	
	public function setPenaInicial(?string $penaInicial) {
		$this->penaInicial = $penaInicial;
	}
	
	public function getDataPena(): ?string {
		if ($this->dataPena != null) return $this->dataPena->format("Y-m-d");
		return null;
	}
	
	public function setDataPena($dataPena) {
		if (is_string($dataPena)) $dataPena = \DateTime::createFromFormat("Y-m-d", $dataPena);
		$this->dataPena = $dataPena;
	}

	public function getCommutacions(): Selectable {
		return $this->commutacions;
	}
	
	public function setCommutacions(ArrayCollection $commutacions) {
		$this->commutacions = $commutacions;
	}

	public function addCommutacions() {
		$instance = new Commutacio();
		$this->commutacions->add($instance);
		$instance->setSumari($this);
		return $instance;
	}
	
	public function changeCommutacions(Commutacio $old, Commutacio $new) {
		if ($this->commutacions->contains($old) && !$this->commutacions->contains($new)) {
			$this->removeCommutacions($old);
			$this->addCommutacions($new);
		}
	}
	
	public function removeCommutacions(Commutacio $commutacio = null) {
		if ($this->commutacions->contains($commutacio)) {
			$this->commutacions->removeElement($commutacio);
			$dao = DAO::getInstance();
			$dao->remove($commutacio);
		}
	}
	
	public function getLlibertats(): Selectable {
		return $this->llibertats;
	}
	
	public function setLlibertats(ArrayCollection $llibertats) {
		$this->llibertats = $llibertats;
	}
	
	public function addLlibertats() {
		$instance = new SumariLlibertat();
		$this->llibertats->add($instance);
		$instance->setSumari($this);
		return $instance;
	}
	
	public function changeLlibertats(SumariLlibertat $old, SumariLlibertat $new) {
		if ($this->llibertats->contains($old) && !$this->llibertats->contains($new)) {
			$this->removeLlibertats($old);
			$this->addLlibertats($new);
		}
	}
	
	public function removeLlibertats(SumariLlibertat $llibertat = null) {
		if ($this->llibertats->contains($llibertat)) {
			$this->llibertats->removeElement($llibertat);
			$dao = DAO::getInstance();
			$dao->remove($llibertat);
		}
	}
	
	public function getHasExecucio(): bool {
		return $this->hasExecucio;
	}
	
	public function setHasExecucio(bool $hasExecucio) {
		$this->hasExecucio = $hasExecucio;
	}
	
	public function getExecucio(): SumariExecucio {
		return $this->execucio;
	}
	
	public function setExecucio(SumariExecucio $execucio) {
		$this->execucio = $execucio;
	}

	public function getObservacions(): ?string {
		return $this->observacions;
	}
	
	public function setObservacions(?string $observacions) {
		$this->observacions = $observacions;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"numSumari" => $this->numSumari,
				"dataInici" => $this->getDataInici(),
				"llocDetencio" => $this->getLlocDetencio()->toArray(),
				"dataDetencio" => $this->getDataDetencio(),
				"acusacio" => $this->getAcusacio()->toArray(),
				"consellGuerra" => $this->consellGuerra,
				"municipiConsell" =>$this->municipiConsell->toArray(),
				"dataConsellGuerra" => $this->getDataConsellGuerra(),
				"penaInicial" => $this->penaInicial,
				"dataPena" => $this->getDataPena(),
				"hasExecucio" => $this->hasExecucio,
				"execucio" => $this->execucio->toArray(),
				"observacions" => $this->observacions,
		];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}