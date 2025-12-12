<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DongBoMtqgLog extends Model
{
    protected $table = 'dong_bo_mtqg_logs';
    protected $fillable = ['user_id', 'hanh_dong', 'mo_ta', 'yeu_cau', 'ket_qua'];

    protected $casts = [
        'yeu_cau' => 'array',
        'ket_qua' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $desc = null, $request = null, $response = null){
        self::create([
            'user_id' => Auth::id(),
            'hanh_dong' => $action,
            'mo_ta' => $desc,
            'yeu_cau' => $request,
            'ket_qua' => $response,
        ]);
    }
}
