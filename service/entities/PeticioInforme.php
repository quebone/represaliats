<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Represaliats\DAO;
use Doctrine\Common\Collections\Selectable;

/**
 * Classe que modela la petició d'un informe
 *
 * @Entity @Table(name="peticions_informe")
 */

class PeticioInforme implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="date", nullable=true) **/
	private $data;
	/** @Column(type="string", length=50, nullable=true) **/
	private $lloc;
	/** @OneToOne(targetEntity="Informe", cascade="all") **/
	private $informe;
	/** @ManyToOne(targetEntity="Organisme") **/
	private $organisme;
	/**
	 * @ManyToOne(targetEntity="Font")
	 * @JoinColumn(name="font_id", referencedColumnName="id")
	 */
	private $font;
	/** @Column(type="integer", nullable=true) **/
	private $capsa;
	/**
	 * @ManyToMany(targetEntity="Persona", mappedBy="peticionsInforme")
	 */
	private $persones;
	    
	public function __construct() {
		$dao = DAO::getInstance();
		$this->persones = new ArrayCollection();
		$this->informe = new Informe();
		$this->organisme = $dao->getByFilter("Organisme", ["nom"=>DEFAULTVALUE])[0];
		$this->font = $dao->getByFilter("Font", ["nom"=>DEFAULTVALUE])[0];
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getData(): ?string {
		if ($this->data != null) return $this->data->format("Y-m-d");
		return $this->data;
	}
	
	public function setData($data) {
		if (is_string($data)) $data = \DateTime::createFromFormat("Y-m-d", $data);
		$this->data = $data;
	}

	public function getLloc(): ?string {
		return $this->lloc;
	}
	
	public function setLloc(string $lloc) {
		$this->lloc = $lloc;
	}
	
	public function getInforme(): ?Informe {
		return $this->informe;
	}
	
	public function setInforme(Informe $informe) {
		$this->informe = $informe;
	}

	public function getOrganisme(): ?Organisme {
		return $this->organisme;
	}
	
	public function setOrganisme(Organisme $organisme) {
		$this->organisme = $organisme;
	}

	public function getPersones(): Selectable {
		return $this->persones;
	}
	
	public function setPersones(ArrayCollection $persones) {
		$this->persones = $persones;
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
		];
	}

	public function getValues(): array {
		return ["id" => $this->id, "data" => [
				"lloc" => $this->lloc,
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