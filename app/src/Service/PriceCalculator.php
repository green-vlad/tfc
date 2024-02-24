<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Exception\CouponNotFound;
use App\Exception\ProductNotFound;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;

const COUNTRY_TAXES = [
    'DE' => 19,
    'IT' => 22,
    'FR' => 2,
    'GR' => 24
];

class PriceCalculator {

    private $couponRepository;
    private $productRepository;

    public function __construct(CouponRepository $couponRepository, ProductRepository $productRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->productRepository = $productRepository;
    }
    public function getPrice(array $req): float
    {
        $product = $this->getProduct($req['product']);
        if (!empty($req['couponCode'])) {
            $coupon = $this->getCouponByCode($req['couponCode']);
            if ($coupon->getCouponType() == 1) {
                $priceWithDiscount = $product->getPrice() * (1 - $coupon->getDiscount() / 100);
            } else {
                $priceWithDiscount = $product->getPrice() - $coupon->getDiscount();
            }
        } else {
            $priceWithDiscount = $product->getPrice();
        }
        return round($priceWithDiscount * (1 + COUNTRY_TAXES[substr($req['taxNumber'], 0, 2)] / 100), 2);
    }

    private function getCouponByCode($couponCode): Coupon
    {
        $couponType = substr($couponCode, 0, 1) == 'P' ? 1 : 0;
        $coupon = $this->couponRepository->findOneBy([
            'coupon_type' => $couponType,
            'discount' => (int) substr($couponCode, 1)
        ]);
        if (!$coupon) {
            throw new CouponNotFound("Coupon not found");
        }
        return $coupon;
    }

    private function getProduct($prodId): Product
    {
        $product = $this->productRepository->findOneBy([
            'id' => $prodId
        ]);
        if (!$product) {
            throw new ProductNotFound("Product not found");
        }
        return $product;
    }
}