<?php

namespace App\Http\Controllers;

use App\ChiTietCongSuat;
use App\ChuDauTu;
use App\Organization;
use App\QuanHuyen;
use App\TinhThanh;
use App\CoQuanToChuc;
use App\CoSoSanXuat;
use App\KetQuaPhanTichMoiTruong;
use App\KetQuaThanhTra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KetQuaPhanTichMauController extends Controller
{
    public function viewIndex()
    {
        return view('ketquaphantichmau.ketquaphantichmau');
    }

    public function showFromAdd()
    {
        return view('ketquaphantichmau.addketquaphantichmau');
    }

    public function showFromEdit($id)
    {
        $data = KetQuaPhanTichMoiTruong::findOrFail($id);

        return view('ketquaphantichmau.editketquaphantichmau', [
            'data' => json_encode($data),
        ]);
    }

    public function getKetQuaPhanTichMau(Request $request)
    {
        $query = KetQuaPhanTichMoiTruong::select(
            'id',
            'dia_diem',
            'vi_tri',
            'loai_mau',
            'kinh_do',
            'vi_do',
            'thong_so',
            'don_vi_tinh',
            'phuong_phap_phan_tich',
            'ket_qua',
            'gia_tri_gioi_han',
            'so_lan_vuot',
            'loai_moi_truong'
        );

        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 10);
        $loaiMoiTruong = $request->get('loai_moi_truong');
        $search = $request->get('search', null);

        // search
        if ($search) {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('dia_diem', 'ilike', "%{$search}%")
                    ->orWhere('vi_tri', 'ilike', "%{$search}%")
                    ->orWhere('loai_mau', 'ilike', "%{$search}%")
                    ->orWhere('thong_so', 'ilike', "%{$search}%");
            });
        }

        // filter trạng thái đồng bộ (nếu có)
        if ($loaiMoiTruong) {
            $query->where('loai_moi_truong', $loaiMoiTruong);
        }

        $query->orderBy('updated_at', 'DESC')->orderBy('id', 'DESC');

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return $data;
    }

    public function addKetQuaPhanTichMau(Request $request)
    {
        // 1️⃣ Validate dữ liệu
        $validated = $request->validate([
            'dia_diem' => 'required|string|max:255',
            'vi_tri' => 'required|string|max:255',
            'loai_mau' => 'required|string|max:255',
            'kinh_do' => 'required|numeric',
            'vi_do' => 'required|numeric',
            'thong_so' => 'required|string|max:255',
            'don_vi_tinh' => 'nullable|string|max:100',
            'phuong_phap_phan_tich' => 'required|string|max:255',
            'ket_qua' => 'required|string|max:100',
            'gia_tri_gioi_han' => 'required|string|max:100',
            'so_lan_vuot' => 'nullable|numeric',
            'loai_moi_truong' => 'required|string|max:100',
            'tep_pdf' => 'nullable|file|mimes:pdf|max:51200', // 50MB
        ]);

        DB::beginTransaction();
        try {
            // 2️⃣ Lưu file PDF vào storage/app/public/files
            $pdfPath = null;
            if ($request->hasFile('tep_pdf')) {
                $file = $request->file('tep_pdf');
                $originalName = $file->getClientOriginalName();
                $directory = 'files';

                // Nếu file trùng tên, thêm timestamp để tránh ghi đè
                if (Storage::disk('public')->exists($directory . '/' . $originalName)) {
                    $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $originalName = $nameWithoutExt . '_' . time() . '.' . $extension;
                }

                // Luôn lưu file (bất kể trùng hay không)
                $pdfPath = $file->storeAs($directory, $originalName, 'public');
            }

            // 3️⃣ Tạo bản ghi trong DB
            $ketQua = KetQuaPhanTichMoiTruong::create([
                'dia_diem' => $validated['dia_diem'],
                'vi_tri' => $validated['vi_tri'],
                'loai_mau' => $validated['loai_mau'],
                'kinh_do' => $validated['kinh_do'],
                'vi_do' => $validated['vi_do'],
                'thong_so' => $validated['thong_so'],
                'don_vi_tinh' => $validated['don_vi_tinh'] ?? null,
                'phuong_phap_phan_tich' => $validated['phuong_phap_phan_tich'],
                'ket_qua' => $validated['ket_qua'],
                'gia_tri_gioi_han' => $validated['gia_tri_gioi_han'],
                'so_lan_vuot' => $validated['so_lan_vuot'] ?? null,
                'loai_moi_truong' => $validated['loai_moi_truong'],
                'tep_pdf' => $pdfPath,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Thêm mới kết quả phân tích mẫu thành công',
                'data' => $ketQua,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateKetQuaPhanTichMau($id, Request $request)
    {
        $validated = $request->validate([
            'dia_diem' => 'required|string|max:255',
            'vi_tri' => 'required|string|max:255',
            'loai_mau' => 'required|string|max:255',
            'kinh_do' => 'required|numeric',
            'vi_do' => 'required|numeric',
            'thong_so' => 'required|string|max:255',
            'don_vi_tinh' => 'nullable|string|max:100',
            'phuong_phap_phan_tich' => 'required|string|max:255',
            'ket_qua' => 'required|string|max:100',
            'gia_tri_gioi_han' => 'required|string|max:100',
            'so_lan_vuot' => 'nullable|numeric',
            'loai_moi_truong' => 'required|string|max:100',
            'tep_pdf' => 'nullable|file|mimes:pdf|max:51200', // 50MB
        ]);

        DB::beginTransaction();
        try {
            $ketQua = KetQuaPhanTichMoiTruong::findOrFail($id);
            $pdfPath = $ketQua->tep_pdf; // Giữ file cũ

            // Nếu có file mới
            if ($request->hasFile('tep_pdf')) {
                $file = $request->file('tep_pdf');
                $directory = 'files';
                $originalName = $file->getClientOriginalName();

                // Xóa file cũ (nếu có)
                if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                    Storage::disk('public')->delete($pdfPath);
                }

                // Tránh trùng tên
                if (Storage::disk('public')->exists($directory . '/' . $originalName)) {
                    $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $originalName = $nameWithoutExt . '_' . time() . '.' . $extension;
                }

                $pdfPath = $file->storeAs($directory, $originalName, 'public');
            }

            // Cập nhật
            $ketQua->update(array_merge($validated, ['tep_pdf' => $pdfPath]));

            DB::commit();
            return response()->json([
                'message' => 'Cập nhật kết quả phân tích mẫu thành công',
                'data' => $ketQua,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteKetQuaPhanTichMau($id)
    {
        DB::beginTransaction();
        try {
            $ketQua = KetQuaPhanTichMoiTruong::findOrFail($id);

            // Xóa file PDF nếu có
            if ($ketQua->tep_pdf && Storage::disk('public')->exists($ketQua->tep_pdf)) {
                Storage::disk('public')->delete($ketQua->tep_pdf);
            }

            $ketQua->delete();

            DB::commit();

            return response()->json([
                'message' => 'Xóa kết quả phân tích mẫu thành công',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
