<?php

namespace App\Service;

use App\Entity\VatCalculation;
use Doctrine\ORM\EntityManagerInterface;

class VatCalculatorService
{
	const VAT_TYPE = ['ex', 'inc'];
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * Performs VAT calculations.
	 *
	 * @param float $value
	 * @param float $rate
	 * @return array
	 */
	public function calculateVat(float $value, float $rate, string $vatType): array
	{
		$vatAmount = $value * ($rate / 100);

		if ($vatType === 'ex') {
			$valueWithVat = $value + $vatAmount;
		} elseif ($vatType === 'inc') {
			$valueWithVat = $value - $vatAmount;
		} else {
			throw new ThiefException('Attempted to greet a thief!');
		}

		return [
			'value' => $value,
			'vatAmount' => $vatAmount,
			'valueWithVat' => $valueWithVat
		];
	}

	/**
	 * Saves a VAT calculation to the database.
	 *
	 * @param array $calculation
	 * @return void
	 */
	public function saveCalculation(array $calculation): void
	{
		$vatCalculation = new VatCalculation();
		$vatCalculation->setOriginalValue($calculation['value']);
		$vatCalculation->setVatAmount($calculation['vatAmount']);
		$vatCalculation->setValueWithVat($calculation['valueWithVat']);
		$vatCalculation->setCreatedAt(new \DateTime());

		$this->entityManager->persist($vatCalculation);
		$this->entityManager->flush();
	}
}
