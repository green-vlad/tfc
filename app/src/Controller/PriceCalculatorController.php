<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PriceCalculator as Calculator;
use App\Service\RequestValidator;

class PriceCalculatorController extends AbstractController
{
    private $requestValidator;
    private $priceCalculator;

    public function __construct(Calculator $priceCalculator, RequestValidator $requestValidator)
    {
        $this->requestValidator = $requestValidator;
        $this->priceCalculator = $priceCalculator;
    }
    public function calculate(Request $request): JsonResponse
    {
        $data = $request->toArray();
        if (!$this->requestValidator->isRequestValid($data)) {
            return $this->json(['errors' => $this->requestValidator->getErrors()], Response::HTTP_BAD_REQUEST);
        }
        try {
            $price = $this->priceCalculator->getPrice($data);
            return $this->json(['price' => $price]);
        } catch (Exception $e) {
            return $this->json(['errors' => [$e->getMessage()]], Response::HTTP_BAD_REQUEST);
        }
    }
}