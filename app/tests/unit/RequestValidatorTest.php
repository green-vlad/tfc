<?php

namespace App\Tests\unit;

use App\Service\RequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class RequestValidatorTest extends TestCase
{
    public function testIsRequestValid()
    {
        $mockRequestCorrect['product'] = 1;
        $mockRequestCorrect['taxNumber'] = 'GR123456789';
        $mockRequestCorrect['couponCode'] = 'P5';
        $mockRequestCorrect['paymentProcessor'] = 'paypal';

        $mockRequestWrongCoupon['product']    = 1;
        $mockRequestWrongCoupon['taxNumber']  = 'GR123456789';
        $mockRequestWrongCoupon['couponCode'] = 'X5';
        $mockRequestWrongCoupon['paymentProcessor'] = 'paypal';

        $mockRequestWrongTaxNumber['product']    = 1;
        $mockRequestWrongTaxNumber['taxNumber']  = 'GR1234567890';
        $mockRequestWrongTaxNumber['couponCode'] = 'P5';
        $mockRequestWrongTaxNumber['paymentProcessor'] = 'paypal';

        $mockRequestWrongPaymentProcessor['product']    = 1;
        $mockRequestWrongPaymentProcessor['taxNumber']  = 'GR1234567890';
        $mockRequestWrongPaymentProcessor['couponCode'] = 'P5';
        $mockRequestWrongPaymentProcessor['paymentProcessor'] = 'payoneer';

        $requestValidator = new RequestValidator(Validation::createValidator());

        $this->assertTrue($requestValidator->isRequestValid($mockRequestCorrect));
        $this->assertFalse($requestValidator->isRequestValid($mockRequestWrongCoupon));
        $this->assertFalse($requestValidator->isRequestValid($mockRequestWrongTaxNumber));
        $this->assertFalse($requestValidator->isRequestValid($mockRequestWrongPaymentProcessor));
    }
}