<?php

namespace App\Models\Traits;


use App\Models\PaymentInterface;
use App\Packages\Payments\Config;
use App\Packages\Payments\PaymentType;

trait HasAmount
{
    use HasBalance;

    /**
     * 收入
     *
     * @param PaymentInterface $model
     * @param $amount
     * @param string $type
     * @throws \Throwable
     */
    public function income(PaymentInterface $model, $amount, $type = PaymentType::PAYMENT_TYPE_CHARGE)
    {
        \DB::transaction(function () use ($model, $amount, $type) {
            $this->incrementAmount($amount);

            // 添加收入日志
            $this->setBalanceModel($model)
                ->setOriginals('amount', $amount)
                ->setOriginals('type', $type)
                ->setOriginals('trade_type', 1)
                ->setOriginals('body', PaymentType::getTypeNameBy($type))
                ->logs();
        });
    }

    /**
     * 支出
     *
     * @param PaymentInterface $model
     * @param $amount
     * @param string|null $type
     *
     * @throws \Throwable
     */
    public function expenditure(PaymentInterface $model, $amount, $type = null)
    {
        \DB::transaction(function () use ($model, $amount, $type) {
            $this->decrementAmount($amount);

            // 添加支出日志
            $this->setBalanceModel($model)
                ->setOriginals('amount', $amount)
                ->setOriginals('type', $type ?? $model->type())
                ->setOriginals('trade_type', 2)
                ->setOriginals('gateway', Config::DEFAULT_CHANNEL)
                ->setOriginals('body', PaymentType::getTypeNameBy($type ?? $model->type()))
                ->logs();
        });
    }

}
