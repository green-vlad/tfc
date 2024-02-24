<?php

namespace App\Service;

use App\Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class Purchase
{
    const PAYMENT_PROCESSORS = [
        'paypal',
        'stripe',
    ];
    private $priceCalculator;

    public function __construct(PriceCalculator $priceCalculator)
    {
        $this->priceCalculator = $priceCalculator;
    }

    public function purchase(array $req): void
    {
        $amount = $this->priceCalculator->getPrice($req);
        if ($req['paymentProcessor'] == 'paypal') {
            $processor = new PaypalPaymentProcessor();
            $processor->pay($amount * 100);
        } else {
            $processor = new StripePaymentProcessor();
            if (!$processor->processPayment($amount)) {
                throw new Exception("Transaction failed. The amount is too low.");
            }
        }
    }
}