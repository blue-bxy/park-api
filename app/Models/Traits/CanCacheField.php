<?php

namespace App\Models\Traits;

/**
 * Trait CanCacheField
 * @package App\Models\Traits
 */
trait CanCacheField
{
    /**
     * @param $value
     */
    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = $this->asJson(
            array_merge($this->fromJson($this->attributes['cache'] ?? '{}'), $value)
        );
    }

    /**
     * @return array
     */
    public function getCacheAttribute()
    {
        return array_merge(self::CACHE_FIELDS, $this->fromJson($this->attributes['cache'] ?? '{}'));
    }

    public function incrementAmount($amount)
    {
        $this->incrementOrDecrementByColumn('balance', $amount, 'increment');

        $this->updateCacheAttributeValue('balance', 'total', $amount);
    }

    public function decrementAmount($amount)
    {
        $this->incrementOrDecrementByColumn('balance', $amount, 'decrement');

        $this->updateCacheAttributeValue('balance', 'used', $amount);
    }

    public function incrementRentalAmount($amount)
    {
        $this->incrementOrDecrementByColumn('rental_amount', $amount, 'increment');

        $this->updateCacheAttributeValue('rental_amount', 'total', $amount);
    }

    public function decrementRentalAmount($amount)
    {
        $this->incrementOrDecrementByColumn('rental_amount', $amount, 'decrement');

        $this->updateCacheAttributeValue('rental_amount', 'used', $amount);
    }

    protected function incrementOrDecrementByColumn($column, $amount, $method)
    {
        $this->{$column} = $this->{$column} + ($method === 'increment' ? $amount : $amount * -1);
    }

    public function updateCacheAttributeValue($column, $key, $num)
    {
        $value = $this->getCacheField("{$column}.{$key}");
        $value += $num;

        $this->cache = [
            $column => array_replace($this->cache[$column], [
                $key => $value,
            ]),
        ];

        $this->save();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getCacheField($key)
    {
        return data_get($this->cache, $key, 0);
    }
}
