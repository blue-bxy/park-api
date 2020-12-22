<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RegionResource;
use App\Models\Regions\City;
use App\Models\Regions\Country;
use App\Models\Regions\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends BaseController
{

    //省
    public function provinces(){
        $data=Province::all();

        return $this->responseData(RegionResource::collection($data));
    }

    //市
    public function cities(Request $request){
        $province_id=$request->input('province_id');
        $cities=City::where('province_id',$province_id)->get();
//        dd($cities);
        return $this->responseData(RegionResource::collection($cities));
    }


    //区
    public function countries(Request $request){
        $city_id=$request->input('city_id');
        $countries=Country::where('city_id',$city_id)->get();
//        dd($countries);
        return $this->responseData(RegionResource::collection($countries));
    }
}
