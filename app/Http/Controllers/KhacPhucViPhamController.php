<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhacPhucViPhamController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    function cacBienPhapKhacPhucViPham(){
        return response()->json([
            'message' => __('message.success'),
            'result' => \App\CacBienPhapKhacPhucHauQua::query()->get(),
        ], 200, []);

    }
}
