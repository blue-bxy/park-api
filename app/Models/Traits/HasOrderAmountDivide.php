<?php

namespace App\Models\Traits;

use App\Models\Bills\OrderAmountDivide;
use App\Models\Bills\ParkWallet;
use App\Models\Bills\ParkWalletBalance;
use App\Models\Dmanger\CarApt;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasOrderAmountDivide
{
    /**
     * divide
     *
     * @return MorphOne
     */
    public function divide()
    {
        return $this->morphOne(OrderAmountDivide::class, 'model');
    }

    public function addDivideRecord()
    {
        if ($this->trueAmount() <= 0) {
            return;
        }
        info('预约费分账：'. $this->trueAmount());

        tap($this->divide()->firstOrNew(['park_id' => $this->park_id, 'user_id' => $this->user_id]), function ($divide) {
            $divide->fee_type = $this instanceof CarApt ? 0 : 1; // 0:预约费，1:停车费
            $divide->total_amount = $this->totalAmount(); // 订单总金额

            $divide->fee = $this->trueAmount(); // 实际付款金额

            $rate = $this->getRates();

            $platform_rate = $rate['platform'] ?? 0;
            $park_rate = $rate['park'] ?? 0;
            $owner_rate = $rate['owner'] ?? 0;

            $divide->platform_rate = $platform_rate;
            $divide->park_rate = $park_rate;
            $divide->owner_rate = $owner_rate;

            $divide->park_fee = intval($divide->fee * $park_rate /100);
            $divide->owner_fee = intval($divide->fee * $owner_rate /100);

            // $divide->platform_fee = intval($divide->fee * $platform_rate /100);
            $divide->platform_fee = max($divide->fee - $divide->park_fee - $divide->owner_fee, 0);


            $divide->save();

            // 给业主发放收益
            $this->sendOwnerAmount($divide);

            // 给车场发放收益
            $this->sendParkAmount($divide);
        });
    }

    public function sendParkAmount($divide)
    {
        $fee = $divide->park_fee;

        if ($fee <= 0) {
            return;
        }

        /** @var ParkWallet $wallet */
        $wallet = tap(ParkWallet::query()->firstOrNew(['park_id' => $divide->park_id]), function ($wallet) use ($fee) {
            $wallet->amount += $fee;

            if ($this instanceof CarApt) {
                $wallet->reserve_fee += $fee; // 预约费
            } else {
                $wallet->parking_fee += $fee;// 停车费
            }

            $wallet->save();
        });

        /** @var ParkWalletBalance $record */
        $record = $wallet->records()->newModelInstance([
            'park_id' => $wallet->park_id,
            'amount' => $fee,
            'type' => $this instanceof CarApt ? ParkWalletBalance::RESERVE_TYPE : ParkWalletBalance::PARKING_TYPE,
            'trade_type' => 1, // 收入
            'balance' => $wallet->amount,
            // 'order_no'
        ]);

        $record->order()->associate($this);

        $record->save();
    }
}
