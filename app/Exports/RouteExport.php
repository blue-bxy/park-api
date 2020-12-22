<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RouteExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    protected $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->routes;
    }


    public function headings(): array
    {
        return [
            '请求方式',
            'uri',
            'name'
        ];
    }

    public function map($row): array
    {
        return [
            $row['method'],
            $row['uri'],
            $row['name'],
        ];
    }
}
