<?php
namespace Represaliats\Service\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Represaliats\DAO;
use Doctrine\Common\Collections\Selectable;

/**
 * Classe que modela una persona
 *
 * @Entity @Table(name="persones")
 */

class Persona implements IEntity
{
	private $dao;
	
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=30) **/
	private $nom;
	/** @Column(type="string", length=50) **/
	private $cognoms;
	/** @Column(type="string", length=50, nullable=true) **/
	private $malnom;
	/** @Column(type="integer", nullable=true) **/
	private $edat;
	/** @ManyToOne(targetEntity="Municipi") */
	private $llocNaixement;
	/** @Column(type="date", nullable=true) **/
	private $dataNaixement;
	/** @ManyToOne(targetEntity="Carrer", inversedBy="persones") */
	private $carrer;
	/** @Column(type="integer", nullable=true) **/
	private $numCarrer;
	/**
	 * Dos pares tenen varis fills
	 * @ManyToMany(targetEntity="Persona", mappedBy="pares")
	 */
	private $fills;
	/**
	 * Varis fills tenen dos pares
	 * @ManyToMany(targetEntity="Persona", inversedBy="fills")
	 * @JoinTable(name="fills",
	 * 		joinColumns={@JoinColumn(name="pare_id", referencedColumnName="id")},
	 * 		inverseJoinColumns={@JoinColumn(name="fill_id", referencedColumnName="id")}
	 * )
	 */
	private $pares;
	/**
	 * Diverses persones poden tenir diverses parelles
	 * @ManyToMany(targetEntity="Persona", mappedBy="myParelles")
	 */
	private $parelles;
	/**
	 * Diverses persones poden tenir diverses parelles
	 * @ManyToMany(targetEntity="Persona", inversedBy="parelles")
	 * @JoinTable(name="parelles",
	 * 		joinColumns={@JoinColumn(name="persona_id", referencedColumnName="id")},
	 * 		inverseJoinColumns={@JoinColumn(name="parella_persona_id", referencedColumnName="id")}
	 * )
	 */
	private $myParelles;
	/**
	 * Vàries persones tenen varis oficis
	 * @ManyToMany(targetEntity="Ofici")
	 * @JoinTable(name="persones_oficis")     
	 */
	private $oficis;
	/** @ManyToOne(targetEntity="Municipi") */
	private $llocMort;
	/** @Column(type="date", nullable=true) **/
	private $dataMort;
	/** @Column(type="date", nullable=true) **/
	private $dataDefuncio;
	/** @Column(type="string", length=1024, nullable=true) **/
	private $observacions;
	/**
	 * Vàries persones militen a un o més partits
	 * @ManyToMany(targetEntity="Partit")
	 * @JoinTable(name="persones_partits")
	 */
	private $partits;
	/**
	 * Vàries persones militen a un o més sindicats
	 * @ManyToMany(targetEntity="Sindicat")
	 * @JoinTable(name="persones_sindicats")
	 */
	private $sindicats;
	/**
	 * Una persona té diverses situacions (represaliat, indefinit, mort al front...)
	 * @ManyToMany(targetEntity="TipusSituacio")
	 * @JoinTable(name="persones_tipusSituacio")
	 */
	private $tipusSituacio;
	/** Causa General
	 * @Column(type="boolean")
	 **/
	private $cg;
	/** @Column(type="boolean") **/
	private $hasSumari;
	/**
	 * Una persona té 1 o cap sumari
	 * @OneToOne(targetEntity="Sumari", cascade="remove")
	 */
	private $sumari;
	/** @Column(type="boolean") **/
	private $fetsOctubre;
	/**
	 * @ManyToOne(targetEntity="Font")
	 * @JoinColumn(name="font_fets_octubre_id", referencedColumnName="id")
	 */
	private $fontFetsOctubre;
	/**
	 * Una persona pot haver format part de varis comitès
	 * @OneToMany(targetEntity="MembreComite", mappedBy="persona", cascade="all")
	 */
	private $comites;
	/** @OneToMany(targetEntity="Ajuntament", mappedBy="persona", cascade="all") **/
	private $ajuntaments;
	 /** @Column(type="boolean") **/
	private $exiliat;
	/** @ManyToOne(targetEntity="Pais") */
	private $destiFinal;
	/** @ManyToOne(targetEntity="Municipi") */
	private $llocDestiFinal;
	/**
	 * Estat militar
	 * @ManyToOne(targetEntity="EstatMilitar", inversedBy="persones")
	 * @JoinColumn(name="estat_militar_id", referencedColumnName="id")
	 */
	private $estatMilitar;
	/**
	 * Destí d'estat militar
	 * @ManyToOne(targetEntity="DestiEstatMilitar", inversedBy="persones")
	 * @JoinColumn(name="desti_estat_militar_id", referencedColumnName="id")
	 */
	private $destiEstatMilitar;
	/** @Column(type="date", nullable=true) **/
	private $dataEstatMilitar;
	/** Tribunal de responsabilitats polítiques @Column(type="boolean") **/
	private $trp;
	/** @Column(type="date", nullable=true) **/
	private $dataTrp;
	/**
	 * Diverses persones tenen la mateixa font de TRP
	 * @ManyToOne(targetEntity="Font")
	 * @JoinColumn(name="font_trp_id", referencedColumnName="id")
	 */
	private $fontTrp;
	/** @Column(type="integer", nullable=true) **/
	private $capsaTrp;
	/** @Column(type="string", length=1024, nullable=true) **/
	private $observacionsSituacio;
	/**
	 * Una persona pot tenir diverses peticions d'informe, que poden correspondre a més d'una persona
	 * @ManyToMany(targetEntity="PeticioInforme", inversedBy="persones")
	 * @JoinTable(name="persones_peticions")
	 */
	private $peticionsInforme;
	/** @Column(type="string", length=1024, nullable=true) **/
	private $observacionsInformes;
	
	public function __construct(string $nom, string $cognoms) {
		$this->dao = DAO::getInstance();
		$this->nom = $nom;
		$this->cognoms = $cognoms;
		$this->parelles = new ArrayCollection();
		$this->myParelles = new ArrayCollection();
		$this->pares = new ArrayCollection();
		$this->oficis = new ArrayCollection();
		$this->peticionsInforme = new ArrayCollection();
		$this->ajuntaments = new ArrayCollection();
		$this->comites = new ArrayCollection();
		$this->cg = false;
		$this->fetsOctubre = false;
		$this->fontFetsOctubre = $this->dao->getById("Font", 1);
		$this->exiliat = false;
		$this->trp = false;
		$this->tipusSituacio = new ArrayCollection();
		$this->carrer = $this->dao->getById("Carrer", 1);
		$this->llocMort = $this->dao->getById("Municipi", 1);
		$this->llocNaixement = $this->dao->getById("Municipi", 1);
		$this->destiFinal = $this->dao->getById("Pais", 1);
		$this->llocDestiFinal = $this->dao->getById("Municipi", 1);
		$this->estatMilitar = $this->dao->getById("EstatMilitar", 1);
		$this->destiEstatMilitar = $this->dao->getById("DestiEstatMilitar", 1);
		$this->fontTrp = $this->dao->getById("Font", 1);
		$this->hasSumari = false;
		$this->sumari = new Sumari();
		$this->dao->persist($this->sumari);
	}
	
	public function getId(): int {
		return $this->id;
	}
	
	public function getNom(): ?string {
		return $this->nom;
	}
	
	public function setNom(string $nom) {
		$this->nom = $nom;
	}
	
	public function getCognoms(): ?string {
		return $this->cognoms;
	}
	
	public function setCognoms(string $cognoms) {
		$this->cognoms = $cognoms;
	}
	
	public function getMalnom(): ?string {
		return $this->malnom;
	}
	
	public function setMalnom(?string $malnom) {
		$this->malnom = $malnom;
	}
	
	public function getEdat():?int {
		return $this->edat;
	}
	
	public function setEdat($edat) {
		if (!is_numeric($edat)) $edat = null;
		$this->edat = $edat;
	}
	
	public function getDataNaixement(): ?string {
		if ($this->dataNaixement != null) return $this->dataNaixement->format("Y-m-d");
		return null;
	}
	
	public function setDataNaixement($dataNaixement) {
		if (is_string($dataNaixement)) $dataNaixement = \DateTime::createFromFormat("Y-m-d", $dataNaixement);
		$this->dataNaixement = $dataNaixement;
	}
	
	public function getCarrer(): ?Carrer {
		return $this->carrer;
	}
	
	public function setCarrer(Carrer $carrer) {
		$this->carrer = $carrer;
	}
	
	public function getNumCarrer(): ?int {
		return $this->numCarrer;
	}
	
	public function setNumCarrer(?int $numCarrer) {
		$this->numCarrer = $numCarrer;
	}
	
	public function getParelles(): Selectable {
		return $this->parelles;
	}
	
	public function setParelles(ArrayCollection $parelles) {
		$this->parelles = $parelles;
	}
	
	public function addParelles() {
		$dao = DAO::getInstance();
		$parelles = new ArrayCollection($dao->getByFilter("Persona"));
		$parelles->removeElement($this);
		foreach ($parelles as $parella) {
			if (!$this->parelles->contains($parella)) {
				$this->parelles->add($parella);
				$parella->addMyParelles($this);
				return $parella;
			}
		}
		throw new \Exception("Col·lecció plena");
	}
	
	public function changeParelles(Persona $old, Persona $new) {
		if ($this->parelles->contains($old) && !$this->parelles->contains($new)) {
			$this->removeParelles($old);
			$this->parelles->add($new);
			$new->myParelles->add($this);
		} else throw new \Exception("Parella existent");
	}
	
	public function removeParelles(Persona $parella = null) {
		if ($this->parelles->contains($parella)) {
			$this->parelles->removeElement($parella);
			$parella->removeMyParelles($this);
		}
	}
	
	public function getMyParelles(): Selectable {
		return $this->myParelles;
	}
	
	public function setMyParelles(ArrayCollection $myParelles) {
			$this->myParelles= $myParelles;
	}
	
	public function addMyParelles(Persona $parella = null) {
		if (!$this->myParelles->contains($parella)) {
			$this->myParelles->add($parella);
		}
	}
	
	public function removeMyParelles(Persona $parella = null) {
		if ($this->myParelles->contains($parella)) {
			$this->myParelles->removeElement($parella);
		}
	}
	
	public function getFills(): Selectable {
		return $this->fills;
	}
	
	public function setFills(ArrayCollection $fills) {
		$this->fills = $fills;
	}
	
	public function addFills() {
		$dao = DAO::getInstance();
		$fills = new ArrayCollection($dao->getByFilter("Persona"));
		$fills->removeElement($this);
		foreach ($fills as $fill) {
			if (!$this->fills->contains($fill)) {
				$this->fills->add($fill);
				$fill->addPares($this);
				return $fill;
			}
		}
		throw new \Exception("Col·lecció plena");
	}
	
	public function changeFills(Persona $old, Persona $new) {
		if ($this->fills->contains($old) && !$this->fills->contains($new)) {
			$this->removeFills($old);
			$this->fills->add($new);
			$new->pares->add($this);
		} else throw new \Exception("Fill existent");
	}
	
	public function removeFills(Persona $fill = null) {
		if ($this->fills->contains($fill)) {
			$this->fills->removeElement($fill);
			$fill->removePares($this);
		}
	}
	
	public function getPares(): Selectable {
		return $this->pares;
	}
	
	public function setPares(ArrayCollection $pares) {
		$this->pares = $pares;
	}

	public function addPares(Persona $pare = null) {
		if (!$this->pares->contains($pare)) {
			$this->pares->add($pare);
		}
	}
	
	public function removePares(Persona $pare = null) {
		if ($this->pares->contains($pare)) {
			$this->pares->removeElement($pare);
		}
	}
	
	public function getGermans(): Selectable {
	    $data = new ArrayCollection();
	    foreach ($this->pares as $pare) {
	        foreach ($pare->getFills() as $fill) {
	            if ($fill != $this && !$data->contains($fill)) $data->add($fill);
	        }
	    }
	    return $data;
	}
	
	public function getOficis(): Selectable {
		return $this->oficis;
	}
	
	public function setOficis(ArrayCollection $oficis) {
		$this->oficis = $oficis;
	}

	// afegeix un ofici a la llista. en cas de nul, en crea un de nou
	public function addOficis() {
		$dao = DAO::getInstance();
		$oficis = $dao->getByFilter("Ofici");
		foreach ($oficis as $ofici) {
			if (!$this->oficis->contains($ofici)) {
				$this->oficis->add($ofici);
				return $ofici;
			}
		}
		throw new \Exception("Col·lecció plena");
	}
	
	public function changeOficis(Ofici $old, Ofici $new) {
		if ($this->oficis->contains($old) && !$this->oficis->contains($new)) {
			$this->removeOficis($old);
			$this->oficis->add($new);
		} else throw new \Exception("Ofici existent");
	}
	
	public function removeOficis(Ofici $ofici = null) {
		if ($this->oficis->contains($ofici)) {
			$this->oficis->removeElement($ofici);
		}
	}
	
	public function getLlocMort(): ?Municipi {
		return $this->llocMort;
	}
	
	public function setLlocMort(Municipi $llocMort) {
		$this->llocMort = $llocMort;
	}
	
	public function getLlocNaixement(): ?Municipi {
	    return $this->llocNaixement;
	}
	
	public function setLlocNaixement(Municipi $llocNaixement) {
	    $this->llocNaixement = $llocNaixement;
	}
	
	public function getDataMort(): ?string {
		if ($this->dataMort != null) return $this->dataMort->format("Y-m-d");
		return null;
	}
	
	public function setDataMort($dataMort) {
		if (is_string($dataMort)) $dataMort = \DateTime::createFromFormat("Y-m-d", $dataMort);
		$this->dataMort = $dataMort;
	}
	
	public function getDataDefuncio(): ?string {
		if ($this->dataDefuncio != null) return $this->dataDefuncio->format("Y-m-d");
		return null;
	}
	
	public function setDataDefuncio($dataDefuncio) {
		if (is_string($dataDefuncio)) $dataDefuncio = \DateTime::createFromFormat("Y-m-d", $dataDefuncio);
		$this->dataDefuncio = $dataDefuncio;
	}

	public function getObservacions(): ?string {
		return $this->observacions;
	}
	
	public function setObservacions(?string $observacions) {
		$this->observacions = $observacions;
	}
	
	public function getHasSumari(): bool {
		return $this->hasSumari;
	}
	
	public function setHasSumari(bool $hasSumari) {
		$this->hasSumari= $hasSumari;
	}
	
	public function getSumari(): ?Sumari {
		return $this->sumari;
	}
	
	public function setSumari(Sumari $sumari) {
		$this->sumari = $sumari;
	}

	public function getCg(): bool {
		return $this->cg;
	}
	
	public function setCg(bool $cg) {
		$this->cg = $cg;
	}
	
	public function getFetsOctubre(): bool {
	  return $this->fetsOctubre;
	}
	
	public function setFetsOctubre(bool $fetsOctubre) {
	  $this->fetsOctubre = $fetsOctubre;
	}
	
	public function getCapsaFetsOctubre() {
	  return $this->capsaFetsOctubre;
	}
	
	public function getFontFetsOctubre(): Font {
	  return $this->fontFetsOctubre;
	}
	
	public function setFontFetsOctubre(Font $fontFetsOctubre) {
	  $this->fontFetsOctubre = $fontFetsOctubre;
	}
	
	public function setCapsaFetsOctubre($capsaFetsOctubre) {
	  $this->capsaFetsOctubre = $capsaFetsOctubre;
	}
	
	public function getPartits(): Selectable {
		return $this->partits;
	}
	
	public function setPartits(ArrayCollection $partits) {
		$this->partits = $partits;
	}
	
	public function addPartits() {
		$dao = DAO::getInstance();
		$partits = new ArrayCollection($dao->getByFilter("Partit"));
		$partits->removeElement($this);
		foreach ($partits as $partit) {
			if (!$this->partits->contains($partit)) {
				$this->partits->add($partit);
				return $partit;
			}
		}
		throw new \Exception("Col·lecció plena");
	}
	
	public function changePartits(Partit $old, Partit $new) {
		if ($this->partits->contains($old) && !$this->partits->contains($new)) {
			$this->removePartits($old);
			$this->partits->add($new);
		} else throw new \Exception("Afiliació existent");
	}
	
	public function removePartits(Partit $partit = null) {
		if ($this->partits->contains($partit)) {
			$this->partits->removeElement($partit);
		}
	}
	
	public function getSindicats(): Selectable {
		return $this->sindicats;
	}
	
	public function setSindicats(ArrayCollection $sindicats) {
		$this->sindicats = $sindicats;
	}
	
	public function addSindicats() {
		$dao = DAO::getInstance();
		$sindicats = new ArrayCollection($dao->getByFilter("Sindicat"));
		$sindicats->removeElement($this);
		foreach ($sindicats as $sindicat) {
			if (!$this->sindicats->contains($sindicat)) {
				$this->sindicats->add($sindicat);
				return $sindicat;
			}
		}
		throw new \Exception("Col·lecció plena");
	}
	
	public function changeSindicats(Sindicat $old, Sindicat $new) {
		if ($this->sindicats->contains($old) && !$this->sindicats->contains($new)) {
			$this->removeSindicats($old);
			$this->sindicats->add($new);
		} else throw new \Exception("Afiliació existent");
	}
	
	public function removeSindicats(Sindicat $sindicat = null) {
		if ($this->sindicats->contains($sindicat)) {
			$this->sindicats->removeElement($sindicat);
		}
	}
	
	public function getTipusSituacio(): Selectable {
	    return $this->tipusSituacio;
	}
	
	public function setTipusSituacio(ArrayCollection $tipusSituacio) {
	    $this->tipusSituacio = $tipusSituacio;
	}
	
	public function addTipusSituacio() {
	    $dao = DAO::getInstance();
	    $situacions = new ArrayCollection($dao->getByFilter("TipusSituacio"));
	    $situacions->removeElement($this);
	    foreach ($situacions as $situacio) {
	        if (!$this->tipusSituacio->contains($situacio)) {
	            $this->tipusSituacio->add($situacio);
	            return $situacio;
	        }
	    }
	    throw new \Exception("Col·lecció plena");
	}
	
	public function changeTipusSituacio(TipusSituacio $old, TipusSituacio $new) {
	    if ($this->tipusSituacio->contains($old) && !$this->tipusSituacio->contains($new)) {
	        $this->removeTipusSituacio($old);
	        $this->tipusSituacio->add($new);
	    } else throw new \Exception("Tipus existent");
	}
	
	public function removeTipusSituacio(TipusSituacio $tipusSituacio = null) {
	    if ($this->tipusSituacio->contains($tipusSituacio)) {
	        $this->tipusSituacio->removeElement($tipusSituacio);
	    }
	}
	
	public function getComites(): Selectable {
		return $this->comites;
	}
	
	public function setComites(ArrayCollection $comites) {
		$this->comites = $comites;
	}
	
	public function addComites() {
	    $instance = new MembreComite();
	    $this->comites->add($instance);
	    $instance->setPersona($this);
	    return $instance;
	}
	
	public function changeComites(MembreComite $old, MembreComite $new) {
	    if ($this->comites->contains($old) && !$this->comites->contains($new)) {
	        $this->removeComites($old);
	        $this->addComites($new);
	    }
	}
	
	public function removeComites(MembreComite $comite = null) {
	    if ($this->comites->contains($comite)) {
	        $this->comites->removeElement($comite);
	        $dao = DAO::getInstance();
	        $dao->remove($comite);
	    }
	}
	
	public function getAjuntaments(): Selectable {
		return $this->ajuntaments;
	}
	
	public function setAjuntaments(Ajuntament $ajuntament) {
		$this->ajuntaments = $ajuntament;
	}
	
	public function addAjuntaments() {
		$instance = new Ajuntament();
		$this->ajuntaments->add($instance);
		$instance->setPersona($this);
		return $instance;
	}
	
	public function removeAjuntaments(Ajuntament $ajuntament = null) {
		if ($this->ajuntaments->contains($ajuntament)) {
			$this->ajuntaments->removeElement($ajuntament);
			$dao = DAO::getInstance();
			$dao->remove($ajuntament);
		}
	}
	
	public function getExiliat(): bool {
		return $this->exiliat;
	}
	
	public function setExiliat(bool $exiliat) {
		$this->exiliat = $exiliat;
	}
	
	public function getDestiFinal(): ?Pais {
		return $this->destiFinal;
	}
	
	public function setDestiFinal(Pais $destiFinal) {
		$this->destiFinal = $destiFinal;
	}
	
	public function getLlocDestiFinal(): ?Municipi {
		return $this->llocDestiFinal;
	}
	
	public function setLlocDestiFinal(Municipi $llocDestiFinal) {
		$this->llocDestiFinal = $llocDestiFinal;
	}
	
	public function getEstatMilitar(): ?EstatMilitar {
		return $this->estatMilitar;
	}
	
	public function setEstatMilitar(EstatMilitar $estatMilitar) {
		$this->estatMilitar = $estatMilitar;
	}
	
	public function getDestiEstatMilitar(): ?DestiEstatMilitar {
		return $this->destiEstatMilitar;
	}
	
	public function setDestiEstatMilitar(DestiEstatMilitar $destiEstatMilitar) {
		$this->destiEstatMilitar = $destiEstatMilitar;
	}
	
	public function getDataEstatMilitar(): ?string {
		if ($this->dataEstatMilitar != null) return $this->dataEstatMilitar->format("Y-m-d");
		return null;
	}
	
	public function setDataEstatMilitar($dataEstatMilitar) {
		if (is_string($dataEstatMilitar)) $dataEstatMilitar = \DateTime::createFromFormat("Y-m-d", $dataEstatMilitar);
		$this->dataEstatMilitar = $dataEstatMilitar;
	}
	
	public function getTrp(): bool {
		return $this->trp;
	}
	
	public function setTrp(bool $trp) {
		$this->trp = $trp;
	}
	
	public function getDataTrp(): ?string {
		if ($this->dataTrp != null) return $this->dataTrp->format("Y-m-d");
		return null;
	}
	
	public function setDataTrp($dataTrp) {
		if (is_string($dataTrp)) $dataTrp = \DateTime::createFromFormat("Y-m-d", $dataTrp);
		$this->dataTrp = $dataTrp;
	}
	
	public function getFontTrp(): Font {
		return $this->fontTrp;
	}
	
	public function setFontTrp(Font $fontTrp) {
		$this->fontTrp = $fontTrp;
	}
	
	public function getCapsaTrp(): ?int {
		return $this->capsaTrp;
	}
	
	public function setCapsaTrp(?int $capsaTrp) {
		$this->capsaTrp = $capsaTrp;
	}
	
	public function getObservacionsSituacio(): ?string {
		return $this->observacionsSituacio;
	}
	
	public function setObservacionsSituacio(?string $observacionsSituacio) {
		$this->observacionsSituacio = $observacionsSituacio;
	}
	
	public function getPeticionsInforme(): Selectable {
		return $this->peticionsInforme;
	}
	
	public function setPeticionsInforme(ArrayCollection $peticionsInforme) {
		$this->peticionsInforme = $peticionsInforme;
	}
	
	public function addPeticionsInforme() {
		$instance = new PeticioInforme();
		$this->peticionsInforme->add($instance);
		return $instance;
	}
	
	public function removePeticionsInforme(PeticioInforme $instance = null) {
		if ($this->peticionsInforme->contains($instance)) {
			$this->peticionsInforme->removeElement($instance);
			$dao = DAO::getInstance();
			$dao->remove($instance->getInforme());
			$dao->remove($instance);
		}
	}
	
	public function getObservacionsInformes(): ?string {
		return $this->observacionsInformes;
	}
	
	public function setObservacionsInformes(?string $observacionsInformes) {
		$this->observacionsInformes = $observacionsInformes;
	}
	
	public function toArray():array {
		return [
		    "id" => $this->id,
		    "nom" => $this->nom,
		    "cognoms" => $this->cognoms,
		    "malnom" => $this->malnom,
		    "edat" => $this->edat,
		    "dataNaixement" => $this->getDataNaixement(),
		    "carrer" => $this->carrer->toArray(),
		    "numCarrer" => $this->numCarrer,
		    "llocMort" => $this->llocMort->toArray(),
		    "llocNaixement" => $this->llocNaixement->toArray(),
		    "dataMort" => $this->getDataMort(),
		    "dataDefuncio" => $this->getDataDefuncio(),
		    "observacions" => $this->observacions,
		    "tipusSituacio" => $this->tipusSituacio->toArray(),
		    "cg" => $this->cg,
		    "fetsOctubre" => $this->fetsOctubre,
		    "fontFetsOctubre" => $this->fontFetsOctubre->toArray(),
		    "exiliat" => $this->exiliat,
		    "destiFinal" => $this->destiFinal->toArray(),
		    "llocDestiFinal" => $this->llocDestiFinal->toArray(),
		    "estatMilitar" => $this->estatMilitar->toArray(),
		    "destiEstatMilitar" => $this->destiEstatMilitar->toArray(),
		    "dataEstatMilitar" => $this->getDataEstatMilitar(),
		    "trp" => $this->trp,
		    "dataTrp" => $this->getDataTrp(),
		    "fontTrp" => $this->fontTrp->toArray(),
		    "capsaTrp" => $this->capsaTrp,
		    "observacionsSituacio" => $this->observacionsSituacio,
		    "observacionsInformes" => $this->observacionsInformes,
		    "hasSumari" => $this->hasSumari,
		    "sumari" => $this->sumari->toArray(),
		];
	}
	
	public function getValues(): array {
		return ["id" => $this->id, "data" => ["id" => $this->id]];
	}
	
	public function getResum(): array {
	    $situacions = [];
	    foreach ($this->tipusSituacio as $situacio)
	        $situacions[] = $situacio->toArray();
	    return [
	        "id" => $this->getId(),
	        "nom" => $this->getNom(),
	        "cognoms" => $this->getCognoms(),
	        "tipusSituacio" => $situacions,
	    ];
	}
	
	public function __toString(): string {
	    return $this->id;
	}
}