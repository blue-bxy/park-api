<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Users\Version;
use Illuminate\Http\Request;

class VersionController extends BaseController
{
    public function version(Request $request)
    {
        $platform = $request->header('platform') ?? $request->input('platform');

        if (!$platform) {
            return $this->responseFailed('platform 不能为空', 40022);
        }

        $query = Version::query();

        $query->where('platform', $platform);

        $version = $query->latest()
            ->first();

        return $this->responseData([
            'version' => $version->version_no,
            'is_force' => $version->is_force,
            'download_url' => $version->resource_url,
            'desc' => $version->update_description
        ]);
    }
}
