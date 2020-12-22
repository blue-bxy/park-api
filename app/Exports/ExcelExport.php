<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;

/**
 * 报表控制器
 * @package App\Exports
 */
class ExcelExport
{
    use Exportable;

    protected $writerType = Excel::XLSX;

    protected $disk = 'excel';
}
