<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Service\PriceCalculator as Calculator;

class PriceCalculator extends AbstractController
{
    private const TAX_NUMBER_REGEX = "/(^DE[0-9]{9}$)|(^IT[0-9]{11}$)|(^GR[0-9]{9}$)|(^FR[A-Z]{2}[0-9]{9}$)/";
    private const COUPON_REGEX = "/^(P|D)[0-9]+$/";
    private $validator;
    private $priceCalculator;

    public function __construct(ValidatorInterface $validator, Calculator $priceCalculator)
    {
        $this->validator = $validator;
        $this->priceCalculator = $priceCalculator;
    }
    public function calculate(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $groups = new Assert\GroupSequence(['Default', 'custom']);
        $constraint = new Assert\Collection([
            'product'   => new Assert\Positive(),
            'taxNumber' => new Assert\Regex(self::TAX_NUMBER_REGEX),
            'couponCode'    => new Assert\Regex(self::COUPON_REGEX),
        ]);
        $violations = $this->validator->validate($data, $constraint, $groups);
        if ($violations->count() > 0) {
            return new JsonResponse(
                ['error' => 'Tax number has wrong format'],
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }

        try {
            $price = $this->priceCalculator->getPrice($data);
            return new JsonResponse(['price' => $price]);
        } catch (Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }
    }
}