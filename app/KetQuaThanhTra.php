<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class KetQuaThanhTra extends Model
{
    protected $fillable = [
        'id',
        'quyet_dinh_thanh_tra_id',        
        'ngay_thanh_tra',
        'nguoi_tao',
        'nguoi_cap_nhat',
        'mo_ta',
        'noi_dung_chua_thuc_hien',
        'inactive',
        'so_quyet_dinh',
        'created_at',
        'updated_at',
        'organization_id',
        'ma_dinh_danh',
        'thoi_gian_dong_bo',
        'trang_thai_dong_bo'
    ];
    protected $dates=['ngay_thanh_tra','created_at','updated_at'];

    protected static function boot()
    {
       parent::boot();

       static::addGlobalScope(new \App\Scopes\ActiveScope);
    }

    public function setNgayThanhTraAttribute($value)
    {       
        if (!empty($value)) {           
            if ($value instanceof Carbon) {
                $this->attributes['ngay_thanh_tra'] = $value;
            } else if (is_numeric($value)) {
                $this->attributes['ngay_thanh_tra'] = Carbon::createFromTimestamp(($value - 25569) * 86400)->startOfMonth();
            } else {

                $this->attributes['ngay_thanh_tra'] = Carbon::createFromFormat(config('app.format_date'), $value);

            }
        } else {
            $this->attributes['ngay_thanh_tra'] = null;
        }
    }
    public function getNgayThanhTraAttribute()
    {
        if (!empty($this->attributes['ngay_thanh_tra'])) {
            return Carbon::parse($this->attributes['ngay_thanh_tra'])->format(config('app.format_date'));
        } else {
            return null;
        }
    }

    public function quyetDinhThanhTra(){
        return $this->belongsTo('App\QuyetDinhThanhTra','quyet_dinh_thanh_tra_id')->withDefault();
    }

    public function ketQuaThanhTraChungs()
    {
        return $this->hasMany('App\KetQuaThanhTraChung', 'ket_qua_thanh_tra_id')->orderBy('id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment', 'reference_id')->where('reference_type','ket_luan_thanh_tra');
    }

    public function nguoiTao()
    {
      return $this->belongsTo('App\User', 'nguoi_tao')->withDefault();
    }

    public function nguoiCapNhat()
    {
        return  $this->belongsTo('App\User', 'nguoi_cap_nhat')->withDefault();
    }

    public function organization()
    {
        return  $this->belongsTo('App\Organization', 'organization_id')->withDefault();
    }

    public function thongBaoQuyetDinhThanhTras()
    {
        return $this->hasMany('App\ThongBaoQuyetDinhThanhTra', 'ket_qua_thanh_tra_id');
    }

    public function ketQuaKhacPhucHauQuas()
    {
        return $this->hasMany('App\KetQuaKhacPhucHauQua', 'ket_qua_thanh_tra_id');
    }

    public function quyetDinhXuPhats()
    {
        return $this->hasMany('App\QuyetDinhXuPhat', 'ket_qua_thanh_tra_id');
    }

    public function nhomHanhViViPhams()
    {
        return $this->hasMany('App\NhomHanhViViPham', 'ket_qua_thanh_tra_id');
    }
}