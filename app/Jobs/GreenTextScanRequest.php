<?php

namespace App\Jobs;

use AlibabaCloud\Client\Exception\ServerException;
use App\Models\Users\RoaGreenInterface;
use App\Models\Users\UserComment;
use App\Models\Users\UserComplaint;
use App\Packages\Green\GreenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GreenTextScanRequest implements ShouldQueue
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
     * @return void
     */
    public function handle(GreenService $green)
    {
        $tasks = [
            'scenes' => ['antispam'],
            'tasks' => [
                [
                    'dataId' => uniqid(),
                    'content' => $this->model->getContent()
                ]
            ],
        ];

        try {
            $response = $green->textScan()->jsonBody($tasks)->request();

            if ($response->isSuccess()) {
                $data = $response->toArray();

                $this->model->callback($data);
            }
        } catch (ServerException $e) {
            logger($e->getErrorMessage());
        }
    }
}
