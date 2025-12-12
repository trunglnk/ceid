<?php

namespace App\Http\Controllers;

use App\ChiTietCongSuat;
use App\ChuDauTu;
use App\Organization;
use App\QuanHuyen;
use App\TinhThanh;
use App\CoQuanToChuc;
use App\CoSoSanXuat;
use App\KetQuaThanhTra;
use App\PhuongXa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChuDauTuController extends Controller
{
    public function viewIndex()
    {
        return view('chudautu.chudautu');
    }
    public function showFromAdd()
    {
        $noicaps = CoQuanToChuc::select('id', 'ten')->get();
        $coquans = CoQuanToChuc::select('id', 'ten')->get();
        $tinhs = TinhThanh::select('id', 'ten')->get();
        $quans = QuanHuyen::select('id', 'tinh_thanh_id', 'ten')->get();
        return view('chudautu.addchudautu', ['tinhs' => json_encode($tinhs), 'coquans' => json_encode($coquans), 'noicaps' => json_encode($noicaps), 'quans' => json_encode($quans)]);
    }

    public function showFromEdit($id)
    {
        $noicaps = CoQuanToChuc::select('id', 'ten')->get();
        $tinhs = TinhThanh::select('id', 'ten')->get();
        $quans = QuanHuyen::select('id', 'tinh_thanh_id', 'ten')->get();
        $xas = PhuongXa::select('id', 'quan_huyen_id', 'ten')->get();
        $coquans = CoQuanToChuc::select('id', 'ten')->get();
        $data = ChuDauTu::where('id', $id)->first();
        $coSos = Organization::select('id', 'ten')->where('chu_dau_tu_id', $id)->get();
        $data->co_sos = $coSos;
        return view('chudautu.editchudautu', ['tinhs' => json_encode($tinhs),  'quans' => json_encode($quans), 'xas' => json_encode($xas), 'coquans' => json_encode($coquans), 'noicaps' => json_encode($noicaps), 'data' => json_encode($data)]);
    }
    public function addChuDauTu(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        try {
            DB::beginTransaction();
            $chuDauTu = ChuDauTu::create($info);
            Organization::create([
                'ten' => $info['ten'],
                'dia_chi_lien_he' => $info['dia_chi'],
                'chu_dau_tu_id' =>  $chuDauTu->id,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);
            DB::commit();
            return response(['message' => 'Thành công'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function getChuDauTu(Request $request)
    {
        $query = ChuDauTu::with(['coQuanCapGiayKinhDoanh'])->select('trang_thai_dong_bo', 'ma_dinh_danh', 'id', 'ten', 'dia_chi', 'so_giay_chung_nhan_dang_ky_kinh_doanh', 'ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $trangThaiDongBo = $request->get('dong_bo');
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('dia_chi', 'ilike', "%{$search}%")
                    ->orWhere('ma_so_qlctnh', 'ilike', "%{$search}%");
            });
        }
        if ($trangThaiDongBo) {
            $query->where('trang_thai_dong_bo', $trangThaiDongBo);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $item['so_co_so'] = Organization::where('chu_dau_tu_id', $item->id)->count();
        }
        return $data;
    }

    public function updateChuDauTu($id, Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        try {
            DB::beginTransaction();
            Organization::where('chu_dau_tu_id', $id)->update([
                'chu_dau_tu_id' => null
            ]);
            Organization::whereIn('id', $info['co_sos'])->update([
                'chu_dau_tu_id' => $id
            ]);
            unset($info['co_sos']);
            ChuDauTu::where('id', $id)->update($info);
            DB::commit();
            return response(['message' => 'Thành công'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function deleteChuDauTu($id)
    {
        try {
            DB::beginTransaction();
            $organization =  Organization::where('chu_dau_tu_id', $id)->pluck('id')->toArray();
            foreach($organization as $organization_id){
               $this->deleteOrganization($organization_id);
            }
            ChuDauTu::where('id', $id)->delete();
            DB::commit();
            return response(['message' => 'Thành công'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function deleteOrganization($id){
        DB::beginTransaction();
        try {
            $organization = Organization::find($id);
            $cososanxuat = CoSoSanXuat::where('organization_id', $id)->get();
            ChiTietCongSuat::query()->whereIn('co_so_san_xuat_id', $cososanxuat->pluck('id'))->delete();
            $ketquathanhtra = KetQuaThanhTra::where('organization_id', $id)->get();
            $ketquathanhtrachungs = \App\KetQuaThanhTraChung::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->whereIn('co_so_san_xuat_id', $cososanxuat->pluck('id'))->get();

            \App\KetQuaThanhTraChungLoaiHinhHoatDong::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
            \App\KetQuaThanhTraThuTucHanhChinh::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();

            \App\ThongBaoQuyetDinhThanhTra::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->delete();
            $quyetdinhxuphat = \App\QuyetDinhXuPhat::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->get();
            \App\KetQuaThanhTraNuocThai::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
            \App\KetQuaThanhTraKhiThai::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
            \App\KetQuaThanhTraChatThaiRanSinhHoat::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
            \App\KetQuaThanhTraChatThaiRanCNTT::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
            \App\KetQuaThanhTraChatThaiNguyHai::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
            $xuPhatChinh = \App\XuPhatChinh::whereIn('quyet_dinh_xu_phat_id', $quyetdinhxuphat->pluck('id'))->get();
            $xuPhatBoXung = \App\XuPhatBoSung::whereIn('quyet_dinh_xu_phat_id', $quyetdinhxuphat->pluck('id'))->get();
            \App\HanhViViPham::whereIn('xu_phat_chinh_id', $xuPhatChinh->pluck('id'))->delete();
            \App\ChiTietXuPhatBoSung::whereIn('xu_phat_bo_sung_id', $xuPhatBoXung->pluck('id'))->delete();
            \App\QuyetDinhXuPhat::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->delete();
            \App\KetQuaThanhTraChung::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->delete();
            KetQuaThanhTra::where('organization_id', $id)->delete();
            CoSoSanXuat::where('organization_id', $id)->delete();
            $organization->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Thất bại',
                'result' => [],
            ], 500, []);
        }
        return response()->json([
            'message' => 'Thành công',
            'result' => [],
        ], 200, []);
    }
}
