<?php


namespace App\Models;


interface RentalRateInterface
{
    public function getWorkday();

    public function getStartPeriod();

    public function getEndPeriod();

    public function getDepositUnit();

    public function getDepositTimeUnit();

    public function getTimeUnit();

    public function getPriceUnit();

    public function getRentalUserType();
}
