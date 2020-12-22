<?php

use Illuminate\Database\Seeder;

class RentalBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bills = factory(\App\Models\Users\ParkingSpaceRentalBill::class, 20)->create();

        foreach ($bills as $bill) {
            if ($bill->type == 0) {
                $bill->user->incrementRentalAmount($bill->amount);
            } else {
                $bill->user->decrementRentalAmount($bill->amount);
            }
        }
    }
}
