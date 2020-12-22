<?php

namespace App\Jobs;

use App\Models\Messages\MessageCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $result;
    private $action;
    private $code;
    private $mobile;

    /**
     * Create a new job instance.
     *
     * @param $mobile
     * @param $code
     * @param $action
     * @param $result
     */
    public function __construct($mobile, $code, $action, $result)
    {
        $this->mobile = $mobile;

        $this->code = $code;

        $this->action = $action;

        $this->result = json_decode($result, true);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        collect($this->result)->filter(function ($result) {
            return isset($result['result']);
        })->each(function ($result) {
            $data = [
                'phone' => $this->mobile,
                'sid' => $this->getSid($result)
            ];

            $value = [
                'code' => $this->code,
                'ip' => request()->ip(),
                'action' => $this->action,
                'send_time' => now()
            ];

            MessageCode::updateOrCreate($data, $value);
        });
    }

    /**
     * @param $response
     * @return mixed
     */
    protected function getSid($response)
    {
        switch ($response['gateway']) {
            case 'aliyun':
                $sid = data_get($response['result'], 'BizId');
                break;
            case 'qcloud':
            default:
                $sid = data_get($response['result'], 'sid');
                break;
        }

        return $sid;
    }
}
