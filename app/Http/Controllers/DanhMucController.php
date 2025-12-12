<?php

namespace App\Http\Controllers;

use App\ChuDauTu;
use App\ChuyenDoiDonVi;
use App\CoQuanToChuc;
use App\CoSoSanXuat;
use App\DanhMucHanhViViPham;
use App\KhuCongNghiep;
use App\LoaiHinhCoSo;
use App\LoaiHinhOiNhiem;
use App\LoaiKhuCongNghiep;
use App\Organization;
use App\PhatTangThem;
use App\PhuongXa;
use App\QuanHuyen;
use App\TinhThanh;
use App\LoaiCoSo;
use App\Muc;
use App\Dieu;
use App\Khoan;
use App\Traits\ExecuteString;
use App\Traits\GenNo;
use App\Traits\GetDataCache;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Math;
use App\LoaiThongSo;
use App\Mien;
use App\NghiDinh;
use App\QuyetDinhThanhTra;
// use Validator;
use Illuminate\Support\Facades\Validator;


class DanhMucController extends Controller
{
    use ExecuteString, GetDataCache, GenNo;

    public function index(Request $request)
    {
        return view('danhmuc.dungchung.index');
    }
    public function indexDanhMucThanhTra(Request $request)
    {
        return view('danhmuc.danhmucthanhtra.index');
    }
    public function cosophaply()
    {
        return view('danhmuc.cosophaply.index');
    }
    public function nghiDinh45View()
    {
        return view('danhmuc.nghidinh45.index');
    }
    public function nghiDinh(Request $request)
    {
        $query = NghiDinh::query();
        $query->orderBy('id');
        return $query->get();
    }

    public function nghiDinhStore(Request $request)
    {
        $info = $request->all();
        $nghiDinh = NghiDinh::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $nghiDinh, // Trả về dữ liệu đã tạo nếu cần thiết
        ], 200);
    }
    public function nghiDinhUpdate(Request $request, $id)
    {
        $info = $request->all();
        $item = NghiDinh::findOrFail($id);
        $item->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function nghiDinhDelete($id)
    {
        $item = NghiDinh::findOrFail($id);
        $item->delete();
        return response()->json([
            'message' => __('message.delete_success'), // Cần đặt thông báo trong file ngôn ngữ
        ], 200);
    }
    public function getDanhMucHanhViChuaDongBo(Request $request, $nghi_dinh_id)
    {
        $query = DanhMucHanhViViPham::query();
        $query = $query->where('nghi_dinh_id', $nghi_dinh_id)->where('trang_thai_dong_bo', 'chua_dong_bo');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where(function ($subQuery) use ($search) {
                // Sử dụng where trong function để nhóm các điều kiện lại với nhau, tránh xung đột với where bên ngoài
                $subQuery->where('ma_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('nhom_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('ten_hanh_vi', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('created_at', 'ASC');
        $query->orderBy('id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function xoaDanhMucHanhVi($id)
    {
        $query = DanhMucHanhViViPham::findOrFail($id);
        $query->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }


    public function getDanhMucHanhVi(Request $request)
    {
        $query = DanhMucHanhViViPham::query();
        $query = $query->where('phap_ly', '45/2022/NĐ-CP')->where('trang_thai_dong_bo', 'chua_dong_bo');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where('ma_hanh_vi', 'ilike', "%{$search}%")
                ->orWhere('nhom_hanh_vi', 'ilike', "%{$search}%")
                ->orWhere('ten_hanh_vi', 'ilike', "%{$search}%");
        }
        $query->orderBy('created_at', 'ASC');
        $query->orderBy('id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function getDanhMucHanhViDaDongBoNew(Request $request, $nghi_dinh_id)
    {
        $query = DanhMucHanhViViPham::query();
        $query = $query->where('nghi_dinh_id', $nghi_dinh_id)->where('trang_thai_dong_bo', 'da_dong_bo');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where(function ($query) use ($search) {
                $query->where('ma_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('nhom_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('ten_hanh_vi', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('created_at', 'ASC');
        $query->orderBy('id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function getDanhMucHanhViDaDongBo(Request $request)
    {
        $query = DanhMucHanhViViPham::query();
        $query = $query->where('phap_ly', '45/2022/NĐ-CP')->where('trang_thai_dong_bo', 'da_dong_bo');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where(function ($query) use ($search) {
                $query->where('ma_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('nhom_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('ten_hanh_vi', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('created_at', 'ASC');
        $query->orderBy('id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function addKhuCongNghiep(Request $request)
    {
        $info = $request->all();
        $info['ma'] = 'KCN_' . (isset($info['tinh_thanh_id']) ? (TinhThanh::where('id', $info['tinh_thanh_id'])->first()->ma) : 'NON') . '_' . (KhuCongNghiep::query()->max('id') + 1);
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.validator'));
        }
        if (empty($info['inactive'])) {
            $info['inactive'] = false;
        }
        $kcn = KhuCongNghiep::create($info);
        if ($request->ajax()) {
            return response()->json([
                'message' => __('message.success'),
                'result' => KhuCongNghiep::where('id', $kcn->id)->with(['tinhThanh', 'quanHuyen', 'tinhThanh.quanHuyens'])->first(),
            ], 200, []);
        }
        return redirect()
            ->route('khucongnghiep.index')
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.create_success'));
    }

    public function updateKhuCongNghiep(Request $request, $id)
    {
        $khucn = KhuCongNghiep::find($id);
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.validator'));
        }

        DB::beginTransaction();
        try {
            $khucn->update($info);
            DB::commit();
            if ($request->ajax()) {
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.update_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.update_error'));
        }
    }

    public function deleteKhuCongNghiep(Request $request, $id)
    {
        $khucn = KhuCongNghiep::find($id);
        try {
            $khucn->delete();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'message.fails',
                ], 200, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error'));
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => 'message.success',
            ], 200, []);
        }

        return back()->withInput()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.delete_success'));
    }

    public function indexLoaiKCN(Request $request)
    {
        $query = \App\LoaiKhuCongNghiep::query();
        $query->orderBy('ma_muc', 'asc');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function indexLoaiHinhCoSo(Request $request)
    {
        $type = $request->get('type');
        if (isset($type) && $type == 'report') {
            $parents = \App\LoaiHinhCoSo::query()->whereNull('parent_id')->orderBy('id')->get();
            $data = [];
            foreach ($parents as $parent) {
                $data[] = [
                    'parent' => $parent->ten,
                    'childrens' => LoaiHinhCoSo::query()->where('parent_id', $parent->id)->get(),
                ];
            }
            return response()->json([
                'message' => __('message.success'),
                'result' => $data,
            ], 200, []);
        }
        $query = \App\LoaiHinhCoSo::query();
        $query->whereNotNull('parent_id');
        $query->select('id', 'parent_id', 'ten');
        $query->orderBy('id');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function getSelectLoaiHinhHoatDong(Request $request)
    {
        $search = $request->get('search', null);
        $query = LoaiHinhCoSo::where('van_ban_phap_luat', '04/2012/TT-BTNMT');
        if ($search) {
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%");
        }
        return $query->get();
    }
    public function getSelectNganhNghe(Request $request)
    {
        $search = $request->get('search', null);
        $query = LoaiHinhCoSo::where('van_ban_phap_luat', '27/2018/QĐ-TTg');
        if ($search) {
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%")
                ->orWhere('ma', 'ilike', "%{$search}%");
        }
        return $query->get();
    }

    public function treeLoaiHinhCoSo(Request $request)
    {
        $danh_muc = DB::select(
            "
            WITH RECURSIVE tree_view AS (
                SELECT *,
                    0 AS level,
                    CAST(id AS varchar(50)) AS order_sequence,
                    CAST(ma AS varchar(50)) AS ma_danh_muc
                FROM loai_hinh_co_sos
                WHERE parent_id IS NULL and van_ban_phap_luat = '27/2018/QĐ-TTg' and inactive = FALSE
            UNION ALL
                SELECT parent.*,
                    level + 1 AS level,
                    CAST(order_sequence || '_' || CAST(parent.id AS VARCHAR (50)) AS VARCHAR(50)) AS order_sequence,
                    CAST(ma_danh_muc || '/' || CAST(parent.ma AS VARCHAR (50)) AS VARCHAR(50)) AS ma_danh_muc
                FROM loai_hinh_co_sos parent
                JOIN tree_view tv
                ON parent.parent_id = tv.id and parent.van_ban_phap_luat = '27/2018/QĐ-TTg' and parent.inactive = FALSE
            )
            SELECT   *
            FROM tree_view
            ORDER BY order_sequence;
            "
        );
        $data = [];
        foreach ($danh_muc as $key => $item) {
            $data = $this->recursive($data, $item);
        }
        return ($data);
    }
    public function getLoaiNganhNghe()
    {
        $data =  DB::select(
            "
            WITH RECURSIVE tree_view AS (
                SELECT *,
                    0 AS level,
                    CAST(id AS varchar(50)) AS order_sequence,
                    CAST(ma AS varchar(50)) AS ma_danh_muc
                FROM loai_hinh_co_sos
                WHERE parent_id IS NULL and van_ban_phap_luat = '27/2018/QĐ-TTg' and inactive = FALSE
            UNION ALL
                SELECT parent.*,
                    level + 1 AS level,
                    CAST(order_sequence || '_' || CAST(parent.id AS VARCHAR (50)) AS VARCHAR(50)) AS order_sequence,
                    CAST(ma_danh_muc || '/' || CAST(parent.ma AS VARCHAR (50)) AS VARCHAR(50)) AS ma_danh_muc
                FROM loai_hinh_co_sos parent
                JOIN tree_view tv
                ON parent.parent_id = tv.id and parent.van_ban_phap_luat = '27/2018/QĐ-TTg'  and parent.inactive = FALSE
            )
            SELECT   *
            FROM tree_view
            ORDER BY order_sequence;
            "
        );
        return $data;
    }
    private function recursive(array $data, $value)
    {
        $explode = explode("_", $value->order_sequence);
        if (count($explode) == 1) {
            $data[] = [
                "label" => $value->ten,
                "id" => $value->id,
                'ma' => $value->ma,
                'level' => $value->level,
                'ma_danh_muc' => $value->ma_danh_muc,
                "parent_id" => $value->parent_id,
            ];
        } else {
            $key = array_shift($explode);
            $new_order_sequence = implode("_", $explode);
            $value->order_sequence = $new_order_sequence;
            $filter = array_filter($data, function ($var) use ($key) {
                return $var["id"] == $key;
            });
            if (!isset($data[array_key_first($filter)]["children"])) {
                $data[array_key_first($filter)]["children"] = [];
            }
            $data[array_key_first($filter)]["children"] = $this->recursive(
                $data[array_key_first($filter)]["children"],
                $value
            );
        }
        return $data;
    }
    public function addLoaiKCN(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ma' => 'required|max:255',
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\LoaiKhuCongNghiep::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateLoaiKCN(Request $request, $id)
    {
        $info = $request->all();
        \App\LoaiKhuCongNghiep::where('ma', $id)->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteLoaiKCN(Request $request, $id)
    {
        $kcn = KhuCongNghiep::where('loai_khu_cong_nghiep', $id)->first();
        if ($kcn) {
            return response()->json([
                'message' => "Không thể xóa! Đã có $kcn->ten thuộc loại này",
                'result' => [],
            ], 500, []);
        }
        \App\LoaiKhuCongNghiep::where('ma', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function addLoaiHinhCoSo(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\LoaiHinhCoSo::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateLoaiHinhCoSo(Request $request, $id)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'ten' => 'required',
                'ma' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            LoaiHinhCoSo::where('id', $id)->update([
                'ten' => $data["ten"],
                'ma' => $data['ma'],
                'parent_id' => $data['parent_id'],
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function deleteLoaiHinhCoSo($id)
    {
        try {
            LoaiHinhCoSo::where('id', $id)->update([
                'inactive' => true,
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function editLoaiHinhCoSo(Request $request, $id)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'inactive' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            LoaiHinhCoSo::where('id', $id)->update([
                'ma' => $data["ma"],
                'ten' => $data['ten']
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function indexTinhThanh(Request $request)
    {
        $mienId = $request->get('mienId', null);
        $search = $request->get('search');
        $asytinh = $request->get('asytinh');
        $luuVucSongId = $request->get('luuVucSongId', null);
        $query = \App\TinhThanh::query();
        if (isset($search)) {
            $query = $query->where('ten', 'ilike', "%{$search}%");
            $query->with('quanHuyens', 'mien', 'vungKinhTeTrongDiem', 'luuVucSong');
            $query->orderBy('mien_id')->orderBy('ma');
        } elseif (isset($asytinh)) {
            $query = $query->where('id', $asytinh);
            $query->with('quanHuyens', 'mien', 'vungKinhTeTrongDiem', 'luuVucSong');
            $query->orderBy('mien_id')->orderBy('ma');
        } else {
            $query->with('quanHuyens', 'mien', 'vungKinhTeTrongDiem', 'luuVucSong');
            if ($mienId) {
                $query->where("mien_id", $mienId);
            }
            if ($luuVucSongId) {
                $query->where("luu_vuc_song_id", $luuVucSongId);
            }
            $query->orderBy('mien_id')->orderBy('ma');
        }

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addTinhThanh(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'ma' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = TinhThanh::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }
    public function updateTinhThanh(Request $request, $id)
    {
        $info = $request->all();
        $item = \App\TinhThanh::find($id);
        $item->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function deleteTinhThanh($id)
    {
        try {
            TinhThanh::where('id', $id)->delete();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function indexQuanHuyen(Request $request)
    {
        $query = QuanHuyen::query();
        $search = $request->get('search');
        $asyhuyen = $request->get('asyhuyen');
        $query->select('ten', 'tinh_thanh_id', 'id');
        $tinh_thanh_id = $request->get('tinh_thanh_id');
        $quan_huyen_id = $request->get('quan_huyen_id');
        $query->orderBy('tinh_thanh_id', 'ten');

        if (isset($tinh_thanh_id)) {
            $query->where('tinh_thanh_id', $tinh_thanh_id);
            return response()->json([
                'message' => __('message.success'),
                'result' => $query->get(),
            ], 200, []);
        } elseif (isset($quan_huyen_id)) {
            $huyen = $query->where('id', $quan_huyen_id)->first();
            $tinh = \App\TinhThanh::query()->where('id', $huyen->tinh_thanh_id)->first();
            return response()->json([
                'message' => __('message.success'),
                'result' => $tinh,
            ], 200, []);
        } elseif (isset($asyhuyen)) {
            $query->where('id', $asyhuyen);
            return response()->json([
                'message' => __('message.success'),
                'result' => $query->first(),
            ], 200, []);
        } elseif (isset($search)) {
            $query->where('ten', 'ilike', "%{$search}%");
            return response()->json([
                'message' => __('message.success'),
                'result' => $query->get(),
            ], 200, []);
        } else {
            return response()->json([
                'message' => __('message.success'),
                'result' => $query->get(),
            ], 200, []);
        }
    }
    public function indexKhuCongNghiep(Request $request)
    {
        $query = \App\KhuCongNghiep::query();
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('tinh_thanh_id', 'ten');
        $query->orderBy('id', 'DESC');
        $tinh_thanh_id = $request->get('tinh_thanh_id');
        $screen = $request->get('screen');
        if ($request->ajax()) {
            if (isset($tinh_thanh_id)) {
                return response()->json([
                    'message' => __('message.success'),
                    'result' => $query->where('tinh_thanh_id', $tinh_thanh_id)->get(),
                ], 200, []);
            } else if (isset($screen)) {
                $perPage = $request->get('perpage', 10);
                $page = $request->get('page', 1);
                $search = $request->get('search');
                $search_tinh_thanh = $request->get('tinh');
                $search_quan_huyen = $request->get('huyen');
                $search_loai = $request->get('loaikhucongnghiep');
                if (isset($search)) {
                    $query->where(function ($query) use ($search) {
                        $query->orWhere('ten', 'ilike', "%{$search}%");
                        $query->orWhere('ma', 'ilike', "%{$search}%");
                        $query->orWhere('dia_chi', 'ilike', "%{$search}%");
                        $query->orWhere('xa_phuong', 'ilike', "%{$search}%");
                    });
                }
                if (!empty($search_tinh_thanh)) {
                    $search_tinh_thanh = explode(',', $search_tinh_thanh);
                    $query->whereIn('tinh_thanh_id', $search_tinh_thanh);
                }
                if (!empty($search_quan_huyen)) {
                    $search_quan_huyen = explode(',', $search_quan_huyen);
                    $query->whereIn('quan_huyen_id', $search_quan_huyen);
                }
                if (!empty($search_loai)) {
                    $search_loai = explode(',', $search_loai);
                    $query->whereIn('loai_khu_cong_nghiep', $search_loai);
                }

                $query->with('quanHuyen', 'tinhThanh.quanHuyens', 'loaiKhuCongNghiep');
                $khucongnghieps = $query->paginate($perPage, ['*'], 'page', $page);
                return response()->json([
                    'message' => __('message.success'),
                    'result' => $khucongnghieps,
                ], 200, []);
            } else {
                return response()->json([
                    'message' => __('message.success'),
                    'result' => $query->get(),
                ], 200, []);
            }
        }
    }

    public function asynKhuCongNghiep(Request $request)
    {
        $search = $request->get('search');
        $query = KhuCongNghiep::query();
        if (isset($search)) {
            $query->where('ten', 'like', "%{$search}%");
        }
        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function indexDieu(Request $request)
    {

        $query = \App\Dieu::query();
        $query->with('khoans.mucs');
        $query->select('id', 'ten', 'mo_ta');
        $query->orderBy('ten');
        $data = $query->get();
        $this->forever('danh_muc', \App\Dieu::class, $data);

        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function indexCoQuanToChuc(Request $request)
    {
        // tongdv bo phan loc theo ma dinh danh
        // $query = CoQuanToChuc::query()->whereNotNull('ma_dinh_danh');
        $query = CoQuanToChuc::query();
        $search = $request->get('search');
        if (isset($search)) {
            $query = $query->Where('ten', 'ilike', "%{$search}%");
        }
        return response()->json([
            'message' => __('message.success'),
            'result' => $query->take(3000)->get(),
        ], 200, []);
    }

    public function addCoQuanToChuc(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\CoQuanToChuc::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateCoQuanToChuc(Request $request, $id)
    {
        $info = $request->all();
        $item = \App\CoQuanToChuc::find($id);
        $item->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteCoQuanToChuc(Request $request, $id)
    {
        \App\CoQuanToChuc::where('id', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function async(Request $request)
    {
        $search = $request->get('search');
        // $coSoDongBo = CoSoSanXuat::where('trang_thai_dong_bo', 'da_dong_bo')->pluck('organization_id')->toArray();
        // $query = Organization::with(['chuDauTu.coQuanCapGiayKinhDoanh',  'chuDauTu.quanHuyen', 'chuDauTu.tinhThanh'])->whereIn('id', $coSoDongBo);
        $query = Organization::with(['chuDauTu.coQuanCapGiayKinhDoanh',  'chuDauTu.quanHuyen', 'chuDauTu.tinhThanh'])->whereNotNull('chu_dau_tu_id');

        if ($request->get('hasCoSo')) {
            $query = $query->has('coSoSanXuats');
        }
        if (isset($search)) {
            $query->where('ten', 'ilike', "%{$search}%");
        }
        $query->orderBy('ten');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function indexVungKinhTe(Request $request)
    {
        $query = \App\VungKinhTeTrongDiem::query();
        $query->orderBy('ten', 'asc');
        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addVungKinhTeTrongDiem(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ma' => 'required|max:255',
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\VungKinhTeTrongDiem::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateVungKinhTeTrongDiem(Request $request, $id)
    {
        $info = $request->all();
        \App\VungKinhTeTrongDiem::where('ma', $id)->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteVungKinhTeTrongDiem(Request $request, $id)
    {
        \App\VungKinhTeTrongDiem::where('ma', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function indexMien(Request $request)
    {
        $query = \App\Mien::query();
        $query->orderBy('ma_dinh_danh');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addMien(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ma' => 'required|max:255',
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\Mien::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateMien(Request $request, $id)
    {
        $info = $request->all();
        \App\Mien::where('ma', $id)->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteMien(Request $request, $id)
    {
        $tinhThanh = TinhThanh::where('mien_id', $id)->first();
        if ($tinhThanh) {
            return response()->json([
                'message' => "Không thể xóa! Đã có $tinhThanh->ten thuộc miên này",
                'result' => [],
            ], 500, []);
        }
        \App\Mien::where('id', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function indexCacBienPhapKhacPhucHauQua(Request $request)
    {
        $query = \App\CacBienPhapKhacPhucHauQua::query();
        $query->orderBy('ten', 'asc');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addCacBienPhapKhacPhucHauQua(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\CacBienPhapKhacPhucHauQua::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateCacBienPhapKhacPhucHauQua(Request $request, $id)
    {
        $info = $request->all();
        \App\CacBienPhapKhacPhucHauQua::where('id', $id)->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteCacBienPhapKhacPhucHauQua(Request $request, $id)
    {
        \App\CacBienPhapKhacPhucHauQua::where('id', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function indexLoaiTTHC(Request $request)
    {
        $query = \App\LoaiThuTucHanhChinh::query();
        $query->orderBy('ma', 'asc');
        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addLoaiTTHC(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ma' => 'required|max:255',
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\LoaiThuTucHanhChinh::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateLoaiTTHC(Request $request, $id)
    {
        $info = $request->all();
        \App\LoaiThuTucHanhChinh::where('ma', $id)->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteLoaiTTHC($id)
    {
        $query = \App\KetQuaThanhTraThuTucHanhChinh::where('loai_thu_tuc_hanh_chinh_id', $id)->first();
        if ($query) {
            return response()->json([
                'message' => "Đã có kết quả thanh tra TTHC số: $query->so_ket_luan sử dụng dữ liệu này",
            ], 500, []);
        }
        try {
            \App\LoaiThuTucHanhChinh::where('id', $id)->delete();
            return response()->json([
                'message' => __('message.success'),
                'result' => [],
            ], 200, []);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('Không thể xóa loại thủ tục hành chính'),
                'result' => [$id],
            ], 500, []);
        }
    }
    public function indexLuuVucSong(Request $request)
    {
        $query = \App\LuuVucSong::with('tinhthanhs', 'tinhThanhNews');
        $query->orderBy('ten', 'asc');
        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addLuuVucSong(Request $request)
    {
        $info = $request->all();

        $validator = Validator::make($info, [
            'ma'  => 'required|max:255',
            'ten' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result'  => $validator->errors(),
            ], 400);
        }

        return DB::transaction(function () use ($info) {
            // 1) Tạo lưu vực sông
            $item = \App\LuuVucSong::create([
                'ma'        => $info['ma'] ?? null,
                'ten'       => $info['ten'] ?? null,
                'mo_ta'     => $info['mo_ta'] ?? null,
                'chieu_dai' => $info['chieu_dai'] ?? null,
                'dien_tich' => $info['dien_tich'] ?? null,
            ]);

            // 2) Lấy danh sách tỉnh (unique, bỏ rác/null)
            $provinceIds = collect($info['tinhThanh'] ?? [])
                ->pluck('id')
                ->filter()
                ->unique()
                ->values();

            // 3) Chuẩn bị rows chỉ gồm tỉnh và lưu vực
            $rows = $provinceIds->map(fn ($tid) => [
                'tinh_thanh_id'   => $tid,
                'luu_vuc_song_id' => $item->id,
                'created_at'      => now(),
                'updated_at'      => now(),
            ])->all();

            if (!empty($rows)) {
                \App\TinhThanhLuuVucSong::insert($rows);
            }

            return response()->json([
                'message' => __('message.success'),
                'result'  => $item->fresh(),
            ], 200);
        });
    }

    public function updateLuuVucSong(Request $request, $id)
    {
        $info = $request->all();

        return DB::transaction(function () use ($id, $info) {
            // 1) Cập nhật thông tin cơ bản của lưu vực
            $item = \App\LuuVucSong::findOrFail($id);
            $item->update([
                'ten'       => $info['ten']       ?? $item->ten,
                'mo_ta'     => $info['mo_ta']     ?? $item->mo_ta,
                'chieu_dai' => $info['chieu_dai'] ?? $item->chieu_dai,
                'dien_tich' => $info['dien_tich'] ?? $item->dien_tich,
            ]);

            // 2) Lấy danh sách tỉnh từ payload (chấp nhận [{id:..}] hoặc [id,...])
            $incoming = collect($info['tinhThanh'] ?? [])
                ->map(fn($x) => is_array($x) ? ($x['id'] ?? null) : $x)
                ->filter()
                ->unique()
                ->values();

            // 3) Lấy danh sách tỉnh đang có trong bảng liên kết
            $existing = \App\TinhThanhLuuVucSong::where('luu_vuc_song_id', $id)
                ->pluck('tinh_thanh_id');

            // 4) Tính phần cần thêm và cần xóa để đồng bộ
            $toInsert = $incoming->diff($existing)
                ->map(fn($pid) => [
                    'tinh_thanh_id'   => $pid,
                    'luu_vuc_song_id' => $id,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ])->values()->all();

            $toDelete = $existing->diff($incoming);

            if (!empty($toInsert)) {
                \App\TinhThanhLuuVucSong::insert($toInsert);
            }
            if ($toDelete->isNotEmpty()) {
                \App\TinhThanhLuuVucSong::where('luu_vuc_song_id', $id)
                    ->whereIn('tinh_thanh_id', $toDelete)
                    ->delete();
            }

            return response()->json([
                'message' => __('message.success'),
                'result'  => $item->fresh(),
            ], 200);
        });
    }


    public function deleteLuuVucSong(Request $request, $id)
    {
        \App\TinhThanhLuuVucSong::where('luu_vuc_song_id', $id)->delete();
        \App\LuuVucSong::where('id', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function indexLoaiXuPhatBoSung(Request $request)
    {
        $query = \App\LoaiXuPhatBoSung::query();
        $query->orderBy('ten', 'asc');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addLoaiXuPhatBoSung(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = \App\LoaiXuPhatBoSung::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updateLoaiXuPhatBoSung(Request $request, $id)
    {
        $info = $request->all();
        \App\LoaiXuPhatBoSung::where('id', $id)->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function deleteLoaiXuPhatBoSung(Request $request, $id)
    {
        \App\LoaiXuPhatBoSung::where('id', $id)->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }
    public function indexChuyenDoiDonVi()
    {
        $donvis = $this->getDataByName(ChuyenDoiDonVi::class);
        $donvigoc = [];
        $dv = [];
        foreach ($donvis as $donvi) {
            if ($donvi->loai == 'luu_luong_khi_thai') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['luu_luong_khi_thai'] = $donvi;
                }
                $dv['luu_luong_khi_thai'][] = $donvi;
            }
            if ($donvi->loai == 'luu_luong_nuoc_thai') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['luu_luong_nuoc_thai'] = $donvi;
                }
                $dv['luu_luong_nuoc_thai'][] = $donvi;
            }
            if ($donvi->loai == 'dien_tich') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['dien_tich'] = $donvi;
                }
                $dv['dien_tich'][] = $donvi;
            }
            if ($donvi->loai == 'cong_xuat_xu_ly_nuoc_thai') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['cong_xuat_xu_ly_nuoc_thai'] = $donvi;
                }
                $dv['cong_xuat_xu_ly_nuoc_thai'][] = $donvi;
            }
            if ($donvi->loai == 'cong_xuat_xu_ly_khi_thai') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['cong_xuat_xu_ly_khi_thai'] = $donvi;
                }
                $dv['cong_xuat_xu_ly_khi_thai'][] = $donvi;
            }
            if ($donvi->loai == 'khoi_luong_phat_sinh_chat_thai') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['khoi_luong_phat_sinh_chat_thai'] = $donvi;
                }
                $dv['khoi_luong_phat_sinh_chat_thai'][] = $donvi;
            }
            if ($donvi->loai == 'cong_suat_hoat_dong') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['cong_suat_hoat_dong'] = $donvi;
                }
                $dv['cong_suat_hoat_dong'][] = $donvi;
            }
            if ($donvi->loai == 'cong_suat_thiet_ke') {
                if ($donvi->don_vi_goc) {
                    $donvigoc['cong_suat_thiet_ke'] = $donvi;
                }
                $dv['cong_suat_thiet_ke'][] = $donvi;
            }
            if ($donvi->loai == 'thong_so_vuot_chuan') {
                $dv['thong_so_vuot_chuan'][] = $donvi;
            }
        }
        $data = [
            'don_vi_goc' => $donvigoc,
            'don_vi' => $dv,
        ];
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function luuluongnuocthai()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "luu_luong_nuoc_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function updateLuuLuongNuocThai(Request $request, $id)
    {
        $info = $request->all();
        $update = ChuyenDoiDonVi::where('id', $id)->first();
        $update->update([
            'ten' => $info['ten'],
            'ty_le' => $info['ty_le'],
            'mo_ta' => $info['mo_ta'],
        ]);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }
    public function deleteLuuLuongNuocThai($id)
    {
        $data = ChuyenDoiDonVi::query()->where('id', $id);
        $data->delete();
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }
    public function addLuuLuongNuocThai(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "luu_luong_nuoc_thai";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "luu_luong_nuoc_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function luuluongkhithai()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "luu_luong_khi_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function addLuuLuongKhiThai(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "luu_luong_khi_thai";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "luu_luong_khi_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function congsuatxulykhithai()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_xuat_xu_ly_khi_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function addcongsuatkhithai(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "cong_xuat_xu_ly_khi_thai";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_xuat_xu_ly_khi_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function congsuatxulynuocthai()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_xuat_xu_ly_nuoc_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function addCongSuatNuocThai(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "cong_xuat_xu_ly_nuoc_thai";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_xuat_xu_ly_nuoc_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function khoiluongphatsinhchatthai()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "khoi_luong_phat_sinh_chat_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function addphatsinhchatthai(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "khoi_luong_phat_sinh_chat_thai";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "khoi_luong_phat_sinh_chat_thai")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function congsuathethong()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_suat_hoat_dong")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function addcongsuathethong(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "cong_suat_hoat_dong";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_suat_hoat_dong")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function congsuatthietke()
    {
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_suat_thiet_ke")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function addcongsuatthietke(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "cong_suat_thiet_ke";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "cong_suat_thiet_ke")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }
    public function dientich()
    {
        return response()->json([
            'message' => __('message.success'),
            'result' => ChuyenDoiDonVi::query()->where('loai', "dien_tich")->get(),
        ], 200, []);
    }
    public function AddDienTich(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "dien_tich";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "dien_tich")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function thongSoVuotChuan()
    {
        return response()->json([
            'message' => __('message.success'),
            'result' => ChuyenDoiDonVi::query()->where('loai', "thong_so_vuot_chuan")->get(),
        ], 200, []);
    }
    public function addThongSoVuotChuan(Request $request)
    {
        $info = $request->all();
        $info['loai'] = "thong_so_vuot_chuan";
        ChuyenDoiDonVi::query()->create($info);
        $data = ChuyenDoiDonVi::query()->where('loai', "thong_so_vuot_chuan")->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function updateDonViGoc(Request $request)
    {
        $id = $request->get('id');
        $loai = $request->get('loai');
        ChuyenDoiDonVi::where('loai', $loai)->update(['don_vi_goc' => false]);
        $donvigoc = ChuyenDoiDonVi::where('id', $id)->first();
        $donvigoc->update(['don_vi_goc' => true]);
        return response()->json([
            'message' => __('message.success'),
            'result' => ['loai' => $loai],
        ], 200, []);
    }

    public function indexNhomLoaiHinhCoSo(Request $request)
    {
        $parents = \App\LoaiHinhCoSo::query()->whereNull('parent_id')->where('van_ban_phap_luat', '04/2012/TT-BTNMT')->orderBy('id')->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $parents,
        ], 200, []);
    }

    public function getChiTietLoaiHinhCoSo($id)
    {
        return response()->json([
            'message' => __('message.success'),
            'result' => LoaiHinhCoSo::query()->whereParentId($id)->get(),
        ], 200, []);
    }

    public function getPhatTangThem(Request $request)
    {
        $type = $request->get('search_type');
        $query = PhatTangThem::query();
        if (!empty($type)) {
            $query->where('type', $type);
        }
        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }
    public function addPhatTangThem(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'phan_tram_tang_them' => 'required',
            'tu_gia_tri' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        PhatTangThem::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }
    public function updatePhatTangThem(Request $request, $id)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'phan_tram_tang_them' => 'required',
            'tu_gia_tri' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        PhatTangThem::find($id)->update($info);

        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ]);
    }
    public function deletePhatTangThem($id)
    {
        try {
            PhatTangThem::find($id)->delete();
            return response()->json([
                'message' => __('message.success'),
                'result' => [],
            ], 200, []);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('message.error'),
                'result' => [],
            ], 500, []);
        }
    }
    public function getLoaiHinhOiNhiem()
    {
        $data = LoaiHinhOiNhiem::get();
        return $data;
    }
    public function addLoaiHinhOiNhiem(Request $request)
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
        LoaiHinhOiNhiem::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function updateLoaiHinhOiNhiem($id, Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'ten' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            // dd($data);
            LoaiHinhOiNhiem::where('id', $id)->update([
                'ten' => $data["ten"],
                'ma' => $data["ma"]
            ]);
            // dd($data,$id);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function editLoaiHinhOiNhiem($id, Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'inactive' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            //dd($data);
            LoaiHinhOiNhiem::where('id', $id)->update([
                'inactive' => $data["inactive"]
            ]);
            // dd($data,$id);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function deleteLoaiHinhOiNhiem($id, Request $request)
    {
        // try{
        //     LoaiHinhOiNhiem::where('id',$id)->delete();
        // }catch(\Exception $e){
        //     dd($e);
        // }
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'inactive' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            //dd($data);
            LoaiHinhOiNhiem::where('id', $id)->update([
                'inactive' => $data["inactive"]
            ]);
            // dd($data,$id);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function getLoaiCoSo()
    {
        $data = LoaiCoSo::get();
        return $data;
    }
    public function addLoaiCoSo(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten_co_so' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        LoaiCoSo::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }
    public function updateLoaiCoSo($id, Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'ten_co_so' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            // dd($data);
            LoaiCoSo::where('id', $id)->update([
                'ten_co_so' => $data['ten_co_so']
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function deleteLoaiCoSo($id)
    {
        try {
            LoaiCoSo::where('id', $id)->delete();
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function nghiDinh155(Request $request)
    {
        $query = DanhMucHanhViViPham::query();
        $query = $query->where('phap_ly', '155/2016/NĐ-CP')->where('trang_thai_dong_bo', 'chua_dong_bo');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where('ma_hanh_vi', 'ilike', "%{$search}%")
                ->orWhere('nhom_hanh_vi', 'ilike', "%{$search}%")
                ->orWhere('ten_hanh_vi', 'ilike', "%{$search}%");
        }
        $query->orderBy('created_at', 'ASC');
        $query->orderBy('id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function nghiDinh155DaDongbo(Request $request)
    {
        $query = DanhMucHanhViViPham::query();
        $query = $query->where('phap_ly', '155/2016/NĐ-CP')->where('trang_thai_dong_bo', 'da_dong_bo');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where(function ($query) use ($search) {
                $query->where('ma_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('nhom_hanh_vi', 'ilike', "%{$search}%")
                    ->orWhere('ten_hanh_vi', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('created_at', 'ASC');
        $query->orderBy('id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function getNghiDinh155(Request $request)
    {
        $query = Muc::with('khoan', 'khoan.dieu')->get();
        foreach ($query as $item) {
            $dieu = $item['khoan']['dieu']['ten'];
            $dieu = substr($dieu, 8);
            $ma_hanh_vi = '155.' . $dieu . '.' . $item['khoan']['ten'] . '.' . $item['ma'];
            $item['ma_hanh_vi'] = $ma_hanh_vi;
            $item['nhom_hanh_vi'] = $item['khoan'] ? ($item['khoan']['dieu'] ? $item['khoan']['dieu']['mo_ta'] : $item['khoan']['mo_ta']) : '';
        }
    }

    public function listXaPhuong(Request $request)
    {
        $query = PhuongXa::query();
        $tinh_thanh_id = $request->get('tinh_thanh_id', null);
        $quan_huyen = $request->get('quan_huyen', null);
        $search = $request->get('search', null);
        if ($tinh_thanh_id) {
        }
        if ($quan_huyen) {
            $query->whereIn('quan_huyen_id', $quan_huyen);
        }
        if ($search) {
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%");
        }
        return $query->get();
    }
    public function getPhuongXa(Request $request)
    {
        $query = PhuongXa::with('quan_huyen', 'quan_huyen.tinh_thanh');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $trangThaiDongBo = $request->get('trang_thai', null);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%");
        }
        if (in_array($trangThaiDongBo, ['da_dong_bo', 'chua_dong_bo'])) {
            $query->where('trang_thai_dong_bo', $trangThaiDongBo);
        }
        $query->orderBy('quan_huyen_id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function getAllPhuongXa(Request $request)
    {
        $query = PhuongXa::query();
        $query->orderBy('id', 'asc');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }

    public function addPhuongXa(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'ma' => 'required',
            'quan_huyen_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = PhuongXa::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function updatePhuongXa(Request $request, $id)
    {
        $info = $request->all();
        $item = PhuongXa::find($id);
        $item->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function deletePhuongXa($id)
    {
        try {
            PhuongXa::where('id', $id)->delete();
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function getQuanHuyen(Request $request)
    {
        $query = QuanHuyen::with('tinh_thanh');
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $trangThaiDongBo = $request->get('trang_thai', null);
        $search = $request->get('search', null);
        if ($search) {
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%");
        }
        if (in_array($trangThaiDongBo, ['da_dong_bo', 'chua_dong_bo'])) {
            $query->where('trang_thai_dong_bo', $trangThaiDongBo);
        }
        $query->orderBy('tinh_thanh_id', 'ASC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function getAllQuanHuyen(Request $request)
    {
        $query = QuanHuyen::query();
        $query->orderBy('id', 'asc');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get(),
        ], 200, []);
    }
    public function updateQuanHuyen(Request $request, $id)
    {
        $info = $request->all();
        $item = \App\QuanHuyen::find($id);
        $item->update($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function addQuanHuyen(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'ma' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = QuanHuyen::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }

    public function deleteQuanHuyen($id)
    {
        $checkKCN = KhuCongNghiep::where('quan_huyen_id', $id)->first();
        if ($checkKCN) {
            return response(['message' => "Không thể xóa, đã có $checkKCN->ten thuộc quận này"], 422);
        }
        $cdt = ChuDauTu::where('quan_huyen_id', $id)->first();
        if ($cdt) {
            return response(['message' => "Không thể xóa, đã có chủ đầu tư $cdt->ten thuộc quận này"], 422);
        }
        $coSo = CoSoSanXuat::where('quan_huyen_id', $id)->first();
        if ($coSo) {
            return response(['message' => "Không thể xóa, đã có cơ sở $coSo->ten thuộc quận này"], 422);
        }
        try {
            QuanHuyen::where('id', $id)->delete();
            return response(['message' => 'Thành công'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function countQDTTchuaDongBo()
    {
        $data = QuyetDinhThanhTra::get();
        $countChuaDongBo = 0;
        $countCapNhatChuaDb = 0;
        foreach ($data as $item) {
            if ($item['thoi_gian_dong_bo'] == null) {
                $countChuaDongBo = $countChuaDongBo + 1;
            } else if (strtotime($item['thoi_gian_dong_bo']) < strtotime($item['updated_at'])) {
                $countCapNhatChuaDb = $countCapNhatChuaDb + 1;
            }
        }
        return response()->json([
            'chuadongbo' => $countChuaDongBo,
            'dongbomoicapnhat' => $countCapNhatChuaDb,
            'total' => $data->count()
        ], 200, []);
    }

    public function getLoaiThongSo()
    {
        $data = LoaiThongSo::get();
        return $data;
    }

    public function updateHanhViViPham(Request $request, $id)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'nhom_hanh_vi' => 'required',
                'ma_hanh_vi' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => __('Lỗi chưa nhập trường bắt buộc'),
                    'result' => $validator->errors(),
                ], 400, []);
            }
            // dd($data);
            DanhMucHanhViViPham::where('id', $id)->update([
                'bien_phap_khac_phuc_hau_qua' => $data["bien_phap_khac_phuc_hau_qua"],
                'dieu_luat' => $data['dieu_luat'],
                'ma_hanh_vi' => $data['ma_hanh_vi'],
                'nghi_dinh_id' => $data['nghi_dinh_id'],
                'nhom_hanh_vi' => $data['nhom_hanh_vi'],
                'phap_ly' => $data['phap_ly'],
                'phat_lon_nhat' => $data['phat_lon_nhat'],
                'phat_nho_nhat' => $data['phat_nho_nhat'],
                'ten_hanh_vi' => $data['ten_hanh_vi'],
                'xu_phat_bo_xung' => $data['xu_phat_bo_xung'],
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function addHanhViViPham(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'nhom_hanh_vi' => 'required|max:255',
            'ma_hanh_vi' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi chưa nhập trường bắt buộc'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $item = DanhMucHanhViViPham::create($info);
        return response()->json([
            'message' => __('message.success'),
            'result' => $item,
        ], 200, []);
    }
}
