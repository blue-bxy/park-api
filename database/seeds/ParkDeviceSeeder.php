<?php

use Illuminate\Database\Seeder;

class ParkDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = \App\Models\Parks\ParkArea::query()->limit(10)->get();
        foreach ($areas as $area) {
            $spaces = \App\Models\Parks\ParkSpace::query()
                ->where('park_area_id', '=', $area->id)
                ->get();
            $area->cameras()->createMany(
                factory(\App\Models\Parks\ParkCamera::class, 4)
                    ->make(['park_id' => $area->park_id])->toArray()
            );
            $area->bluetooths()->createMany(
                factory(\App\Models\Parks\ParkBluetooth::class, 4)
                    ->make(['park_id' => $area->park_id])->toArray()
            );
            $area->locks()->createMany(
                factory(\App\Models\Parks\ParkSpaceLock::class, 10)
                    ->make(['park_id' => $area->park_id])->toArray()
            );
            $this->deviceSpacePivot($area->cameras, $spaces, 5);
            $this->deviceSpacePivot($area->bluetooths, $spaces, 5);
            $this->deviceSpacePivot($area->locks, $spaces, 1);
        }
    }

    /**
     * 新增设备-车位中间表数据
     * @param $devices
     * @param $spaces
     * @param $quantity
     * @return array
     */
    protected function deviceSpacePivot($devices, $spaces, $quantity) {
        $data = [];
        $j = 0;
        foreach ($devices as $device) {
            for ($i = 0; $i < $quantity; $i++) {
                $data[] = [
                    'park_space_id' => $spaces[$j++]->id,
                    'device_type' => get_class($device),
                    'device_id' => $device->id
                ];
            }
        }
        DB::table('park_space_has_devices')->insert($data);
        return $data;
    }
}
