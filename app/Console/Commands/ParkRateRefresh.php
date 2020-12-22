<?php

namespace App\Console\Commands;

use App\Models\Parks\ParkRate;
use App\Services\ParkRateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParkRateRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '费率整点切换';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hour = now()->hour;
        $isWeekday = now()->isWeekday() ? ParkRate::IS_WORKDAY_TRUE : ParkRate::IS_WORKDAY_FALSE;
        $isWorkday = array($isWeekday, ParkRate::IS_WORKDAY_ALL);
        $service = new ParkRateService();
        $this->stop($service, $hour, $isWorkday);
        $this->start($service, $hour, $isWorkday);
    }

    /**
     * 停用费率
     * @param ParkRateService $service
     * @param int $hour
     * @param array $isWorkday
     */
    private function stop(ParkRateService $service, int $hour, array $isWorkday) {
        if ($hour == 0) {
            $hour = 24;
        }
        $rates = ParkRate::query()
            ->where('end_period', '=', $hour)
            ->whereIn('is_workday', $isWorkday)
            ->where('is_active', '=', ParkRate::IS_ACTIVE_ON)
            ->orderBy('type', 'desc')
            ->get();
        foreach ($rates as $rate) {
            try {
                DB::transaction(function () use ($service, $rate) {
                    $service->unpublish($rate);
                });
            } catch (\Exception $e) {
                Log::error('费率'.$rate->id.'停用失败（'.$e->getMessage().')');
            }
        }
    }

    /**
     * 启用费率
     * @param ParkRateService $service
     * @param int $hour
     * @param array $isWorkday
     */
    private function start(ParkRateService $service, int $hour, array $isWorkday) {
        $rates = ParkRate::query()
            ->where('start_period', '=', $hour)
            ->whereIn('is_workday', $isWorkday)
            ->where('is_active', '=', ParkRate::IS_ACTIVE_ON)
            ->orderBy('type', 'desc')
            ->get();
        $collections = $rates->groupBy('park_id');
        foreach ($collections as $collection) {
            foreach ($collection as $rate) {
                try {
                    DB::transaction(function () use ($service, $rate) {
                        $service->publish($rate);
                    });
                } catch (\Exception $e) {
                    Log::error('费率'.$rate->id.'启用失败（'.$e->getMessage().')');
                }
            }
        }
    }
}
