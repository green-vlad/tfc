<?php

namespace App\Tests\unit;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\PriceCalculator;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    public function testGetPrice(): void
    {
        $request = [
            'product' => 1,
            'taxNumber' => 'FRRR123456719',
            'couponCode' => 'D15',
        ];

        $product = new Product();
        $product->setPrice(100);
        $mockProductRepository = $this->createMock(ProductRepository::class);
        $mockProductRepository->method('findOneBy')->willReturn($product);

        $coupon = new Coupon();
        $coupon->setCouponType(0);
        $coupon->setDiscount(15);
        $mockCouponRepository = $this->createMock(CouponRepository::class);
        $mockCouponRepository->method('findOneBy')->willReturn($coupon);

        $priceCalculator = new PriceCalculator($mockCouponRepository, $mockProductRepository);
        $this->assertEquals(102, $priceCalculator->getPrice($request));

        $request = [
            'product' => 1,
            'taxNumber' => 'GR123456719',
            'couponCode' => 'P15',
        ];

        $coupon = new Coupon();
        $coupon->setCouponType(1);
        $coupon->setDiscount(15);
        $mockCouponRepository = $this->createMock(CouponRepository::class);
        $mockCouponRepository->method('findOneBy')->willReturn($coupon);

        $priceCalculator = new PriceCalculator($mockCouponRepository, $mockProductRepository);
        $this->assertEquals(105.4, $priceCalculator->getPrice($request));

        $request = [
            'product' => 1,
            'taxNumber' => 'DE123456719',
        ];

        $priceCalculator = new PriceCalculator($mockCouponRepository, $mockProductRepository);
        $this->assertEquals(119, $priceCalculator->getPrice($request));
    }
}