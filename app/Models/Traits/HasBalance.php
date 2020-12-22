<?php

namespace App\Models\Traits;

use App\Models\Financial\Withdrawal;
use App\Models\Payment;
use App\Models\PaymentInterface;
use App\Models\Users\UserBalance;

trait HasBalance
{
    protected $balance_originals = [];

    protected $balance_model = null;

    protected $balance_values = [];

    public function balances()
    {
        return $this->hasMany(UserBalance::class);
    }

    private function getBalanceOriginals()
    {
        return $this->balance_originals;
    }

    protected function setBalanceOriginals(array $value)
    {
        $this->balance_originals = array_replace($this->getBalanceOriginals(), $value);

        return $this;
    }

    protected function setBalanceValues(array $value)
    {
        $this->balance_values = $value;

        return $this;
    }

    private function getBalanceValues()
    {
        return $this->balance_values;
    }

    protected function setBalanceModel(PaymentInterface $payment)
    {
        $this->balance_model = $payment instanceof Payment ? $payment->payable : $payment;

        if ($this->balance_model instanceof Withdrawal) {
            $this->setValues('status', 0);
        }

        foreach (['type', 'amount', 'gateway', 'order_no', 'trade_no'] as $item) {
            $method = camel_case($item);

            $this->setOriginals($item, $payment->$method());
        }

        return $this;
    }

    protected function setOriginals($key, $value)
    {
        $this->balance_originals[$key] = $value;

        return $this;
    }

    protected function setValues($key, $value)
    {
        $this->balance_values[$key] = $value;

        return $this;
    }

    public function logs()
    {
        return tap($this->balances()->make($this->getBalanceOriginals()), function ($instance) {
            $instance->order()->associate($this->balance_model);

            $instance->balance = $this->balance;

            $instance->fill($this->getBalanceValues());

            $instance->save();
        });
    }
}
