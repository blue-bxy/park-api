<?php


namespace App\Services;


use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginatorService extends LengthAwarePaginator
{
    public function toArray()
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->items->toArray(),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
