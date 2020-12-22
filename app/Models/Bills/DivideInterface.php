<?php


namespace App\Models\Bills;


interface DivideInterface
{
    public function totalAmount();

    public function trueAmount();

    public function getRates();

    public function sendOwnerAmount($divide);

    public function sendParkAmount($divide);
}
