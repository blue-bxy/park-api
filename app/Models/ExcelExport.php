<?php

namespace App\Models;


class ExcelExport extends EloquentModel
{
    protected $table='export_excels';
    public static $logName = "excelExport";
    protected $fillable = [
        'excel_name', 'excel_type', 'excel_src', 'load_type',
        'excel_size','create_excel_time', 'out_excel_time'
    ];

}
