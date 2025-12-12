<?php

namespace App\Http\Controllers;

use App\ChiTietPhatTangThem;
use App\ChiTietViPhamXaThai;
use App\DanhMucThongSoVuotChuan;
use App\KetQuaThanhTra;
use App\NhomHanhViViPham;
use App\Traits\GetDataCache;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DanhMucThongSoVuotChuanController extends Controller
{

    public function index(Request $request)
    {
        $type=$request->get('type');
        $trangThai = $request->get('trang_thai', null);
        $page = $request->get('page');
        $per_pager = $request->get('perPage');
        $search = $request->get('search', null);
        $query=DanhMucThongSoVuotChuan::with(['phanLoai']);
        if(!empty($type)){
            $query->where('type',$type);
        }
        if($trangThai){
           $query->where('trang_thai_dong_bo', $trangThai); 
        }
        if($search){
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%");
        }
        $query->orderBy('ma_loai');
        if(!empty($page) && !empty($per_pager)){
            $data =  $query->paginate($per_pager, ['*'], 'page', $page);
            return $data;
        }
        else{
            return response()->json([
                'message' => __('message.success'),
                'result' => $query->get(),
            ], 200, []);
        }
    }

    public function store(Request $request)
    {
        $info=$request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'ky_hieu_hoa_hoc'=>'max:255',
            // 'type'=>'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item=DanhMucThongSoVuotChuan::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function update(Request $request, $id)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'ky_hieu_hoa_hoc'=>'max:255',
            // 'type'=>'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

				$item = DanhMucThongSoVuotChuan::find($id);
				$item->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function destroy($id)
    {
        $danh_muc = DanhMucThongSoVuotChuan::find($id);
        try{
            $chiTietPhatTangThem = ChiTietPhatTangThem::where('thong_so_id', $id)->first();
            $phatXaThaiId = $chiTietPhatTangThem ? $chiTietPhatTangThem->chi_tiet_vi_pham_xa_thai_id : null;
            $queryChiTietViPhamXaThai = ChiTietViPhamXaThai::where('thong_so_id', $id);
            $chiTietViPhamXaThai = $phatXaThaiId ? $queryChiTietViPhamXaThai->orWhere('id', $phatXaThaiId)->first() : $queryChiTietViPhamXaThai->first() ;
            $nhomHanhViId = $chiTietViPhamXaThai ? $chiTietViPhamXaThai->nhom_hanh_vi_vi_pham_id : null;
            $nhomHanhVi = $nhomHanhViId  ? NhomHanhViViPham::where('id', $nhomHanhViId)->first() : null;
            $ketQuaThanhTraId = $nhomHanhVi  ? $nhomHanhVi->ket_qua_thanh_tra_id : null;
            $ketQuaThanhTra = $ketQuaThanhTraId ? KetQuaThanhTra::where('id', $ketQuaThanhTraId)->first() : null;
            if($ketQuaThanhTra ){
                return response()->json([
                    'message' => "Kết quả thanh tra, số quyết định $ketQuaThanhTra->so_quyet_dinh  đã có thông số này",
                ], 500, []);
            }
            $danh_muc->delete();

            return response()->json([
                'message' => __('message.success'),
                'result' => [],
            ], 200, []);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Thông số đã được sử dụng',
            ], 500, []);
        }
    }

}
