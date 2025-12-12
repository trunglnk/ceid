<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'id',
        'ten',
        'dia_chi_lien_he',
        'created_by',
        'updated_by',
        'chu_dau_tu_id'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function coSoSanXuats()
    {
        return $this->hasMany('App\CoSoSanXuat', 'organization_id')->orderBy('id');
    }

    public function ketQuaThanhTras()
    {
        return $this->hasMany('App\KetQuaThanhTra', 'organization_id')->orderBy('id');
    }
    public function chuDauTu(){
        return $this->belongsTo('App\ChuDauTu', 'chu_dau_tu_id', 'id');
    }
}
