<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
             UserSeeder::class,
             AdminSeeder::class,
             RegionSeeder::class,
             PaymentGatewaySeeder::class,

             ParkSeeder::class,
             ParkAreaSeeder::class,
             BrandSeeder::class,
             ParkDeviceSeeder::class,
             ParkRateSeeder::class,
             CarRentSeeder::class,
             UserOrderSeeder::class,
             CarAptSeeder::class,
             CarStopSeeder::class,
             CarAptOrderSeeder::class,
             UserPaymentLogSeeder::class,
             WithDrawalsSeeder::class,
             UserRefundSeeder::class,
             UserParkingSpaceSeeder::class,
             RentalBillSeeder::class
        ]);
    }
}
