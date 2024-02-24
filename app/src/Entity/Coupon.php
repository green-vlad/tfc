<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouponRepository::class)
 */
class Coupon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $coupon_type;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $discount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouponType(): ?int
    {
        return $this->coupon_type;
    }

    public function setCouponType(int $coupon_type): self
    {
        $this->coupon_type = $coupon_type;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }
}
