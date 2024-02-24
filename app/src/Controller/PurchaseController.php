<?php

namespace App\Controller;

use App\Service\PriceCalculator as Calculator;
use App\Service\Purchase;
use App\Service\RequestValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController extends AbstractController
{
    private $requestValidator;
    private $purchase;

    public function __construct(Calculator $priceCalculator, RequestValidator $requestValidator, Purchase $purchase)
    {
        $this->requestValidator = $requestValidator;
        $this->purchase = $purchase;
    }
    public function purchase(Request $request): JsonResponse
    {
        $data = $request->toArray();
        if (!$this->requestValidator->isRequestValid($data)) {
            return $this->json($this->requestValidator->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->purchase->purchase($data);
            return $this->json(['result' => 'success']);
        } catch (Exception $e) {
            return $this->json(['errors' => [$e->getMessage()]], Response::HTTP_BAD_REQUEST);
        }
    }
}
