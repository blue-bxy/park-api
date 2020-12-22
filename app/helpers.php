<?php

use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use App\Models\Parks\ParkSetting;
use App\Models\Users\Version;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

if (!function_exists('uuid')) {
    /**
     * uuid
     *
     * @return string
     */
    function uuid()
    {
        return Str::orderedUuid()->toString();
    }
}

if (!function_exists('get_order_no')) {
    /**
     * 订单号生成器 适用于多种途径
     *
     * @return string
     */
    function get_order_no()
    {
        return date('ymdHi') . gen_uuid(6);
    }
}

if (!function_exists('gen_uuid')) {
    /**
     * 随机数
     *
     * @param int $len
     * @return bool|string
     */
    function gen_uuid($len = 6)
    {
        $hex = md5("lt" . uniqid("", true));

        $pack = pack('H*', $hex);
        $tmp = base64_encode($pack);

        $uid = preg_replace("#[^a-z1-9]#", "", $tmp);

        $len = max(4, min(128, $len));

        while (strlen($uid) < $len) {
            $uid .= gen_uuid(22);
        }

        return substr($uid, 0, $len);
    }
}

if (!function_exists('get_excel_file_path')) {
    /**
     * get_excel_file_path
     *
     * @param null $fileName
     * @param string $writerType
     * @return string
     */
    function get_excel_file_path($fileName = null, $writerType = Excel::XLSX)
    {
        $date = now()->toDateString();

        $fileName = is_null($fileName) ? Str::orderedUuid() : $fileName;

        $fileName .= time();

        return sprintf("%s/%s.%s", $date, $fileName, $writerType);
    }
}

if (!function_exists('get_app_version')) {
    /**
     * get_app_version
     *
     * @param string $platform
     * @return array
     */
    function get_app_version($platform)
    {
        return Cache::rememberForever('version', function () use ($platform) {
            return Version::lastVersion($platform);
        });
    }
}

if (!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk = 'id', $pid = 'parent_id', $child = 'children', $root = 0)
    {
        if ($list instanceof Collection) {
            $list = $list->toArray();
        }

        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

if (! function_exists('time_format')) {
    /**
     * 时间格式化
     *
     * @param Carbon $time
     * @param $format
     * @return string
     */
    function time_format(Carbon $time, $format)
    {
        return $time->format($format);
    }
}

if (!function_exists('is_url')) {
    /**
     * 验证URL有效性
     *
     * @param $url
     * @return bool
     */
    function is_url($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

if (!function_exists('get_img_format_name')) {
    /**
     * 获取图片文件名称
     *
     * @param $filename
     * @param $width
     * @param $height
     * @return string
     * @example 20191112865cbe84-6ab8-452e-addb-58f3a4a3e380-W640H380
     */
    function get_img_format_name($filename, $width, $height)
    {
        return sprintf(date('Ymd').$filename.'-W%sH%s', $width, $height);
    }
}

if (!function_exists('get_img_filename')) {
    function get_img_filename($filename)
    {
        return sprintf(date('Ymd'). $filename);
    }
}

if (!function_exists('filename')) {
    function filename(UploadedFile $file, $is_cdn = false)
    {
        try {
            $image = Image::make($file);

            $filename = get_img_format_name(uuid(), $image->width(), $image->height());
        } catch (\Exception $exception) {
            //
            $filename = get_img_filename(uuid());
        }

        if (!$is_cdn) {
            $filename .= '.'. $file->extension();
        }

        return $filename;
    }
}

if (!function_exists('decimal_number')) {
    /**
     * 格式化金额，保留2位
     *
     * @param float $amount
     *
     * @return string
     */
    function decimal_number($amount)
    {
        return sprintf('%01.2f', round($amount, 2));
    }
}

if (!function_exists('paid_notify')) {
    /**
     * @param $message
     * @param string $type
     */
    function paid_notify($message, $type = 'notify')
    {
        logs('paidnotify')->info("----------{$type} start----------");
        logs('paidnotify')->debug($message);
        logs('paidnotify')->info("----------{$type} end----------");
    }
}

if (!function_exists('toXml')) {
    /**
     * 输出xml字符
     * @param $values
     * @return string
     */
    function toXml($values)
    {
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}

if (!function_exists('get_sms_code')) {
    /**
     * 获取短信验证码
     *
     * @param int $length
     * @return string
     */
    function get_sms_code($length = 4)
    {
        return str_pad(mt_rand(1, 9999), $length, 0, STR_PAD_LEFT);
    }
}


if (!function_exists('format_car_num')) {
    /**
     * 格式化 车牌号
     *
     * 替换空格、特殊符号
     *
     * @param string $car_num
     * @return string
     */
    function format_car_num(string $car_num)
    {
        return strtoupper(str_replace([' ', '·'], '', $car_num));
    }
}

if (!function_exists('get_park_setting')) {
    /**
     * get_park_setting
     *
     * @param $id
     * @param string|null $key
     * @return ParkSetting|Collection|mixed
     */
    function get_park_setting($id, ?string $key = null)
    {
        $setting = Cache::rememberForever("parking_setting:{$id}", function () use ($id) {
            return ParkSetting::query()->where('park_id', $id)->first();
        });

        if (!is_null($key)) {
            return collect($setting)->get($key);
        }

        return $setting;
    }
}
