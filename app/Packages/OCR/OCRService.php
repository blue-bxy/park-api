<?php


namespace App\Packages\OCR;


use App\Packages\Config;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class OCRService
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    //通用文字识别/OCR文字识别
    public function general($file)
    {
        $data = [
            'image' => base64_encode(file_get_contents($this->getResource($file))),
            // 'image' => 'url',
            'configure' => [
                "min_size"                     => 16,     #图片中文字的最小高度，单位像素
                "output_prob"                  => true,   #是否输出文字框的概率
                "output_keypoints"             => false,  #是否输出文字框角点
                "skip_detection"               => false,  #是否跳过文字检测步骤直接进行文字识别
                "without_predicting_direction" => false   #是否关闭文字行方向预测
            ]
        ];

        return $this->request('https://tysbgpu.market.alicloudapi.com/api/predict/ocr_general', $data);
    }

    //行驶证识别
    public function license($file, $side = 'face')
    {
        $data = [
            'image' => base64_encode(file_get_contents($this->getResource($file))),
            // 'image' => 'url',
            'configure' => [
                "side" => $side // back
            ]
        ];

        return $this->request('https://dm-53.data.aliyun.com/rest/160601/ocr/ocr_vehicle.json', $data);
    }

    /**
     * getResource
     *
     * @param string|UploadedFile $file
     * @return false|string
     */
    protected function getResource($file)
    {
        if (is_string($file)) {
            return $file;
        }

        if ($file instanceof UploadedFile) {
            return $file->getRealPath();
        }

        return $file;
    }

    public function request(string $url, array $data)
    {
        $response = \Http::withToken($this->config->get('ocr_client_code'), 'APPCODE')
            ->post($url, $data);

        return json_decode($response->body(), true);
    }
}
