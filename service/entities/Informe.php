<?php
namespace Represaliats\Service\Entities;

use Represaliats\DAO;

/**
 * Classe que modela un informe
 *
 * @Entity @Table(name="informes")
 */

class Informe implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=20, nullable=true) **/
	private $registre;
	/** @Column(type="date", nullable=true) **/
	private $data;
	/** @ManyToOne(targetEntity="Organisme") **/
	private $organisme;
	/** @ManyToOne(targetEntity="Font") **/
	private $font;
	/** @Column(type="integer", nullable=true) **/
	private $capsa;
	
	public function __construct() {
		$dao = DAO::getInstance();
		$this->organisme = $dao->getByFilter("Organisme", ["nom"=>DEFAULTVALUE])[0];
		$this->font = $dao->getByFilter("Font", ["nom"=>DEFAULTVALUE])[0];
	}
	
	public function getId(): int {
		return $this->id;
	}

	public function getRegistre(): ?string {
		return $this->registre;
	}
	
	public function setRegistre(string $lloc) {
		$this->registre = $lloc;
	}

	public function getData(): ?string {
		if ($this->data != null) return $this->data->format("Y-m-d");
		return $this->data;
	}
	
	public function setData($data) {
		if (is_string($data)) $data = \DateTime::createFromFormat("Y-m-d", $data);
		$this->data = $data;
	}
	
	public function getOrganisme(): ?Organisme {
		return $this->organisme;
	}
	
	public function setOrganisme(Organisme $organisme) {
		$this->organisme = $organisme;
	}

	public function getFont(): ?Font {
		return $this->font;
	}
	
	public function setFont(Font $font) {
		$this->font = $font;
	}
	
	public function getCapsa(): int {
		return $this->capsa;
	}
	
	public function setCapsa(int $capsa) {
		$this->capsa = $capsa;
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"registre" => $this->registre,
				"data" => $this->data,
				"organisme" => $this->organisme->toArray(),
				"font" => $this->font->toArray(),
				"capsa" => $this->capsa,
		];
	}

	public function getValues(): array {
		return ["id" => $this->id, "data" => [
				"registre" => $this->registre,
				"data" => $this->getData(),
				"organisme" => $this->organisme->getId(),
				"font" => $this->font->getId(),
				"capsa" => $this->capsa,
		]];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}