<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhamViQTMT extends Model
{
    protected $table = 'pham_vi_qtmt';

    protected $fillable = [
        'diem_tram_qtmt_id',
        'thong_so_ma','thong_so_ten',
        'quy_chuan_ma','quy_chuan_ten'
    ];

    public function diemTram()
    {
        return $this->belongsTo(DiemTramQTMT::class, 'diem_tram_qtmt_id');
    }
}
