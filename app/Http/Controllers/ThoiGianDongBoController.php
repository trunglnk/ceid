<?php

namespace App\Http\Controllers;

use App\ThoiGianDongBo;
use Illuminate\Http\Request;

class ThoiGianDongBoController extends Controller
{
    public function getThoiGianDongBo($type){
        $data=ThoiGianDongBo::where('danh_muc_dong_bo', $type)->first();
        return $data;
    }
}
