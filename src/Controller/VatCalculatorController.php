<?php

namespace App\Controller;

use App\Repository\VatCalculationRepository;
use App\Service\VatCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VatCalculatorController extends AbstractController
{
	private VatCalculatorService $vatCalculatorService;

	public function __construct(VatCalculatorService $vatCalculatorService)
	{
		$this->vatCalculatorService = $vatCalculatorService;
	}

	#[Route('/vat-calculate', name: 'vat_calculate')]
	public function calculate(Request $request, SessionInterface $session): Response
	{

		if (!in_array($request->get('vat'), $this->vatCalculatorService::VAT_TYPE)) {
			return $this->render('vat_calculator/error.html.twig', [
				'message' => 'VAT value can be only ' .
					implode(' or ', $this->vatCalculatorService::VAT_TYPE) . ', please correct it'
			]);
		}

		if (empty($request->get('value')) || empty($request->get('rate'))) {
			return $this->render('vat_calculator/error.html.twig', [
				'message' => 'Rate and value cannot be empty'
			]);
		}

		// Float casting fails to 0
		$value = (float)$request->get('value');
		$rate = (float)$request->get('rate');
		$vatType = $request->get('vat');

		$calculation = $this->vatCalculatorService->calculateVat($value, $rate, $vatType);
		$this->vatCalculatorService->saveCalculation($calculation);

		$history = $session->get('history', []);

		$history[] = [
			'originalValue' => $calculation['value'],
			'valueWithVat' => $calculation['valueWithVat'],
			'vatAmount' => $calculation['vatAmount'],
			'createdAt' => (new \DateTime())->format('Y-m-d H:i:s'),
		];

		$session->set('history', $history);

		return $this->render('vat_calculator/result.html.twig', [
			'value' => $calculation['value'],
			'rate' => $rate,
			'vatAmount' => $calculation['vatAmount'],
			'valueWithVat' => $calculation['valueWithVat'],
			'history' => $history,
		]);
	}

	#[Route('/vat-history/all', name: 'vat_history_all')]
	public function showAllHistory(VatCalculationRepository $repository): Response
	{
		return $this->render('vat_calculator/history.html.twig', [
			'history' => $repository->findAll(),
		]);
	}

	#[Route('/vat-history/clear', name: 'vat_history_clear')]
	public function clearHistory(SessionInterface $session): Response
	{
		$session->set('history', []);

		return $this->redirectToRoute('vat_calculate');
	}

	#[Route('/vat-history/export', name: 'vat_history_export')]
	public function exportHistory(SessionInterface $session): StreamedResponse
	{
		$history = $session->get('history', []);

		$response = new StreamedResponse(function () use ($history) {
			$handle = fopen('php://output', 'w+');
			fputcsv($handle, ['Original Value', 'Value with VAT', 'VAT Amount', 'Created At']);

			foreach ($history as $row) {
				fputcsv($handle, $row);
			}

			fclose($handle);
		});

		$response->headers->set('Content-Type', 'text/csv');
		$response->headers->set('Content-Disposition', 'attachment; filename="vat_history.csv"');

		return $response;
	}

	#[Route('/', name: 'home_page')]
	public function homepage(SessionInterface $session): Response
	{
		return $this->redirectToRoute('vat_calculate');
	}
}
