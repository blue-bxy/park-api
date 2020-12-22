<?php

namespace App\Models\Traits;

use App\Jobs\GreenImageSyncScanRequest;
use App\Jobs\GreenTextScanRequest;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasGreen
{
    protected static function bootHasGreen()
    {
        static::created(function ($model) {
            // 同步处理文本审核
            dispatch(new GreenTextScanRequest($model));
            // 同步处理图片审核
            dispatch(new GreenImageSyncScanRequest($model));
        });
    }

    /**
     * review
     *
     * @return MorphMany
     */
    public function review()
    {
        return $this->morphMany(Review::class, 'model');
    }

    public function getContent(): string
    {
        return $this->{$this->getContentColumn()};
    }

    public function getContentColumn(): string
    {
        return 'content';
    }

    public function getImageColumn(): string
    {
        return 'imgurl';
    }

    public function getImages(): array
    {
        return $this->covers;
    }

    public function callback(array $array)
    {
        $data = collect($array['data'])->first();

        if (empty($data)) return;

        $result = $data['results'][0];

        $this->review()->create([
            'type' => 'text',
            'value' => $data['content'],
            'label' => $result['label'],
            'suggestion' => $result['suggestion'],
            'response' => $data
        ]);
    }

    public function imageSyncScanCallback(array $array)
    {
        foreach ($array['data'] as $data) {
            foreach ($data['results'] as $result) {
                $this->review()->create([
                    'type' => 'img',
                    'value' => $data['url'],
                    'label' => $result['label'],
                    'suggestion' => $result['suggestion'],
                    'response' => $result
                ]);
            }
        }
    }
}
