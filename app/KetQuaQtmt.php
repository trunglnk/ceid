<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaQtmt extends Model
{

     protected $table = 'ket_qua_qtmt';
    public $timestamps = true;

    protected $fillable = [
        'ma_dinh_danh',
        'ma_tram',
        'ten_tram',
        'loai_hinh_qtmt',
        'loai_so_lieu_qtmt',
        'nam_quan_trac',
        'thang_quan_trac',
        'trang_thai',
        'nguon_tham_chieu',
        'modified_at',
        'thong_so_ma',
        'thong_so_ten',
        'quy_chuan_ma',
        'quy_chuan_ten',
        'don_vi_ma',
        'don_vi_ten',
        'gia_tri_quan_trac',
        'gia_tri_gioi_han_max',
    ];
}
