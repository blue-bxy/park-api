<?php

namespace App\Jobs;

use App\Models\Users\RoaGreenInterface;
use App\Models\Users\UserComment;
use App\Models\Users\UserComplaint;
use App\Packages\Green\GreenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GreenImageSyncScanRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    /**
     * Create a new job instance.
     *
     * @param UserComment|UserComplaint|RoaGreenInterface $model
     *
     * @return void
     */
    public function __construct(RoaGreenInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @param GreenService $green
     *
     * @return void
     */
    public function handle(GreenService $green)
    {
        $tasks = [
            'scenes' => [
                'porn', // 涉黄
                'terrorism', // 政治
                'ad', // 广告
                'live' // 图片不良场景识别
            ],
        ];

        $images = $this->model->getImages();

        $item = [];
        foreach ($images as $image) {
            $item[] = [
                'dataId' => uniqid(),
                'url' => $image
            ];
        }

        $tasks['tasks'] = $item;

        try {
            $response = $green->imageSyncScan()->jsonBody($tasks)->request();

            if ($response->isSuccess()) {
                $data = $response->toArray();

                $this->model->imageSyncScanCallback($data);
            }
        } catch (\Exception $exception) {
            logger($exception);
        }
    }
}
