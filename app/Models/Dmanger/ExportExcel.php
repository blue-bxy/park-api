<?php

namespace App\Models\Dmanger;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
class ExportExcel extends EloquentModel
{
    // 软删除
    use SoftDeletes;

    // 添加时白名单
    protected $fillable=['excel_name','excel_type','create_excel_time','stop_create_time','load_type'];

    // 搜索
    public function scopeSearch(Builder $query,Request $request)
    {
        // 判断是否有excel_name
        if ($excelName = $request->input('excel_name')) {
            $query->where('excel_name','like', "%{$excelName}%");
        }
        // 判断是否有excel_type
        if ($excelType= $request->input('excel_type')) {
            $query->where('excel_type', $excelType);
        }
        // 判断是否有load_type_id
        if ($load_type_id= $request->input('load_type_id')) {
            $query->where('load_type_id', $load_type_id);
        }
        // 判断是否有时间段
        if ($createExcelTime = $request->input('create_excel_time')) {
            $query->where('create_excel_time','>=',$createExcelTime);
        }
        if ($stopCreateTime = $request->input('stop_create_time')) {
            $query->where('create_excel_time','<=', $stopCreateTime);
        }
        return $query;
    }
}
