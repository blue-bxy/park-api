<?php


namespace App\Models;


interface PaymentInterface
{
    public function gateway();

    public function amount();

    public function type();

    public function tradeNo();

    public function orderNo();
}
