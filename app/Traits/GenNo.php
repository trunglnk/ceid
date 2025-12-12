<?php

namespace App\Traits;

use App\NhanSu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait GenNo
{    
    /**
     * Gen code nhan su with len 5 charactor
     */
    public function genCode($MODEL, $name, $length)
    {
        $data = $MODEL::query()->orderBy('id','desc')->first();
        if(empty($data)){
            return $name.str_pad((string)(1), $length, "0", STR_PAD_LEFT);
        }
        else{
            return $name.str_pad((string)($MODEL::query()->max('id')+1),$length , "0", STR_PAD_LEFT);
        }
    } 
}
