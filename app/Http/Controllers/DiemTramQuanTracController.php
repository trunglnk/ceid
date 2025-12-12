<?php

namespace App\Http\Controllers;

use App\ChiTietCongSuat;
use App\ChuDauTu;
use App\Organization;
use App\QuanHuyen;
use App\TinhThanh;
use App\CoQuanToChuc;
use App\CoSoSanXuat;
use App\DiemTramQTMT;
use App\KetQuaThanhTra;
use App\PhamViQTMT;
use App\PhuongXa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiemTramQuanTracController extends Controller
{
    public function viewIndex()
    {
        return view('diemtramquantrac.diemtramquantrac');
    }
    public function showFromAdd()
    {
        $noicaps = CoQuanToChuc::select('id', 'ten')->get();
        $coquans = CoQuanToChuc::select('id', 'ten')->get();
        $tinhs = TinhThanh::select('id', 'ten', 'ma')->get();
        $quans = QuanHuyen::select('id', 'tinh_thanh_id', 'ten', 'ma')->get();
        $xas = PhuongXa::select('id', 'quan_huyen_id', 'ten', 'ma')->get();
        return view('diemtramquantrac.adddiemtramquantrac', ['tinhs' => json_encode($tinhs), 'coquans' => json_encode($coquans), 'noicaps' => json_encode($noicaps), 'quans' => json_encode($quans), 'xas' => json_encode($xas)]);
    }

    public function showFromEdit($id)
    {
        $tinhs = TinhThanh::select('id', 'ten', 'ma')->get();
        $quans = QuanHuyen::select('id', 'tinh_thanh_id', 'ten', 'ma')->get();
        $xas = PhuongXa::select('id', 'quan_huyen_id', 'ten', 'ma')->get();
        $data = DiemTramQTMT::with(['phamVi' => function ($q) {
            $q->select('id', 'diem_tram_qtmt_id', 'thong_so_ma', 'thong_so_ten', 'quy_chuan_ma', 'quy_chuan_ten');
        }])->findOrFail($id);
        return view('diemtramquantrac.editdiemtramquantrac', ['tinhs' => json_encode($tinhs),  'quans' => json_encode($quans), 'data' => json_encode($data), 'xas' => json_encode($xas)]);
    }
    public function addDiemTramQuanTrac(Request $request)
    {
        $validated = $request->validate([
            'ma_dinh_danh'   => 'required|string|unique:diem_tram_qtmt,ma_dinh_danh',
            'ten_goi'        => 'required|string',
            'dia_chi_chi_tiet' => 'nullable|string',
            'phuong_xa_ma'   => 'nullable|string',
            'phuong_xa_ten'  => 'nullable|string',
            'quan_huyen_ma'  => 'nullable|string',
            'quan_huyen_ten' => 'nullable|string',
            'tinh_thanh_ma'  => 'nullable|string',
            'tinh_thanh_ten' => 'nullable|string',
            'kinh_do'        => 'nullable|numeric',
            'vi_do'          => 'nullable|numeric',
            'kenh_song_ma'   => 'nullable|string',
            'kenh_song_ten'  => 'nullable|string',
            'luu_vuc_song_ma' => 'nullable|string',
            'luu_vuc_song_ten' => 'nullable|string',
            'loai_diem_qtmt_ma' => 'nullable|string',
            'loai_diem_qtmt_ten' => 'nullable|string',
            'trang_thai_ma'  => 'nullable|string',
            'trang_thai_ten' => 'nullable|string',
            'pham_vi'        => 'nullable|array',  // danh sách thông số môi trường
            'pham_vi.*.thong_so_ma'  => 'required_with:pham_vi|string',
            'pham_vi.*.thong_so_ten' => 'required_with:pham_vi|string',
            'pham_vi.*.quy_chuan_ma' => 'nullable|string',
            'pham_vi.*.quy_chuan_ten' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // tạo điểm trạm
            $diem = DiemTramQTMT::create([
                'ma_dinh_danh'   => $validated['ma_dinh_danh'],
                'ten_goi'        => $validated['ten_goi'],
                'dia_chi_chi_tiet' => $validated['dia_chi_chi_tiet'] ?? null,
                'phuong_xa_ma'   => $validated['phuong_xa_ma'] ?? null,
                'phuong_xa_ten'  => $validated['phuong_xa_ten'] ?? null,
                'quan_huyen_ma'  => $validated['quan_huyen_ma'] ?? null,
                'quan_huyen_ten' => $validated['quan_huyen_ten'] ?? null,
                'tinh_thanh_ma'  => $validated['tinh_thanh_ma'] ?? null,
                'tinh_thanh_ten' => $validated['tinh_thanh_ten'] ?? null,
                'kinh_do'        => $validated['kinh_do'] ?? null,
                'vi_do'          => $validated['vi_do'] ?? null,
                'kenh_song_ma'   => $validated['kenh_song_ma'] ?? null,
                'kenh_song_ten'  => $validated['kenh_song_ten'] ?? null,
                'luu_vuc_song_ma' => $validated['luu_vuc_song_ma'] ?? null,
                'luu_vuc_song_ten' => $validated['luu_vuc_song_ten'] ?? null,
                'loai_diem_qtmt_ma' => $validated['loai_diem_qtmt_ma'] ?? null,
                'loai_diem_qtmt_ten' => $validated['loai_diem_qtmt_ten'] ?? null,
                'trang_thai_ma'  => $validated['trang_thai_ma'] ?? '02',
                'trang_thai_ten' => $validated['trang_thai_ten'] ?? 'Nháp',
                'trang_thai_dong_bo' => 'chua_dong_bo',
            ]);

            // thêm thông số môi trường nếu có
            if (!empty($validated['pham_vi'])) {
                foreach ($validated['pham_vi'] as $pv) {
                    $diem->phamVi()->create([
                        'thong_so_ma'  => $pv['thong_so_ma'],
                        'thong_so_ten' => $pv['thong_so_ten'],
                        'quy_chuan_ma' => $pv['quy_chuan_ma'] ?? null,
                        'quy_chuan_ten' => $pv['quy_chuan_ten'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Thêm mới thành công', 'data' => $diem->load('phamVi')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDiemTramQuanTrac(Request $request)
    {
        $query = DiemTramQTMT::with(['phamVi'])
            ->select(
                'id',
                'ma_dinh_danh',
                'ten_goi',
                'dia_chi_chi_tiet',
                'phuong_xa_ten',
                'quan_huyen_ten',
                'tinh_thanh_ten',
                'kinh_do',
                'vi_do',
                'kenh_song_ten',
                'luu_vuc_song_ten',
                'loai_diem_qtmt_ten',
                'trang_thai_ma',
                'trang_thai_ten',
                'modified_at',
                'updated_at'
            );

        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 10);
        $trangThai = $request->get('dong_bo');
        $search = $request->get('search', null);

        // search
        if ($search) {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('ten_goi', 'ilike', "%{$search}%")
                    ->orWhere('dia_chi_chi_tiet', 'ilike', "%{$search}%")
                    ->orWhere('ma_dinh_danh', 'ilike', "%{$search}%");
            });
        }

        // filter trạng thái đồng bộ (nếu có)
        if ($trangThai) {
            $query->where('trang_thai_dong_bo', $trangThai);
        }

        $query->orderBy('updated_at', 'DESC')->orderBy('id', 'DESC');

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        foreach ($data as $item) {
            $item['so_thong_so'] = $item->phamVi->count();
        }

        return $data;
    }


    public function updateDiemTramQuanTrac($id, Request $request)
    {
        $validated = $request->validate([
            'ma_dinh_danh'   => 'required|string|unique:diem_tram_qtmt,ma_dinh_danh,' . $id,
            'ten_goi'        => 'required|string',
            'dia_chi_chi_tiet' => 'nullable|string',
            'phuong_xa_ma'   => 'nullable|string',
            'phuong_xa_ten'  => 'nullable|string',
            'quan_huyen_ma'  => 'nullable|string',
            'quan_huyen_ten' => 'nullable|string',
            'tinh_thanh_ma'  => 'nullable|string',
            'tinh_thanh_ten' => 'nullable|string',
            'kinh_do'        => 'nullable|numeric',
            'vi_do'          => 'nullable|numeric',
            'kenh_song_ma'   => 'nullable|string',
            'kenh_song_ten'  => 'nullable|string',
            'luu_vuc_song_ma' => 'nullable|string',
            'luu_vuc_song_ten' => 'nullable|string',
            'loai_diem_qtmt_ma' => 'nullable|string',
            'loai_diem_qtmt_ten' => 'nullable|string',
            'trang_thai_ma'  => 'nullable|string',
            'trang_thai_ten' => 'nullable|string',
            'pham_vi'        => 'nullable|array',
            'pham_vi.*.thong_so_ma'  => 'required_with:pham_vi|string',
            'pham_vi.*.thong_so_ten' => 'required_with:pham_vi|string',
            'pham_vi.*.quy_chuan_ma' => 'nullable|string',
            'pham_vi.*.quy_chuan_ten' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Tìm điểm trạm
            $diem = DiemTramQTMT::findOrFail($id);

            // Cập nhật thông tin
            $diem->update([
                'ma_dinh_danh'   => $validated['ma_dinh_danh'],
                'ten_goi'        => $validated['ten_goi'],
                'dia_chi_chi_tiet' => $validated['dia_chi_chi_tiet'] ?? null,
                'phuong_xa_ma'   => $validated['phuong_xa_ma'] ?? null,
                'phuong_xa_ten'  => $validated['phuong_xa_ten'] ?? null,
                'quan_huyen_ma'  => $validated['quan_huyen_ma'] ?? null,
                'quan_huyen_ten' => $validated['quan_huyen_ten'] ?? null,
                'tinh_thanh_ma'  => $validated['tinh_thanh_ma'] ?? null,
                'tinh_thanh_ten' => $validated['tinh_thanh_ten'] ?? null,
                'kinh_do'        => $validated['kinh_do'] ?? null,
                'vi_do'          => $validated['vi_do'] ?? null,
                'kenh_song_ma'   => $validated['kenh_song_ma'] ?? null,
                'kenh_song_ten'  => $validated['kenh_song_ten'] ?? null,
                'luu_vuc_song_ma' => $validated['luu_vuc_song_ma'] ?? null,
                'luu_vuc_song_ten' => $validated['luu_vuc_song_ten'] ?? null,
                'loai_diem_qtmt_ma' => $validated['loai_diem_qtmt_ma'] ?? null,
                'loai_diem_qtmt_ten' => $validated['loai_diem_qtmt_ten'] ?? null,
                'trang_thai_ma'  => $validated['trang_thai_ma'] ?? '02',
                'trang_thai_ten' => $validated['trang_thai_ten'] ?? 'Nháp',
            ]);

            // Xóa phạm vi cũ
            $diem->phamVi()->delete();

            // Thêm phạm vi mới
            if (!empty($validated['pham_vi'])) {
                foreach ($validated['pham_vi'] as $pv) {
                    $diem->phamVi()->create([
                        'thong_so_ma'  => $pv['thong_so_ma'],
                        'thong_so_ten' => $pv['thong_so_ten'],
                        'quy_chuan_ma' => $pv['quy_chuan_ma'] ?? null,
                        'quy_chuan_ten' => $pv['quy_chuan_ten'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Cập nhật thành công',
                'data'    => $diem->load('phamVi')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteDiemTramQuanTrac($id)
    {
        try {
            $diemTram = DiemTramQtmt::findOrFail($id);
            $diemTram->delete();

            return response()->json([
                'message' => 'Xóa điểm trạm quan trắc thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể xóa điểm trạm quan trắc',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
