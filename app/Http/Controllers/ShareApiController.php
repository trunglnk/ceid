<?php

namespace App\Http\Controllers;

use App\KetQuaThanhTra;
use App\KetQuaThanhTraThuTucHanhChinh;
use App\Organization;
use App\QuyetDinhThanhTra;
use App\QuyetDinhXuPhat;
use Illuminate\Http\Request;

class ShareApiController extends Controller
{
    public function getQuyetDinhThanhLap(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $query = QuyetDinhThanhTra::select(
            'id',
            'co_quan_quyet_dinh',
            'hinh_thuc_thanh_tra',
            'co_quan_thuc_hien',
            'ten',
            'nguoi_tao',
            'nguoi_cap_nhat',
            'created_at',
            'updated_at',
            'so_quyet_dinh',
            'nam_ke_hoach',
            'ma_dinh_danh'
        )
            ->with([
                'attachments', 'hinhThucThanhTra:id,ten', 'coQuanQuyetDinh:ten,id,ma_dinh_danh',
                'nguoiCapNhat:id,name', 'nguoiTao:id,name', 'coQuanThucHien:id,ten,ma_dinh_danh'
            ]);
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function getKetQuaThanhTra(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $query = KetQuaThanhTra::with([
            'attachments', 'nguoiTao:id,name', 'nguoiCapNhat:id,name',
            'organization:id,chu_dau_tu_id','organization.chuDauTu:id,ten,ma_dinh_danh,so_giay_chung_nhan_dang_ky_kinh_doanh,ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh', 'quyetDinhThanhTra:id,ten,nam_ke_hoach,ma_dinh_danh,so_quyet_dinh,ngay_ra_quyet_dinh', 'ketQuaThanhTraChungs', 'ketQuaKhacPhucHauQuas', 'quyetDinhXuPhats', 'nhomHanhViViPhams'
        ]);
        $query->whereHas('organization', function($q){
            $q->whereNotNull('chu_dau_tu_id');
        });
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function quyetDinhXuPhatHanhChinh(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $query = QuyetDinhXuPhat::has('ketQuaThanhTra.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs')->with(['coQuanQuyetDinhXuPhat:id,ten,ma_dinh_danh','ketQuaThanhTra.ketQuaThanhTraChungs:id,ket_qua_thanh_tra_id,ten','ketQuaThanhTra.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc:id,ten,ma']);
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function getGiayPhepMoiTruong(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $query = KetQuaThanhTraThuTucHanhChinh::select('id', 'loai_thu_tuc_hanh_chinh_id', 'so_quyet_dinh_phe_duyet', 'thoi_gian_phe_duyet', 'co_quan_phe_duyet_id', 'so_ket_luan', 'ngay_ket_luan_thanh_tra', 'ghi_chu')->with(['coQuanToChuc:id,ten,ma_dinh_danh', 'loaiThuTuc:id,ma,ten']);
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
