<?php
namespace Represaliats\Service\Entities;

/**
 * Classe que modela un usuari
 *
 * @Entity @Table(name="users")
 */

class User implements IEntity
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=30, nullable=true) **/
	private $nom;
	/** @Column(type="string", length=50, nullable=true) **/
	private $cognoms;
	/** @Column(type="string", length=20, nullable=true) **/
	private $username;
	/** @Column(type="string", length=40, nullable=true) **/
	private $password;
	
	public function __construct(string $nom, string $cognoms, string $username) {
		$this->nom = $nom;
		$this->cognoms = $cognoms;
		$this->username = $username;
		$this->password = sha1("1234");
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
	
	public function getUsername(): ?string {
		return $this->username;
	}
	
	public function setUsername(string $username) {
		$this->username = $username;
	}
	
	public function getPassword(): ?string {
		return $this->password;
	}
	
	public function setPassword(string $password) {
		$this->password = sha1($password);
	}
	
	public function toArray(): array {
		return [
				"id" => $this->id,
				"nom" => $this->nom,
				"cognoms" => $this->cognoms,
				"username" => $this->username,
		];
	}
	
	public function __toString(): string {
	    return json_encode($this);
	}
}