<?php

namespace App\Http\Controllers;

use App\ChiTietPhatTangThem;
use App\ChiTietViPhamXaThai;
use App\DanhMucThongSoVuotChuan;
use App\KetQuaThanhTra;
use App\LoaiThongSo;
use App\NhomHanhViViPham;
use App\Traits\GetDataCache;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoaiHinhQuanTracController extends Controller
{

    //    use App\Models\LoaiThongSo;
    // use Illuminate\Http\Request;

    public function index(Request $request)
    {
        $trangThai = $request->get('trang_thai');
        $search    = trim((string) $request->get('search', ''));

        $page    = $request->has('page')    ? (int) $request->get('page')    : null;
        $perPage = $request->has('perPage') ? (int) $request->get('perPage') : 15;
        if ($perPage <= 0) {
            $perPage = 15;
        }

        $query = LoaiThongSo::query();

        if ($trangThai !== null && $trangThai !== '') {
            $query->where('trang_thai_dong_bo', $trangThai);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ma',  'ilike', "%{$search}%");
            });
        }

        $query->orderBy('ma');

        if (!empty($page)) {
            return $query->paginate($perPage, ['*'], 'page', $page);
        }

        return response()->json([
            'message' => __('message.success'),
            'result'  => $query->get(),
        ], 200);
    }


    public function store(Request $request)
    {
        $data = $request->only(['ma', 'ten', 'trang_thai_dong_bo']);

        $rules = [
            'ma' => ['required', 'max:25', 'unique:loai_thong_sos,ma'], // đổi tên bảng nếu khác
            'ten' => ['required', 'max:255'],
            'trang_thai_dong_bo' => ['required', Rule::in(['da_dong_bo', 'chua_dong_bo'])],
        ];

        $messages = [
            'ma.required' => 'Vui lòng nhập Mã.',
            'ma.max'      => 'Mã không được vượt quá 25 ký tự.',
            'ma.unique'   => 'Mã đã tồn tại',
            'ten.required' => 'Vui lòng nhập Tên',
            'ten.max'      => 'Tên không được vượt quá 255 ký tự.',
            'trang_thai_dong_bo.required' => 'Vui lòng chọn Trạng thái đồng bộ.',
            'trang_thai_dong_bo.in'       => 'Trạng thái đồng bộ không hợp lệ.',
            'type.max' => 'Loại không được vượt quá 100 ký tự.',
        ];

        $attributes = [
            'ma' => 'Mã',
            'ten' => 'Tên',
            'trang_thai_dong_bo' => 'Trạng thái đồng bộ',
        ];

        $validator = Validator::make($data, $rules, $messages, $attributes);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = LoaiThongSo::create($data);

        return response()->json([
            'message' => 'Tạo mới thành công.',
            'result'  => $item,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $item = LoaiThongSo::findOrFail($id);

        $data = $request->only(['ma', 'ten', 'trang_thai_dong_bo', 'type', 'description']);

        $validator = Validator::make($data, [
            'ma' => 'sometimes|required|max:25|unique:loai_thong_sos,ma,' . $item->id,
            'ten' => 'sometimes|required|max:255',
            'trang_thai_dong_bo' => 'sometimes|required|in:da_dong_bo,chua_dong_bo',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ], [
            'ma.required' => 'Vui lòng nhập Mã.',
            'ma.max' => 'Mã không được vượt quá 25 ký tự.',
            'ma.unique' => 'Mã đã tồn tại.',
            'ten.required' => 'Vui lòng nhập Tên.',
            'ten.max' => 'Tên không được vượt quá 255 ký tự.',
            'trang_thai_dong_bo.in' => 'Trạng thái đồng bộ không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Dữ liệu không hợp lệ.', 'errors' => $validator->errors()], 422);
        }

        $item->update($data);

        return response()->json(['message' => 'Cập nhật thành công.', 'result' => $item], 200);
    }

    public function destroy($id)
    {
        try {
            $item = LoaiThongSo::findOrFail($id);
            $item->delete();

            return response()->json(['message' => 'Xóa thành công.', 'result' => ['id' => (int)$id]], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Không tìm thấy bản ghi.', 'errors' => ['id' => ['Bản ghi không tồn tại.']]], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23503') {
                return response()->json(['message' => 'Không thể xóa do đang được tham chiếu.', 'errors' => ['foreign_key' => ['Bản ghi đang được dùng.']]], 409);
            }
            return response()->json(['message' => 'Không thể xóa.', 'errors' => ['server' => [$e->getMessage()]]], 500);
        }
    }
}
