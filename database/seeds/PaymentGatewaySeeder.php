<?php

use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    protected $array = [
        'ali_app' => [
            'gateway' => 'ali_app',
            'gateway_name' => '支付宝',
            'desc' => '数亿用户都在用，安全可托付',
            'icon' => 'https://cdn.taopaitang.com/res/payMethod/20190411a94cc62b-fb09-3d6e-c15c-dfd16f461bd4-W300H300',
            'sort' => 2
        ],

        'balance' => [
            'gateway' => 'balance',
            'gateway_name' => '钱包支付',
            'desc' => '方便快捷',
            'icon' => 'https://cdn.taopaitang.com/res/payMethod/20190411d657f2a7-c8f5-cf95-9478-278477106524-W300H300',
            'sort' => 1
        ],

        'wx_app' => [
            'gateway' => 'wx_app',
            'gateway_name' => '微信',
            'desc' => '使用微信支付，简单方便',
            'icon' => 'https://cdn.taopaitang.com/res/payMethod/20190411bdf0ab11-8ef8-87c9-d00f-a210d8e5b7f8-W300H300',
            'sort' => 0
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->array as $item) {
            $gateway = new \App\Models\PaymentGateway($item);
            $gateway->save();
        }
    }
}
