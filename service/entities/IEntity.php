<?php
namespace Represaliats\Service\Entities;

/**
 * Interface for an entity
 */

interface IEntity
{
	public function toArray(): array;
	public function __toString(): string;
}