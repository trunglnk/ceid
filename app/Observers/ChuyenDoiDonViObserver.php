<?php

namespace App\Observers;

use App\ChuyenDoiDonVi;
use App\CoSoSanXuat;
use App\Traits\GetDataCache;
use Illuminate\Support\Facades\DB;

class ChuyenDoiDonViObserver
{
    use GetDataCache;

    function updated(ChuyenDoiDonVi $model){
        $original = $model->getOriginal();
        if($model->don_vi_goc != $original['don_vi_goc'] && $model->don_vi_goc ==true) {
            DB::update("update chuyen_doi_don_vis set ty_le = ty_le/".$original['ty_le']."where loai='".$model->loai."'");
            switch ($model->loai) {
                case 'dien_tich':
                    DB::update('update co_so_san_xuats set dien_tich = dien_tich/'.$original['ty_le']);
                    break;
                case 'luu_luong_nuoc_thai':
                    DB::update('update co_so_san_xuats set luong_nuoc_su_dung = luong_nuoc_su_dung/'.$original['ty_le']);
                    break;
                case 'khoi_luong_phat_sinh_chat_thai':
                    DB::update('update ket_qua_thanh_tra_chat_thai_ran_sinh_hoats set khoi_luong_phat_sinh = khoi_luong_phat_sinh/'.$original['ty_le']);
                    DB::update('update ket_qua_thanh_tra_chat_thai_ran_cntts set khoi_luong_phat_sinh = khoi_luong_phat_sinh/'.$original['ty_le']);
                    DB::update('update ket_qua_thanh_tra_chat_thai_nguy_hais set khoi_luong_phat_sinh_thuc_te = khoi_luong_phat_sinh_thuc_te/'.$original['ty_le'].',khoi_luong_phat_sinh_theo_so_dang_ki = khoi_luong_phat_sinh_theo_so_dang_ki/'.$original['ty_le']);
                break;
                default:
                    break;
            }
        }
    }    

    public function created(ChuyenDoiDonVi $model){
        $this->forgetByName(ChuyenDoiDonVi::class);        
    }
}