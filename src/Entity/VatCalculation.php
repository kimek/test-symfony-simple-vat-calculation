<?php

namespace App\Entity;

use App\Repository\VatCalculationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VatCalculationRepository::class)]
class VatCalculation
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
	private ?string $originalValue = null;

	#[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
	private ?string $vatAmount = null;

	#[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
	private ?string $valueWithVat = null;

	#[ORM\Column]
	private ?\DateTime $createdAt = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getOriginalValue(): ?string
	{
		return $this->originalValue;
	}

	public function setOriginalValue(string $originalValue): static
	{
		$this->originalValue = $originalValue;

		return $this;
	}

	public function getVatAmount(): ?string
	{
		return $this->vatAmount;
	}

	public function setVatAmount(string $vatAmount): static
	{
		$this->vatAmount = $vatAmount;

		return $this;
	}

	public function getValueWithVat(): ?string
	{
		return $this->valueWithVat;
	}

	public function setValueWithVat(string $valueWithVat): static
	{
		$this->valueWithVat = $valueWithVat;

		return $this;
	}

	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTime $createdAt): static
	{
		$this->createdAt = $createdAt;

		return $this;
	}
}
