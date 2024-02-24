<?php

namespace App\Service;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RequestValidator
{
    private const TAX_NUMBER_REGEX = "/(^DE[0-9]{9}$)|(^IT[0-9]{11}$)|(^GR[0-9]{9}$)|(^FR[A-Z]{2}[0-9]{9}$)/";
    private const COUPON_REGEX = "/^(P|D)[0-9]+$/";

    private $validator;
    private $errors;
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    public function isRequestValid(array $data): bool
    {
        $constraint = new Assert\Collection([
            'product'          => new Assert\Positive(),
            'taxNumber'        => new Assert\Regex(self::TAX_NUMBER_REGEX),
            'couponCode'       => new Assert\Optional(new Assert\Regex(self::COUPON_REGEX)),
            'paymentProcessor' => new Assert\Optional(new Assert\Choice(Purchase::PAYMENT_PROCESSORS))
        ]);
        $violations = $this->validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            $this->errors['errors'] = [];
            foreach ($violations as $violation) {
                $this->errors['errors'][] = $violation->getPropertyPath() . " " . $violation->getMessage();
            }
            return false;
        }
        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}