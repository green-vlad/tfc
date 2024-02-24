<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $iphone = new Product();
        $iphone->setName('Iphone');
        $iphone->setPrice(100);
        $manager->persist($iphone);

        $hp = new Product();
        $hp->setName('Наушники');
        $hp->setPrice(20);
        $manager->persist($hp);

        $case = new Product();
        $case->setName('Чехол');
        $case->setPrice(10);
        $manager->persist($case);

        $coupon = new Coupon();
        $coupon->setCouponType(0);
        $coupon->setDiscount("10.00");
        $manager->persist($coupon);

        $coupon = new Coupon();
        $coupon->setCouponType(1);
        $coupon->setDiscount("10.00");
        $manager->persist($coupon);

        $coupon = new Coupon();
        $coupon->setCouponType(1);
        $coupon->setDiscount("6.00");
        $manager->persist($coupon);

        $manager->flush();
    }
}
