<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\ChuyenDoiDonvi;
use App\ChiTietPhatTangThem;
use App\CheDoThanhTra;
use App\CoQuanToChuc;
use App\CoSoSanXuat;
use App\Organization;
use App\HanhViViPham;
use App\KetQuaThanhTra;
use App\KetQuaThanhTraChatThaiNguyHai;
use App\KetQuaThanhTraChatThaiRanCNTT;
use App\KetQuaThanhTraChatThaiRanSinhHoat;
use App\KetQuaThanhTraKhiThai;
use App\KetQuaThanhTraNuocThai;
use App\KetQuaThanhTraThuTucHanhChinh;
use App\KetQuaKhacPhucHauQua;
use App\Khoan;
use App\ChiTietCongSuat;
use App\ChiTietViPhamXaThai;
use App\CoSoSanXuatLoaiHinhONhiem;
use App\HanhViViPhamNew;
use App\KetQuaThanhTraChung;
use App\KetQuaThanhTraChungLoaiHinhHoatDong;
use App\LoaiThuTucHanhChinh;
use App\Muc;
use App\NhomHanhViViPham;
use App\PhieuThuNghiems;
use App\TinhThanh;
use App\QuanHuyen;
use App\QuyetDinhThanhTra;
use App\QuyetDinhXuPhat;
use App\ThongBaoQuyetDinhThanhTra;
use App\Traits\ExecuteString;
use App\Traits\ExecuteExcel;
use App\Traits\GetDataCache;
use App\XuPhatBoSung;
use App\Traits\GeoCode;
use App\KetQuaPhanTichMoiTruong;
use App\KetQuaQuanTrac;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class CongTacThanhTraController extends Controller
{
    use ExecuteString, GetDataCache, ExecuteExcel, GeoCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexDoanThanhTra(Request $request)
    {
        $perPage = $request->get('perpage', 30);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $hinhthuctt = $request->get('hinh_thuc_thanh_tra');
        $coquanqd = $request->get('co_quan_quyet_dinh');
        $coquanth = $request->get('co_quan_thuc_hien');
        $search_start_time = $request->get('search_start_time');
        $search_end_time = $request->get('search_end_time');
        $search_year = $request->get('search_year');
        $search_tinh_thanh = $request->get('search_tinh_thanh');
        $search_mien = $request->get('search_mien');
        $tinhThanhs = $this->getDataByName(\App\TinhThanh::class);
        $query = QuyetDinhThanhTra::query()->with('nguoiCapNhat');
        $toChuc = CoQuanToChuc::take(30)->get();
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('so_quyet_dinh', 'ilike', "%{$search}%");
                $query->orWhere('ma_dinh_danh', 'ilike', "%{$search}%");
            });
        }

        if (isset($search_tinh_thanh)) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($search_tinh_thanh) {
                $query->whereHas('organization', function ($query) use ($search_tinh_thanh) {
                    $query->whereHas('coSoSanXuats', function ($query) use ($search_tinh_thanh) {
                        $query->whereIn('tinh_thanh_id', $search_tinh_thanh);
                    });
                });
            });
        }
        if (isset($search_mien)) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($search_mien, $tinhThanhs) {
                $query->whereHas('organization', function ($query) use ($search_mien, $tinhThanhs) {
                    $query->whereHas('coSoSanXuats', function ($query) use ($search_mien, $tinhThanhs) {
                        $query->whereIn('tinh_thanh_id', $tinhThanhs->whereIn('mien_id', $search_mien)->pluck('id'));
                    });
                });
            });
        }
        if (isset($search_year) && is_numeric($search_year)) {
            $search_start_time = Carbon::create($search_year, 1, 1, 0, 0, 0);
            $search_end_time = Carbon::create($search_year, 1, 1, 23, 59, 59)->endOfYear();
        } else {
            if (isset($search_start_time)) {
                $search_start_time = Carbon::createFromFormat(config('app.format_date'), $search_start_time)->startOfDay();
            } else {
                $search_start_time = Carbon::now()->addYear(-10)->startOfDay();
            }
            if (isset($search_end_time)) {
                $search_end_time = Carbon::createFromFormat(config('app.format_date'), $search_end_time)->endOfDay();
            } else {
                $search_end_time = Carbon::now()->endOfDay();
            }
        }

        $query->where('ngay_ra_quyet_dinh', '>=', $search_start_time);
        $query->where('ngay_ra_quyet_dinh', '<=', $search_end_time);

        if (isset($hinhthuctt)) {

            $query->where('hinh_thuc_thanh_tra', $hinhthuctt);
        }
        if (isset($coquanqd)) {
            $query->where('co_quan_quyet_dinh', $coquanqd);
        }
        if (isset($coquanth)) {
            $query->where('co_quan_thuc_hien', $coquanth);
        }

        $query->with('coQuanQuyetDinh', 'hinhThucThanhTra', 'nguoiCapNhat');

        $query->orderBy('ngay_ra_quyet_dinh', 'desc');

        if ($request->ajax()) {
            return response()->json([
                'data' => $data,
                'search' => $search,
                'perPage' => $perPage,
                'hinhthuc' => $this->getDataByName(\App\CheDoThanhTra::class),
                'hinh_thuc_thanh_tra' => $hinhthuctt,
                'co_quan_quyet_dinh' => $coquanqd,
                'co_quan_thuc_hien' => $coquanth,
                'coquantochuc' => $toChuc,
            ], 200, []);
        }
        if ($request->headers->get('accept') != 'xlsx') {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            return view('thanhtra.doanthanhtra.index', [
                'data' => $data,
                'search' => $search,
                'search_year' => $search_year,
                'perPage' => $perPage,
                'hinhthuc' => $this->getDataByName(\App\CheDoThanhTra::class),
                'hinh_thuc_thanh_tra' => $hinhthuctt,
                'co_quan_quyet_dinh' => $coquanqd,
                'co_quan_thuc_hien' => $coquanth,
                'search_start_time' => $search_start_time->format(config('app.format_date')),
                'search_end_time' => $search_end_time->format(config('app.format_date')),
                'coquantochuc' => $toChuc,
                'tinhthanhs' => $tinhThanhs,
                'search_tinh_thanh' => $search_tinh_thanh,
                'miens' => \App\Mien::query()->get(),
                'search_mien' => $search_mien
            ]);
        } else {
            $data = $query->get();
            $input = $request->all();
            $excelFile = public_path() . '/report/danh_sach_doan_thanh_tra.xlsx';

            $this->load($excelFile, 'Sheet1', function ($sheet) use (&$data, &$input) {
                foreach ($data as $key => $doanthanhtra) {
                    $rowKey = (int)$key;
                    $sheet->row($rowKey + 4, [
                        $rowKey + 1,
                        $doanthanhtra->so_quyet_dinh,
                        isset($doanthanhtra->ngay_ra_quyet_dinh) ? $doanthanhtra->ngay_ra_quyet_dinh : '',
                        isset($doanthanhtra->co_quan_quyet_dinh) ? $doanthanhtra->coQuanQuyetDinh->ten : '',
                        isset($doanthanhtra->hinh_thuc_thanh_tra) ? $doanthanhtra->hinhThucThanhTra->ten : '',
                    ]);
                }
            })->download('xlsx');
        }
    }

    public function exportExcel($id)
    {
        $user = Auth::user();
        // $data = QuyetDinhThanhTra::query()->where('id', $id)->with('hinhThucThanhTra', 'coQuanQuyetDinh', 'coQuanThucHien', 'coQuanThongBao')->first();
        $data = QuyetDinhThanhTra::query()->where('id', $id)->with('hinhThucThanhTra', 'coQuanQuyetDinh', 'coQuanThucHien')->first();
        $ketquathanhtra = KetQuaThanhTra::where('quyet_dinh_thanh_tra_id', $id)->get();
        $tochuc = Organization::whereIn('id', $ketquathanhtra->pluck('organization_id'))->paginate(30);

        $excelFile = public_path() . '/report/chi_tiet_quyet_dinh_thanh_tra_new.xlsx';
        $this->load($excelFile, 'Sheet1', function ($sheet) use (&$user, $data, $tochuc) {
            $this->setValue($sheet, 'B3', $data->so_quyet_dinh);
            $this->setValue($sheet, 'B4', $data->coQuanQuyetDinh->ten);
            $this->setValue($sheet, 'B5', $data->coQuanThucHien->ten);
            $this->setValue($sheet, 'B6', $data->hinhThucThanhTra->ten);
            $this->setValue($sheet, 'B7', $data->ngay_ra_quyet_dinh);
            $this->setValue($sheet, 'B8', $data->thoi_gian_thong_bao);

            foreach ($tochuc as $key => $it) {
                $this->setValue($sheet, 'A' . (11 + $key), $key + 1);
                $this->setValue($sheet, 'B' . (11 + $key), isset($it->ten) ? $it->ten . ' ' : '');
                $this->setValue($sheet, 'C' . (11 + $key), isset($it->dia_chi_lien_he) ? $it->dia_chi_lien_he . ' ' : '');
            }
        })->download('xlsx');
    }

    public function asyncdoanthanhtra(Request $request)
    {
        $search = $request->get('search');
        $query = QuyetDinhThanhTra::query();
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('so_quyet_dinh', 'ilike', "%{$search}%");
            });
        }
        $query->select('id', 'ngay_ra_quyet_dinh', 'so_quyet_dinh');
        $query->orderBy('ngay_ra_quyet_dinh', 'desc');

        return response()->json([
            'message' => __('message.success'),
            'result' => $query->get()
        ], 200, []);
    }

    public function addDoanThanhTra(Request $request)
    {
        $data = $request->get('thanhtra');
        $info = $request->get('data');
        $attachments = $request->get('attachments');
        $user = Auth::user();
        $dates = QuyetDinhThanhTra::select('ngay_ra_quyet_dinh')->whereSoQuyetDinh($data['so_quyet_dinh'])->get();
        $getyear = [];
        foreach ($dates as $key => $date) {
            $getyear[$key] = Carbon::createFromFormat('d/m/Y', $date['ngay_ra_quyet_dinh'])->year;
        }

        $validator = Validator::make($data, [
            'so_quyet_dinh' => 'required|max:1000',
            'co_quan_quyet_dinh' => 'required',
            'ma_dinh_danh' => 'required',
            'nam_ke_hoach' => 'required',
            'ten' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.create_error'));
        }

        DB::beginTransaction();
        try {
            $data['nguoi_tao'] = $user->id;
            $data['nguoi_cap_nhat'] = $user->id;

            if (empty($getyear) || (!empty($getyear) && !(in_array(Carbon::createFromFormat('d/m/Y', $data['ngay_ra_quyet_dinh'])->year, $getyear)))) {
                $quyetdinh = QuyetDinhThanhTra::query()->create($data);
                if (!empty($attachments)) {
                    foreach ($attachments as $attachment) {
                        Attachment::where('id', $attachment['id'])
                            ->update(['reference_id' => $quyetdinh->id, 'reference_type' => 'quyet_dinh_thanh_tra']);
                    }
                }
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'message' => __('Thành công'),
                    'result' => [],
                ], 200);
            } else {
                return response()->json([
                    'code' => 500,
                    'message' => __('Đã tồn tại năm'),
                ], 500);
            }
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function showAddDoanThanhTra(Request $request)
    {
        $user = Auth::user();

        return view('thanhtra.doanthanhtra.create', [
            'menus' => $this->getMenusForUser($user),
            'chedothanhtras' => CheDoThanhTra::query()->get(),
        ]);
    }

    public function indexCheDoThanhTra(Request $request)
    {

        return response()->json([
            'message' => __('message.success'),
            'result' => CheDoThanhTra::query()->get(),
        ], 200, []);
    }

    public function showUpdateDoanThanhTra($id)
    {
        $data = QuyetDinhThanhTra::query()->where('id', $id)->with('attachments')->first();
        $ketquathanhtra = KetQuaThanhTra::where('quyet_dinh_thanh_tra_id', $id)->get();

        $tochuc = Organization::whereIn('id', $ketquathanhtra->pluck('organization_id'))->with('coSoSanXuats', 'chuDauTu')->paginate(30);

        return view('thanhtra.doanthanhtra.edit', [
            'data' => $data,
            'chitiet' => $tochuc,
            'chedothanhtras' => CheDoThanhTra::query()->get(),
        ]);
    }

    public function showCoSoThanhTra($id)
    {

        $ketquathanhtra = KetQuaThanhTra::where('quyet_dinh_thanh_tra_id', $id)->get();
        $tochuc = Organization::whereIn('id', $ketquathanhtra->pluck('organization_id'))->paginate(30);

        return response()->json([
            'message' => 'Thành công',
            'result' => $tochuc,
        ], 200, []);
    }

    public function updateDoanThanhTra(Request $request, $id)
    {
        $data = $request->get('thanhtra');
        $info = $request->get('data');
        $temp = $request->get('temp');
        $attachments = $request->get('attachments');
        $user = Auth::user();
        $validator = Validator::make($data, [
            'so_quyet_dinh' => 'required|max:1000',
            'co_quan_quyet_dinh' => 'required',
            'co_quan_thuc_hien' => 'required',
            'hinh_thuc_thanh_tra' => 'required',
            'ma_dinh_danh' => 'required',
            'nam_ke_hoach' => 'required',
            'ten' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.create_error'));
        }
        DB::beginTransaction();
        try {
            $data['nguoi_cap_nhat'] = $user->id;
            $quyetdinh = QuyetDinhThanhTra::query()->where('id', $id)->first();
            if (!empty($data)) {

                $quyetdinh->update([
                    'ten' => $data['ten'],
                    'so_quyet_dinh' => $data['so_quyet_dinh'],
                    'ma_dinh_danh' => $data['ma_dinh_danh'],
                    'nam_ke_hoach' => $data['nam_ke_hoach'],
                    'co_quan_quyet_dinh' => $data['co_quan_quyet_dinh'],
                    'ngay_ra_quyet_dinh' => $data['ngay_ra_quyet_dinh'],
                    'thoi_gian_thong_bao' => $data['thoi_gian_thong_bao'],
                    'co_quan_thuc_hien' => $data['co_quan_thuc_hien'],
                    'hinh_thuc_thanh_tra' => $data['hinh_thuc_thanh_tra'],
                    'nguoi_cap_nhat' => $data['nguoi_cap_nhat'],
                ]);
            }

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    Attachment::where('id', $attachment['id'])
                        ->update(['reference_id' => $id, 'reference_type' => 'quyet_dinh_thanh_tra']);
                }
            }

            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => __('Thành công'),
                'result' => [],
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            dd($exception);
            return response()->json([
                'code' => 500,
                'message' => $exception,
            ], 500);
        }
    }

    public function showFromAddKetQuaThanhTra()
    {
        return view('ketquathanhtra.create');
    }

    public function showFromEditKetQuaThanhTra(Request $request, $id)
    {
        $ketquathanhtra = KetQuaThanhTra::find($id);
        $thutuchanhchinhs = KetQuaThanhTraThuTucHanhChinh::get();
        $thutuc = [];
        $loaithutucs = $this->getDataByName(LoaiThuTucHanhChinh::class);

        $ketquathanhtra = KetQuaThanhTra::query()->with(
            'attachments',
            'quyetDinhThanhTra',
            'quyetDinhThanhTra.coQuanQuyetDinh',
            'organization.chuDauTu',
            'organization.chuDauTu.tinhThanh',
            'organization.chuDauTu.quanHuyen',
            'ketQuaThanhTraChungs.coSoSanXuat',
            'ketQuaThanhTraChungs.coSoSanXuat.loaiKhuCongNghiep',
            'ketQuaThanhTraChungs.coSoSanXuat.loaiHinhONhiem',
            'ketQuaThanhTraChungs.coSoSanXuat.loaiNganhNghe',
            'ketQuaThanhTraChungs.coSoSanXuat.phuongXas',
            'ketQuaThanhTraChungs.coSoSanXuat.quanHuyens',
            'ketQuaThanhTraChungs.coSoSanXuat.tinhThanhs',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.loaiHinhCoSo',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.donViHoatDong',
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.donViThietKe',
            'ketQuaThanhTraChungs.ketQuaThanhTraNuocThais',
            'ketQuaThanhTraChungs.ketQuaThanhTraNuocThais.phieuThuNghiem',
            'ketQuaThanhTraChungs.ketQuaThanhTraNuocThais.phieuThuNghiem.attachmentNuocThai',
            'ketQuaThanhTraChungs.ketQuaThanhTraKhiThais',
            'ketQuaThanhTraChungs.ketQuaThanhTraKhiThais.phieuThuNghiem',
            'ketQuaThanhTraChungs.ketQuaThanhTraKhiThais.phieuThuNghiem.attachmentKhiThai',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanSinhHoats',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanSinhHoats.phieuThuNghiem',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanCNTT',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanCNTT.phieuThuNghiem',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiNguyHai',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiNguyHai.phieuThuNghiem',
            'ketQuaThanhTraChungs.ketQuaPhanTichMoiTruong',
            'ketQuaThanhTraChungs.ketQuaPhanTichMoiTruong.attachment',
            'ketQuaThanhTraChungs.khuCongNghiep',
            'ketQuaThanhTraChungs.tinhThanh',
            'ketQuaThanhTraChungs.quanHuyen',
            'ketQuaThanhTraChungs.coQuanDauTu',
            'ketQuaThanhTraChungs.coQuanKinhDoanh',
            'ketQuaThanhTraChungs.chuyenDoiDonVi',
            'ketQuaThanhTraChungs.ketQuaQuanTrac',
            'ketQuaThanhTraChungs.ketQuaQuanTrac.attachment',
            // 'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.dieu',
            // 'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.khoan',
            // 'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.muc',
            'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.hanhVi',
            'quyetDinhXuPhats.coQuanQuyetDinhXuPhat',
            'quyetDinhXuPhats.xuPhatBoSung.chiTietXuPhatBoSungs.loaiXuPhatBoSung',
            'quyetDinhXuPhats.bienPhapKhacPhucHauQua.chiTietBienPhapKhacPhucHauQuas.cacBienPhapKhacPhucHauQua',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.chiTietPhatTangThems.thongSo',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.chiTietPhatTangThems.donVi',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.chiTietPhatTangThems.phatTangThem',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.coSoSanXuat',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.thongSo',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.donVi',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.khoan',
            'nhomHanhViViPhams.viPhamXaThaiKhiThais.muc',

            'nhomHanhViViPhams.viPhamXaThaiNuocThais.chiTietPhatTangThems.thongSo',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.chiTietPhatTangThems.donVi',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.chiTietPhatTangThems.phatTangThem',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.coSoSanXuat',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.thongSo',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.donVi',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.khoan',
            'nhomHanhViViPhams.viPhamXaThaiNuocThais.muc',
            'ketQuaKhacPhucHauQuas',
        )->findOrFail($id);
        // dd($ketquathanhtra);
        foreach ($ketquathanhtra->ketQuaThanhTraChungs as $item1) {
            $listThuTuc = KetQuaThanhTraThuTucHanhChinh::with(['loaiThuTuc', 'coQuanToChuc'])->where('ket_qua_thanh_tra_chung_id', $item1->id)->get()->toArray();
            foreach ($loaithutucs as $loaithutuc) {
                $check = false;

                foreach ($thutuchanhchinhs->where('ket_qua_thanh_tra_chung_id', $item1->id)->groupBy('loai_thu_tuc_hanh_chinh_id') as $key => $item) {
                    if ($loaithutuc->id == $key) {
                        $check = true;
                        foreach ($item as $key1 => $it) {
                            $thutuc[$loaithutuc->ma][$key1]['so_quyet_dinh'] = $it->so_quyet_dinh_phe_duyet;
                            $thutuc[$loaithutuc->ma][$key1]['co_quan_quyet_dinh'] = $it->co_quan_phe_duyet_id;
                            $thutuc[$loaithutuc->ma][$key1]['co_quan_quyet_dinh'] = $it->coQuanToChuc;
                            $thutuc[$loaithutuc->ma][$key1]['thoi_gian_phe_duyet'] = $it->thoi_gian_phe_duyet;
                            $thutuc[$loaithutuc->ma][$key1]['ghi_chu'] = $it->ghi_chu;
                        }
                    }
                }
                if ($check == false) {
                    $thutuc[$loaithutuc->ma] = [];
                }
            }
            $item1['thutuchanhchinhs'] = $thutuc;
            $item1['danhsachthutuchanhchinh'] =  $listThuTuc;
        }
        // dd($ketquathanhtra, $thutuc);
        return view('ketquathanhtra.edit', [
            'ketquathanhtra' => $ketquathanhtra,
            'thutuc' => $thutuc,
        ]);
    }

    public function addKetQuaThanhTra(Request $request)
    {
        $info = $request->get('data');
        $info['id'] = $request->get('id');
        $validator = Validator::make($info, [
            'co_so_san_xuats' => 'required|array',
            'co_so_san_xuats.*.ten' => 'required',
            // 'co_so_san_xuats.*.dia_chi_hoat_dong' => 'required',
            // 'co_so_san_xuats.*.chi_tiet_cong_suat' => 'required|array',
            'quyet_dinh_thanh_tra' => 'required',
            'so_quyet_dinh' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('system.invalid'),
                'result' => $validator->errors(),
            ], 500, []);
        }

        $user = Auth::user();
        $new_co_so_San_xuat_ids = [];
        $info['nguoi_cap_nhat'] = $user->id;
        if (empty($info['ngay_ket_luan'])) {
            $info['ngay_thanh_tra'] = Carbon::now();
        } else {
            $info['ngay_thanh_tra'] = $info['ngay_ket_luan'];
        }
        DB::beginTransaction();
        try {
            $co_so_san_xuats = $info['co_so_san_xuats'];
            if (empty($info['id'])) {
                if (count($co_so_san_xuats) > 0) {
                    $coso = $co_so_san_xuats[0];
                    if (!empty($coso['ten'])) {
                        $organization = \App\Organization::create([
                            'ten' => $coso['ten'],
                            'dia_chi_lien_he' => empty($coso['dia_chi_lien_he']) ? $coso['dia_chi_hoat_dong'] : $coso['dia_chi_lien_he'],
                            'created_by' => $user->id,
                            'updated_by' => $user->id
                        ]);
                    }
                } else {
                    return response()->json([
                        'message' => 'Chưa nhập cơ sở sản xuất',
                        'result' => [],
                    ], 500, []);
                }
            } else {
                $organization = \App\Organization::query()->find($info['id']);
            }
            if ($organization) {
                $organization->update(['chu_dau_tu_id' => $info['chu_dau_tu_id'] ?? null]);
            }

            $info['organization_id'] = $organization->id;
            $info['quyet_dinh_thanh_tra_id'] = $info['quyet_dinh_thanh_tra']['id'];
            $ketquathanhtra = \App\KetQuaThanhTra::create(Arr::except($info, ['id']));

            foreach ($co_so_san_xuats as $co_so_san_xuat) {
                // --- Chuẩn hóa dữ liệu cơ bản ---
                $tinh_id = data_get($co_so_san_xuat, 'tinh_thanh.id')
                    ?? data_get($co_so_san_xuat, 'tinh_thanhs.0.id')
                    ?? data_get($co_so_san_xuat, 'tinh_thanh_id');

                $tinh = \App\TinhThanh::find($tinh_id);
                $co_so_san_xuat['vung_kinh_te_trong_diem_id'] = $tinh->vung_kinh_te_trong_diem_id ?? null;
                $co_so_san_xuat['mien_id'] = $tinh->mien_id ?? null;
                $co_so_san_xuat['luu_vuc_song_id'] = $tinh->luu_vuc_song_id ?? null;
                $co_so_san_xuat['organization_id'] = $organization->id;
                $co_so_san_xuat['created_by'] = $user->id;
                $co_so_san_xuat['updated_by'] = $user->id;

                // --- Ngày kết luận nằm trong bảng CoSoSanXuat ---
                $co_so_san_xuat['ngay_ket_luan'] = !empty($info['ngay_ket_luan'])
                    ? $info['ngay_ket_luan']
                    : Carbon::now();

                // --- Mã riêng cho snapshot ---
                $co_so_san_xuat['ma'] = ($tinh->ma ?? '00') . '-' . Str::random(6);

                // --- Quy đổi đơn vị ---
                if (!empty($co_so_san_xuat['don_vi_dien_tich'])) {
                    $co_so_san_xuat['dien_tich'] =
                        $co_so_san_xuat['don_vi_dien_tich']['ty_le'] * floatval($co_so_san_xuat['dien_tich'] ?? 0);
                }
                if (!empty($co_so_san_xuat['don_vi_nuoc_su_dung'])) {
                    $co_so_san_xuat['luong_nuoc_su_dung'] =
                        $co_so_san_xuat['don_vi_nuoc_su_dung']['ty_le'] * floatval($co_so_san_xuat['luong_nuoc_su_dung'] ?? 0);
                }

                // --- Tính lại tọa độ nếu trống ---
                if (empty($co_so_san_xuat['kinh_do']) || empty($co_so_san_xuat['vi_do'])) {
                    $latlon = $this->getLatLonByAddressText($co_so_san_xuat['dia_chi_hoat_dong'], false);
                    if (!empty($latlon['long']) && !empty($latlon['lat'])) {
                        $co_so_san_xuat['kinh_do'] = $latlon['long'];
                        $co_so_san_xuat['vi_do'] = $latlon['lat'];
                    }
                }

                // --- Làm phẳng dữ liệu quan hệ ---
                $co_so_san_xuat['khu_cong_nghiep_id'] = data_get($co_so_san_xuat, 'khu_cong_nghiep.0.id')
                    ?? data_get($co_so_san_xuat, 'khu_cong_nghiep.id')
                    ?? data_get($co_so_san_xuat, 'khu_cong_nghiep_id');

                $co_so_san_xuat['quan_huyen_id'] = data_get($co_so_san_xuat, 'quan_huyen.id')
                    ?? data_get($co_so_san_xuat, 'quan_huyens.0.id')
                    ?? data_get($co_so_san_xuat, 'quan_huyen_id');

                $co_so_san_xuat['tinh_thanh_id'] = $tinh_id;

                $co_so_san_xuat['loai_khu_cong_nghiep'] = data_get($co_so_san_xuat, 'loai_khu_cong_nghiep.ma')
                    ?? null;

                // --- Tạo mới cơ sở sản xuất (snapshot riêng cho lần thanh tra này) ---
                $old_id = $co_so_san_xuat['id'] ?? null;
                unset($co_so_san_xuat['id']);
                $newCoso = \App\CoSoSanXuat::create(array_filter($co_so_san_xuat, fn($v) => !is_array($v)));
                if ($old_id) {
                    $new_co_so_San_xuat_ids[$old_id] = $newCoso->id;
                }
                // $new_co_so_San_xuat_ids[$co_so_san_xuat['id']] = $newCoso->id;

                // --- Gắn quan hệ nhiều-nhiều ---
                if (!empty($co_so_san_xuat['loai_hinh_o_nhiem'])) {
                    $newCoso->loaiHinhONhiem()->sync(
                        collect($co_so_san_xuat['loai_hinh_o_nhiem'])->pluck('id')->toArray()
                    );
                }

                if (!empty($co_so_san_xuat['loai_nganh_nghe'])) {
                    $newCoso->loaiNganhNghe()->sync(
                        collect($co_so_san_xuat['loai_nganh_nghe'])->pluck('id')->toArray()
                    );
                }

                if (!empty($co_so_san_xuat['tinh_thanhs'])) {
                    $newCoso->tinhThanhs()->sync(
                        collect($co_so_san_xuat['tinh_thanhs'])->pluck('id')->toArray()
                    );
                }

                if (!empty($co_so_san_xuat['quan_huyens'])) {
                    $newCoso->quanHuyens()->sync(
                        collect($co_so_san_xuat['quan_huyens'])->pluck('id')->toArray()
                    );
                }

                if (!empty($co_so_san_xuat['phuong_xas'])) {
                    $newCoso->phuongXas()->sync(
                        collect($co_so_san_xuat['phuong_xas'])->pluck('id')->toArray()
                    );
                }

                // --- Gắn chi tiết công suất ---
                ChiTietCongSuat::where('co_so_san_xuat_id', $newCoso->id)->delete();
                if (!empty($co_so_san_xuat['chi_tiet_cong_suat'])) {
                    foreach ($co_so_san_xuat['chi_tiet_cong_suat'] as $item) {
                        ChiTietCongSuat::create([
                            'co_so_san_xuat_id' => $newCoso->id,
                            'don_vi_id' => data_get($item, 'don_vi_thiet_ke.id'),
                            'thong_so' => data_get($item, 'thong_so_thiet_ke'),
                            'loai_hinh_id' => data_get($item, 'loai_hinh_co_so.id'),
                            'don_vi_hoat_dong_id' => data_get($item, 'don_vi_hoat_dong.id'),
                            'thong_so_hoat_dong' => data_get($item, 'thong_so_hoat_dong'),
                            'ghi_chu' => data_get($item, 'ghi_chu'),
                        ]);
                    }
                }


                // --- Gắn kết quả thanh tra chung ---
                $info['co_so_san_xuat_id'] = $newCoso->id;
                $ketquathanhtrachung = KetQuaThanhTraChung::create([
                    'ket_qua_thanh_tra_id' => $ketquathanhtra->id,
                    'co_so_san_xuat_id' => $newCoso->id,
                    'ten' => $newCoso->ten,
                    'kinh_do' => $newCoso->kinh_do,
                    'vi_do' => $newCoso->vi_do,
                    'dia_chi_lien_he' => $newCoso->dia_chi_lien_he,
                    'khu_cong_nghiep_id' => $newCoso->khu_cong_nghiep_id,
                    'xa_phuong' => $newCoso->xa_phuong,
                    'quan_huyen_id' => $newCoso->quan_huyen_id,
                    'tinh_thanh_id' => $newCoso->tinh_thanh_id,
                    'dien_tich' => $newCoso->dien_tich,
                    'so_nguoi_lao_dong' => $newCoso->so_nguoi_lao_dong,
                    'so_giay_chung_nhan_dau_tu' => $newCoso->so_giay_chung_nhan_dau_tu,
                    'ngay_cap_giay_chung_nhan_dau_tu' => $newCoso->ngay_cap_giay_chung_nhan_dau_tu,
                    'co_quan_cap_giay_chung_nhan_dau_tu' => $newCoso->co_quan_cap_giay_chung_nhan_dau_tu,
                    'nguyen_lieu_chinh_su_dung' => $newCoso->nguyen_lieu_chinh_su_dung,
                    'nhien_lieu_chinh_su_dung' => $newCoso->nhien_lieu_chinh_su_dung,
                    'hoa_chat_su_dung' => $newCoso->hoa_chat_su_dung,
                    'nguon_nuoc_su_dung' => $newCoso->nguon_nuoc_su_dung,
                    'vung_kinh_te_trong_diem_id' => $newCoso->vung_kinh_te_trong_diem_id,
                    'mien_id' => $newCoso->mien_id,
                    'luu_vuc_song_id' => $newCoso->luu_vuc_song_id,
                    'ngay_cap_giay_chung_nhan_kinh_doanh' => $newCoso->ngay_cap_giay_chung_nhan_kinh_doanh,
                    'so_giay_chung_nhan_kinh_doanh' => $newCoso->so_giay_chung_nhan_kinh_doanh,
                    'co_quan_cap_giay_chung_nhan_kinh_doanh' => $newCoso->co_quan_cap_giay_chung_nhan_kinh_doanh,
                    'luong_nuoc_su_dung' => $newCoso->luong_nuoc_su_dung,
                    'dia_chi_hoat_dong' => $newCoso->dia_chi_hoat_dong,
                    'ty_le_lap_day' => $newCoso->ty_le_lap_day,
                    'tong_dien_tich' => $newCoso->tong_dien_tich,
                    'chuyen_doi_don_vi_id' => $newCoso->chuyen_doi_don_vi_id,
                    'dien_tich_dat_cn' => $newCoso->dien_tich_dat_cn,
                    'dien_tich_dat_cho_thue' => $newCoso->dien_tich_dat_cho_thue,
                    'dien_tich_dat_cay_xanh' => $newCoso->dien_tich_dat_cay_xanh
                ]);

                // --- Gắn loại hình hoạt động cho KQ thanh tra chung ---
                if (!empty($co_so_san_xuat['chi_tiet_cong_suat'])) {
                    foreach ($co_so_san_xuat['chi_tiet_cong_suat'] as $item) {
                        \App\KetQuaThanhTraChungLoaiHinhHoatDong::create([
                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                            'don_vi_thiet_ke_id' => data_get($item, 'don_vi_thiet_ke.id'),
                            'thong_so_thiet_ke' => data_get($item, 'thong_so_thiet_ke'),
                            'loai_hinh_co_so_id' => data_get($item, 'loai_hinh_co_so.id'),
                            'don_vi_hoat_dong_id' => data_get($item, 'don_vi_hoat_dong.id'),
                            'thong_so_hoat_dong' => data_get($item, 'thong_so_hoat_dong'),
                            'ghi_chu' => data_get($item, 'ghi_chu'),
                        ]);
                    }
                }


                // --- Thêm dữ liệu chất thải (nếu có) ---
                if (!empty($co_so_san_xuat['chatthais'])) {
                    $phieu_thu_nghiem_id = null;
                    $phieu_thu_nghiem_id_KT = null;
                    $phieu_thu_nghiem_id_CTRSH = null;
                    $phieu_thu_nghiem_id_CTRCNTT = null;
                    $phieu_thu_nghiem_id_CTNH = null;

                    // --- Nước thải ---
                    if (!empty($co_so_san_xuat['chatthais']['nuocthai']['ket_quas'])) {
                        foreach ($co_so_san_xuat['chatthais']['nuocthai']['ket_quas'] as $nt) {
                            \App\KetQuaThanhTraNuocThai::create([
                                'luu_luong' => data_get($nt, 'luu_luong'),
                                'thanh_phan' => data_get($nt, 'thanh_phan'),
                                'cong_suat_he_thong_xu_ly' => data_get($nt, 'cong_suat_he_thong_xu_ly'),
                                'nguon_tiep_nhan' => data_get($nt, 'nguon_tiep_nhan'),
                                'thong_so_nuoc_thai_vuot_chuan' => data_get($nt, 'thong_so_nuoc_thai_vuot_chuan'),
                                'co_so_san_xuat_id' => $newCoso->id,
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra']['id'],
                                'so_ket_luan' => trim($info['so_quyet_dinh']),
                                'ngay_ket_luan_thanh_tra' => $newCoso->ngay_ket_luan,
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id,
                                'loai_hinh_quan_trac' => data_get($nt, 'loai_hinh_quan_trac.id'),
                            ]);
                        }

                        // --- Kết quả quan trắc của nước thải ---
                        if (!empty($co_so_san_xuat['chatthais']['nuocthai']['ket_qua_quan_trac'])) {
                            foreach ($co_so_san_xuat['chatthais']['nuocthai']['ket_qua_quan_trac'] as $kqqt) {
                                if (empty($kqqt['mau_moi_truong']) || !is_array($kqqt['mau_moi_truong'])) continue;

                                foreach ($kqqt['mau_moi_truong'] as $mau) {
                                    if (empty($mau['thong_sos'])) continue;

                                    $attachment = data_get($mau, 'attachment');

                                    foreach ($mau['thong_sos'] as $index => $ts) {
                                        $kq = \App\KetQuaQuanTrac::create([
                                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                            'loai_moi_truong' => $kqqt['loai_moi_truong'] ?? null,
                                            'dia_diem' => $mau['dia_diem'] ?? null,
                                            'vi_tri' => $mau['vi_tri'] ?? null,
                                            'loai_mau' => $mau['loai_mau'] ?? null,
                                            'kinh_do' => $mau['kinh_do'] ?? null,
                                            'vi_do' => $mau['vi_do'] ?? null,
                                            'thong_so' => data_get($ts, 'thong_so'),
                                            'don_vi_tinh' => data_get($ts, 'don_vi_tinh'),
                                            'phuong_phap_phan_tich' => data_get($ts, 'phuong_phap_phan_tich'),
                                            'ket_qua' => data_get($ts, 'ket_qua'),
                                            'gia_tri_gioi_han' => data_get($ts, 'gia_tri_gioi_han'),
                                            'so_lan_vuot' => data_get($ts, 'so_lan_vuot'),
                                            'tep_pdf' => data_get($attachment, 'link'),
                                        ]);

                                        // update file PDF vào dòng đầu tiên
                                        if ($index === 0 && !empty($attachment['id'])) {
                                            Attachment::where('id', $attachment['id'])->update([
                                                'reference_id' => $kq->id,
                                                'reference_type' => 'ket_qua_quan_trac',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // --- Khí thải ---
                    if (!empty($co_so_san_xuat['chatthais']['khithai']['ket_quas'])) {
                        foreach ($co_so_san_xuat['chatthais']['khithai']['ket_quas'] as $kt) {
                            \App\KetQuaThanhTraKhiThai::create([
                                'luu_luong' => data_get($kt, 'luu_luong'),
                                'thanh_phan' => data_get($kt, 'thanh_phan'),
                                'cong_suat_he_thong_xu_ly' => data_get($kt, 'cong_suat_he_thong_xu_ly'),
                                'nguon_tiep_nhan' => data_get($kt, 'nguon_tiep_nhan'),
                                'thong_so_khi_thai_vuot_chuan' => data_get($kt, 'thong_so_nuoc_thai_vuot_chuan'),
                                'quy_trinh_xu_ly' => data_get($kt, 'quy_trinh_xu_ly'),
                                'co_so_san_xuat_id' => $newCoso->id,
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra']['id'],
                                'so_ket_luan' => trim($info['so_quyet_dinh']),
                                'ngay_ket_luan_thanh_tra' => $newCoso->ngay_ket_luan,
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id_KT,
                                'loai_hinh_quan_trac' => data_get($kt, 'loai_hinh_quan_trac.id'),
                            ]);
                        }

                        // --- KQ QUAN TRẮC KHÍ THẢI ---
                        if (!empty($co_so_san_xuat['chatthais']['khithai']['ket_qua_quan_trac'])) {
                            foreach ($co_so_san_xuat['chatthais']['khithai']['ket_qua_quan_trac'] as $kqqt) {
                                if (empty($kqqt['mau_moi_truong']) || !is_array($kqqt['mau_moi_truong'])) continue;

                                foreach ($kqqt['mau_moi_truong'] as $mau) {
                                    if (empty($mau['thong_sos'])) continue;

                                    $attachment = data_get($mau, 'attachment');

                                    foreach ($mau['thong_sos'] as $index => $ts) {
                                        $kq = \App\KetQuaQuanTrac::create([
                                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                            'loai_moi_truong' => $kqqt['loai_moi_truong'] ?? null,
                                            'dia_diem' => $mau['dia_diem'] ?? null,
                                            'vi_tri' => $mau['vi_tri'] ?? null,
                                            'loai_mau' => $mau['loai_mau'] ?? null,
                                            'kinh_do' => $mau['kinh_do'] ?? null,
                                            'vi_do' => $mau['vi_do'] ?? null,
                                            'thong_so' => data_get($ts, 'thong_so'),
                                            'don_vi_tinh' => data_get($ts, 'don_vi_tinh'),
                                            'phuong_phap_phan_tich' => data_get($ts, 'phuong_phap_phan_tich'),
                                            'ket_qua' => data_get($ts, 'ket_qua'),
                                            'gia_tri_gioi_han' => data_get($ts, 'gia_tri_gioi_han'),
                                            'so_lan_vuot' => data_get($ts, 'so_lan_vuot'),
                                            'tep_pdf' => data_get($attachment, 'link'),
                                        ]);

                                        // update file PDF vào dòng đầu tiên
                                        if ($index === 0 && !empty($attachment['id'])) {
                                            Attachment::where('id', $attachment['id'])->update([
                                                'reference_id' => $kq->id,
                                                'reference_type' => 'ket_qua_quan_trac',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // --- Chất thải rắn công nghiệp thông thường (CTRCNTT) ---
                    if (!empty($co_so_san_xuat['chatthais']['ctrcntt']['ket_quas'])) {
                        foreach ($co_so_san_xuat['chatthais']['ctrcntt']['ket_quas'] as $nt) {
                            \App\KetQuaThanhTraChatThaiRanCNTT::create([
                                'khoi_luong_phat_sinh' => (float) data_get($nt, 'khoi_luong_phat_sinh'),
                                'thanh_phan_chinh' => data_get($nt, 'thanh_phan_chinh'),
                                'tu_xu_ly' => data_get($nt, 'tu_xu_ly_ctrcntt'),
                                'thue_xu_ly' => data_get($nt, 'thue_xu_ly_ctrcntt'),
                                'co_quan_thue_xu_ly' => data_get($nt, 'co_quan_thue_xu_ly_ctrcntt'),
                                'co_so_san_xuat_id' => $newCoso->id,
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra']['id'],
                                'so_ket_luan' => trim($info['so_quyet_dinh']),
                                'ngay_ket_luan_thanh_tra' => $newCoso->ngay_ket_luan,
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id_CTRCNTT,
                            ]);
                        }

                        // --- KQ QUAN TRẮC CTRCNTT ---
                        if (!empty($co_so_san_xuat['chatthais']['ctrcntt']['ket_qua_quan_trac'])) {
                            foreach ($co_so_san_xuat['chatthais']['ctrcntt']['ket_qua_quan_trac'] as $kqqt) {
                                if (empty($kqqt['mau_moi_truong']) || !is_array($kqqt['mau_moi_truong'])) continue;

                                foreach ($kqqt['mau_moi_truong'] as $mau) {
                                    if (empty($mau['thong_sos'])) continue;

                                    $attachment = data_get($mau, 'attachment');

                                    foreach ($mau['thong_sos'] as $index => $ts) {
                                        $kq = \App\KetQuaQuanTrac::create([
                                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                            'loai_moi_truong' => $kqqt['loai_moi_truong'] ?? null,
                                            'dia_diem' => $mau['dia_diem'] ?? null,
                                            'vi_tri' => $mau['vi_tri'] ?? null,
                                            'loai_mau' => $mau['loai_mau'] ?? null,
                                            'kinh_do' => $mau['kinh_do'] ?? null,
                                            'vi_do' => $mau['vi_do'] ?? null,
                                            'thong_so' => data_get($ts, 'thong_so'),
                                            'don_vi_tinh' => data_get($ts, 'don_vi_tinh'),
                                            'phuong_phap_phan_tich' => data_get($ts, 'phuong_phap_phan_tich'),
                                            'ket_qua' => data_get($ts, 'ket_qua'),
                                            'gia_tri_gioi_han' => data_get($ts, 'gia_tri_gioi_han'),
                                            'so_lan_vuot' => data_get($ts, 'so_lan_vuot'),
                                            'tep_pdf' => data_get($attachment, 'link'),
                                        ]);

                                        // update file PDF vào dòng đầu tiên
                                        if ($index === 0 && !empty($attachment['id'])) {
                                            Attachment::where('id', $attachment['id'])->update([
                                                'reference_id' => $kq->id,
                                                'reference_type' => 'ket_qua_quan_trac',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // --- CTR sinh hoạt ---
                    if (!empty($co_so_san_xuat['chatthais']['ctrsh']['ket_quas'])) {
                        foreach ($co_so_san_xuat['chatthais']['ctrsh']['ket_quas'] as $nt) {
                            \App\KetQuaThanhTraChatThaiRanSinhHoat::create([
                                'khoi_luong_phat_sinh' => data_get($nt, 'khoi_luong_phat_sinh'),
                                'thanh_phan_chinh' => data_get($nt, 'thanh_phan_chinh'),
                                'tu_xu_ly' => data_get($nt, 'tu_xu_ly_ctrsh'),
                                'thue_xu_ly' => data_get($nt, 'thue_xu_ly_ctrsh'),
                                'co_quan_thue_xu_ly' => data_get($nt, 'co_quan_thue_xu_ly'),
                                'co_so_san_xuat_id' => $newCoso->id,
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra']['id'],
                                'so_ket_luan' => trim($info['so_quyet_dinh']),
                                'ngay_ket_luan_thanh_tra' => $newCoso->ngay_ket_luan,
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id_CTRSH,
                            ]);
                        }

                        // --- KQ QUAN TRẮC CTR sinh hoạt ---
                        if (!empty($co_so_san_xuat['chatthais']['ctrsh']['ket_qua_quan_trac'])) {
                            foreach ($co_so_san_xuat['chatthais']['ctrsh']['ket_qua_quan_trac'] as $kqqt) {
                                if (empty($kqqt['mau_moi_truong']) || !is_array($kqqt['mau_moi_truong'])) continue;

                                foreach ($kqqt['mau_moi_truong'] as $mau) {
                                    if (empty($mau['thong_sos'])) continue;

                                    $attachment = data_get($mau, 'attachment');

                                    foreach ($mau['thong_sos'] as $index => $ts) {
                                        $kq = \App\KetQuaQuanTrac::create([
                                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                            'loai_moi_truong' => $kqqt['loai_moi_truong'] ?? null,
                                            'dia_diem' => $mau['dia_diem'] ?? null,
                                            'vi_tri' => $mau['vi_tri'] ?? null,
                                            'loai_mau' => $mau['loai_mau'] ?? null,
                                            'kinh_do' => $mau['kinh_do'] ?? null,
                                            'vi_do' => $mau['vi_do'] ?? null,
                                            'thong_so' => data_get($ts, 'thong_so'),
                                            'don_vi_tinh' => data_get($ts, 'don_vi_tinh'),
                                            'phuong_phap_phan_tich' => data_get($ts, 'phuong_phap_phan_tich'),
                                            'ket_qua' => data_get($ts, 'ket_qua'),
                                            'gia_tri_gioi_han' => data_get($ts, 'gia_tri_gioi_han'),
                                            'so_lan_vuot' => data_get($ts, 'so_lan_vuot'),
                                            'tep_pdf' => data_get($attachment, 'link'),
                                        ]);

                                        // update file PDF vào dòng đầu tiên
                                        if ($index === 0 && !empty($attachment['id'])) {
                                            Attachment::where('id', $attachment['id'])->update([
                                                'reference_id' => $kq->id,
                                                'reference_type' => 'ket_qua_quan_trac',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // --- CTNH ---
                    if (!empty($co_so_san_xuat['chatthais']['ctnh']['ket_quas'])) {
                        foreach ($co_so_san_xuat['chatthais']['ctnh']['ket_quas'] as $nt) {
                            \App\KetQuaThanhTraChatThaiNguyHai::create([
                                'khoi_luong_phat_sinh_thuc_te' => data_get($nt, 'khoi_luong_phat_sinh_thuc_te_ctnh'),
                                'khoi_luong_phat_sinh_theo_so_dang_ki' => data_get($nt, 'khoi_luong_phat_sinh_theo_dk_ctnh'),
                                'thanh_phan_chinh' => data_get($nt, 'thanh_phan_chinh'),
                                'tu_xu_ly' => data_get($nt, 'tu_xu_ly_ctnh'),
                                'thue_xu_ly' => data_get($nt, 'thue_xu_ly_ctnh'),
                                'co_quan_thue_xu_ly' => data_get($nt, 'co_quan_thue_xu_ly_ctnh'),
                                'co_so_san_xuat_id' => $newCoso->id,
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra']['id'],
                                'so_ket_luan' => trim($info['so_quyet_dinh']),
                                'ngay_ket_luan_thanh_tra' => $newCoso->ngay_ket_luan,
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id_CTNH,
                            ]);
                        }

                        // --- KQ QUAN TRẮC CTNH ---
                        if (!empty($co_so_san_xuat['chatthais']['ctnh']['ket_qua_quan_trac'])) {
                            foreach ($co_so_san_xuat['chatthais']['ctnh']['ket_qua_quan_trac'] as $kqqt) {
                                if (empty($kqqt['mau_moi_truong']) || !is_array($kqqt['mau_moi_truong'])) continue;

                                foreach ($kqqt['mau_moi_truong'] as $mau) {
                                    if (empty($mau['thong_sos'])) continue;

                                    $attachment = data_get($mau, 'attachment');

                                    foreach ($mau['thong_sos'] as $index => $ts) {
                                        $kq = \App\KetQuaQuanTrac::create([
                                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                            'loai_moi_truong' => $kqqt['loai_moi_truong'] ?? null,
                                            'dia_diem' => $mau['dia_diem'] ?? null,
                                            'vi_tri' => $mau['vi_tri'] ?? null,
                                            'loai_mau' => $mau['loai_mau'] ?? null,
                                            'kinh_do' => $mau['kinh_do'] ?? null,
                                            'vi_do' => $mau['vi_do'] ?? null,
                                            'thong_so' => data_get($ts, 'thong_so'),
                                            'don_vi_tinh' => data_get($ts, 'don_vi_tinh'),
                                            'phuong_phap_phan_tich' => data_get($ts, 'phuong_phap_phan_tich'),
                                            'ket_qua' => data_get($ts, 'ket_qua'),
                                            'gia_tri_gioi_han' => data_get($ts, 'gia_tri_gioi_han'),
                                            'so_lan_vuot' => data_get($ts, 'so_lan_vuot'),
                                            'tep_pdf' => data_get($attachment, 'link'),
                                        ]);

                                        // update file PDF vào dòng đầu tiên
                                        if ($index === 0 && !empty($attachment['id'])) {
                                            Attachment::where('id', $attachment['id'])->update([
                                                'reference_id' => $kq->id,
                                                'reference_type' => 'ket_qua_quan_trac',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // --- Bắt đầu thêm lại phần Kết quả phân tích môi trường ---
                if (!empty($co_so_san_xuat['ket_qua_phan_tich_moi_truong'])) {
                    foreach ($co_so_san_xuat['ket_qua_phan_tich_moi_truong'] as $loai_mt) {
                        if (empty($loai_mt) || empty($loai_mt['mau_moi_truong'])) continue;

                        foreach ($loai_mt['mau_moi_truong'] as $mau) {
                            if (empty($mau) || empty($mau['thong_sos']) || !is_array($mau['thong_sos'])) continue;

                            $attPT = data_get($mau, 'attachment');
                            $tep_pdf = $mau['tep_pdf'] ?? ($attPT['link'] ?? null);

                            foreach ($mau['thong_sos'] as $index => $thong_so) {
                                if (
                                    empty($thong_so['thong_so']) &&
                                    empty($thong_so['ket_qua']) &&
                                    empty($thong_so['gia_tri_gioi_han'])
                                ) continue;

                                $kqpt = KetQuaPhanTichMoiTruong::create([
                                    'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id ?? null,
                                    'loai_moi_truong' => $loai_mt['loai_moi_truong'] ?? null,
                                    'dia_diem' => $mau['dia_diem'] ?? null,
                                    'vi_tri' => $mau['vi_tri'] ?? null,
                                    'loai_mau' => $mau['loai_mau'] ?? null,
                                    'kinh_do' => $mau['kinh_do'] ?? null,
                                    'vi_do' => $mau['vi_do'] ?? null,
                                    'thong_so' => $thong_so['thong_so'] ?? null,
                                    'don_vi_tinh' => $thong_so['don_vi_tinh'] ?? null,
                                    'phuong_phap_phan_tich' => $thong_so['phuong_phap_phan_tich'] ?? null,
                                    'ket_qua' => $thong_so['ket_qua'] ?? null,
                                    'gia_tri_gioi_han' => $thong_so['gia_tri_gioi_han'] ?? null,
                                    'so_lan_vuot' => $thong_so['so_lan_vuot'] ?? 0,
                                    'tep_pdf' => $tep_pdf,
                                ]);

                                // Gắn file PDF cho dòng đầu tiên của mỗi mẫu
                                if ($index === 0) {
                                    if (!empty($attPT['id'])) {
                                        Attachment::where('id', $attPT['id'])->update([
                                            'reference_id' => $kqpt->id,
                                            'reference_type' => 'ket_qua_phan_tich_moi_truong',
                                        ]);
                                    } elseif (!empty($tep_pdf)) {
                                        $att = Attachment::where('link', $tep_pdf)->first();
                                        if ($att) {
                                            $att->update([
                                                'reference_id' => $kqpt->id,
                                                'reference_type' => 'ket_qua_phan_tich_moi_truong',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // --- Kết thúc phần KQ phân tích môi trường ---
                if (!empty($co_so_san_xuat['thutuchanhchinhs'])) {
                    foreach ($co_so_san_xuat['thutuchanhchinhs'] as $key => $thutuchanhchinh) {
                        $loaithutuc = \App\LoaiThuTucHanhChinh::where('ma', $key)->first();
                        if (!$loaithutuc) continue;

                        foreach ($thutuchanhchinh as $item) {
                            \App\KetQuaThanhTraThuTucHanhChinh::create([
                                'loai_thu_tuc_hanh_chinh_id' => $loaithutuc->id,
                                'so_quyet_dinh_phe_duyet' => $item['so_quyet_dinh'] ?? null,
                                'ghi_chu' => $item['ghi_chu'] ?? null,
                                'co_quan_phe_duyet_id' => data_get($item, 'co_quan_quyet_dinh.id'),
                                'thoi_gian_phe_duyet' => $item['thoi_gian_phe_duyet'] ?? null,
                                'so_ket_luan' => trim($info['so_quyet_dinh']),
                                'co_so_san_xuat_id' => $newCoso->id,
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra']['id'],
                                'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                            ]);
                        }
                    }
                }

                if (!empty($co_so_san_xuat['danhsachthutuchanhchinh'])) {
                    foreach ($co_so_san_xuat['danhsachthutuchanhchinh'] as $key => $item) {
                        KetQuaThanhTraThuTucHanhChinh::create([
                            'loai_thu_tuc_hanh_chinh_id' => $item['loai_thu_tuc_hanh_chinh_id'],
                            'so_quyet_dinh_phe_duyet' => $item['so_quyet_dinh_phe_duyet'],
                            'co_quan_phe_duyet_id' => $item['co_quan_phe_duyet_id'],
                            'thoi_gian_phe_duyet' => $item['thoi_gian_phe_duyet'],
                            'so_ket_luan' => trim($info['so_quyet_dinh']),
                            'co_so_san_xuat_id' =>  $info['co_so_san_xuat_id'],
                            'quyet_dinh_thanh_tra_id' =>  $info['quyet_dinh_thanh_tra']['id'],
                            'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                            'quyet_dinh_thanh_tra_id' =>  $info['quyet_dinh_thanh_tra_id'],
                            'ghi_chu' => empty($item['ghi_chu']) ? null : $item['ghi_chu'],
                            'ket_qua_thanh_tra_chung_id' =>  $ketquathanhtrachung->id
                        ]);
                    }
                }
            }


            if (!empty($info['attachments'])) {
                foreach ($info['attachments'] as $attachment) {
                    Attachment::where('id', $attachment['id'])
                        ->update(['reference_id' => $ketquathanhtra->id, 'reference_type' => 'ket_luan_thanh_tra']);
                }
            }

            if (!empty($info['nhom_hanh_vi_vi_phams'])) {
                foreach ($info['nhom_hanh_vi_vi_phams'] as $thongbathanhtra) {
                    $thongbathanhtra['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                    $thongbathanhtra['ma_loai_hinh_quan_trac'] = empty($thongbathanhtra['loai_hinh_quan_trac']['id']) ? null : $thongbathanhtra['loai_hinh_quan_trac']['id'];
                    $nhom_hanh_vi_vi_pham = \App\NhomHanhViViPham::create($thongbathanhtra);

                    if (!empty($thongbathanhtra['vi_pham_xa_thai_khi_thais'])) {
                        $this->createViPhamXaThai($thongbathanhtra['vi_pham_xa_thai_khi_thais'], $nhom_hanh_vi_vi_pham, 'khi_thai', $new_co_so_San_xuat_ids);
                    }

                    if (!empty($thongbathanhtra['vi_pham_xa_thai_nuoc_thais'])) {
                        $this->createViPhamXaThai($thongbathanhtra['vi_pham_xa_thai_nuoc_thais'], $nhom_hanh_vi_vi_pham, 'nuoc_thai', $new_co_so_San_xuat_ids);
                    }
                }
            }

            if (!empty($info['danh_sach_quyet_dinh_xu_phat'])) {
                foreach ($info['danh_sach_quyet_dinh_xu_phat'] as $xuphat) {
                    if (
                        isset($xuphat['so_quyet_dinh']) ||
                        isset($xuphat['co_quan_quyet_dinh_xu_phat']) ||
                        isset($xuphat['thoi_gian_ban_hanh']) || isset($xuphat['ghi_chu']) ||
                        isset($xuphat['so_quyet_dinh_sua_doi']) ||
                        isset($xuphat['ngay_sua_doi_quyet_dinh'])
                    ) {
                        $xuphat['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                        $xuphat['co_quan_quyet_dinh_xu_phat_id'] = empty($xuphat['co_quan_quyet_dinh_xu_phat']) ? null : $xuphat['co_quan_quyet_dinh_xu_phat']['id'];

                        $quyetdinhxuphat = QuyetDinhXuPhat::create($xuphat);
                        if (!empty($xuphat['xu_phat_chinh']) && is_array($xuphat['xu_phat_chinh'])) {
                            foreach ($xuphat['xu_phat_chinh'] as $item) {
                                $item['quyet_dinh_xu_phat_id'] = $quyetdinhxuphat->id;
                                $xuPhatChinh = \App\XuPhatChinh::query()->create($item);
                                foreach ($item['hanh_vi_vi_phams'] as $hanhvivipham) {
                                    // $hanhvivipham['xu_phat_chinh_id'] = $xuPhatChinh->id;
                                    // $hanhvivipham['dieu_id'] = empty($hanhvivipham['dieu']) ? null : $hanhvivipham['dieu']['id'];
                                    // $hanhvivipham['khoan_id'] = empty($hanhvivipham['khoan']) ? null : $hanhvivipham['khoan']['id'];
                                    // $hanhvivipham['muc_id'] = empty($hanhvivipham['muc']) ? null : $hanhvivipham['muc']['id'];
                                    // HanhViViPham::create($hanhvivipham);
                                    HanhViViPhamNew::create(['xu_phat_chinh_id' => $xuPhatChinh->id, 'danh_muc_hanh_vi_id' => $hanhvivipham['hanh_vi']['id']]);
                                }
                            }
                        }
                        if (!empty($xuphat['xu_phat_bo_sung']) && is_array($xuphat['xu_phat_bo_sung'])) {
                            foreach ($xuphat['xu_phat_bo_sung'] as $item) {
                                $item['quyet_dinh_xu_phat_id'] = $quyetdinhxuphat->id;
                                $xuPhatBoSung = \App\XuPhatBoSung::query()->create($item);
                                foreach ($item['chi_tiet_xu_phat_bo_sungs'] as $chiTietXuPhatBoSung) {
                                    $chiTietXuPhatBoSung['xu_phat_bo_sung_id'] = $xuPhatBoSung->id;
                                    $chiTietXuPhatBoSung['loai_xu_phat_bo_sung_id'] = empty($chiTietXuPhatBoSung['loai_xu_phat_bo_sung']) ? null : $chiTietXuPhatBoSung['loai_xu_phat_bo_sung']['id'];
                                    \App\ChiTietXuPhatBoSung::create($chiTietXuPhatBoSung);
                                }
                            }
                        }
                        if (!empty($xuphat['bien_phap_khac_phuc_hau_qua']) && is_array($xuphat['bien_phap_khac_phuc_hau_qua'])) {
                            foreach ($xuphat['bien_phap_khac_phuc_hau_qua'] as $item) {
                                $item['quyet_dinh_xu_phat_id'] = $quyetdinhxuphat->id;
                                $bienPhapKhacPhucHauQua = \App\BienPhapKhacPhucHauQua::query()->create($item);
                                foreach ($item['chi_tiet_bien_phap_khac_phuc_hau_quas'] as $chiTiet) {
                                    $chiTiet['bien_phap_khac_phuc_hau_qua_id'] = $bienPhapKhacPhucHauQua->id;
                                    $chiTiet['cac_bien_phap_khac_phuc_hau_qua_id'] = empty($chiTiet['cac_bien_phap_khac_phuc_hau_qua']) ? null : $chiTiet['cac_bien_phap_khac_phuc_hau_qua']['id'];
                                    \App\ChiTietBienPhapKhacPhucHauQua::create($chiTiet);
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($info['danh_sach_ket_qua_khac_phuc_vi_pham'])) {
                foreach ($info['danh_sach_ket_qua_khac_phuc_vi_pham'] as $khacphucvipham) {
                    $khacphucvipham['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                    $khacPhucHauQua = \App\KetQuaKhacPhucHauQua::query()
                        ->create($khacphucvipham);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Thành công',
                'result' => [],
            ], 200, []);
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollback();
            return response()->json([
                'message' => 'Error',
                'result' => $exception->getMessage(),
            ], 500, []);
        }
    }

    public function updateKetQuaThanhTra(Request $request, $id)
    {
        $info = $request->get('data');

        if (isset($info['organization'])) {
            $ten = $info['organization']['ten'] ?? null;
            $diaChi = $info['organization']['dia_chi_lien_he'] ?? null;

            if ($ten || $diaChi) {
                // Lấy kết quả thanh tra hiện tại để truy xuất organization_id
                $ketquathanhtra_tmp = \App\KetQuaThanhTra::find($id);
                if ($ketquathanhtra_tmp && $ketquathanhtra_tmp->organization_id) {
                    \App\Organization::where('id', $ketquathanhtra_tmp->organization_id)
                        ->update([
                            'ten' => $ten,
                            'dia_chi_lien_he' => $diaChi,
                            'updated_at' => now(),
                        ]);
                }
            }
        }

        $user = Auth::user();
        $validator = Validator::make($info, [
            'quyet_dinh_thanh_tra' => 'required|array',
            'so_quyet_dinh' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->error(),
                'result' => [],
            ], 500, []);
        }
        $new_co_so_San_xuat_ids = [];
        $info['nguoi_cap_nhat'] = $user->id;
        DB::beginTransaction();
        try {
            $ketquathanhtra = KetQuaThanhTra::find($id);
            $kequathanhtrachungs = KetQuaThanhTraChung::where('ket_qua_thanh_tra_id', $id)->get();
            $info['quyet_dinh_thanh_tra_id'] = $info['quyet_dinh_thanh_tra']['id'];
            $ketQuaThanhTraChungIdRemoved = array_diff($kequathanhtrachungs->pluck('id')->all(), array_column($info['co_so_san_xuats'], 'id'));
            if (!empty($ketQuaThanhTraChungIdRemoved)) {
                KetQuaThanhTraChungLoaiHinhHoatDong::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraNuocThai::where('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraKhiThai::where('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChatThaiRanSinhHoat::where('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChatThaiRanCNTT::where('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChatThaiNguyHai::where('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraThuTucHanhChinh::where('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaPhanTichMoiTruong::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChung::where('id', $ketQuaThanhTraChungIdRemoved)->delete();
            }
            $ketquathanhtra->update($info);


            foreach ($info['co_so_san_xuats'] as $key => $co_so_san_xuat) {
                if (!empty($co_so_san_xuat['is_new']) && $co_so_san_xuat['is_new'] == true) {
                    if (isset($co_so_san_xuat['tinh_thanh']) && isset($co_so_san_xuat['tinh_thanh']['id'])) {
                        $tinh = \App\TinhThanh::query()->where('id', $co_so_san_xuat['tinh_thanh']['id'])->first();
                        $co_so_san_xuat['vung_kinh_te_trong_diem_id'] = $tinh->vung_kinh_te_trong_diem_id;
                        $co_so_san_xuat['ma'] = $tinh->ma . '-' . $co_so_san_xuat['ten'];
                        $co_so_san_xuat['luu_vuc_song_id'] = $tinh->luu_vuc_song_id;
                        $co_so_san_xuat['mien_id'] = $tinh->mien_id;
                        $co_so_san_xuat['created_by'] = $user->id;
                        $co_so_san_xuat['updated_by'] = $user->id;
                        $co_so_san_xuat['co_quan_cap_giay_chung_nhan_kinh_doanh'] = empty($co_so_san_xuat['co_quan_kinh_doanh']) ? null : $co_so_san_xuat['co_quan_kinh_doanh']['id'];
                        $co_so_san_xuat['co_quan_cap_giay_chung_nhan_dau_tu'] = empty($co_so_san_xuat['co_quan_dau_tu']) ? null : $co_so_san_xuat['co_quan_dau_tu']['id'];
                        $co_so_san_xuat['khu_cong_nghiep_id'] = $co_so_san_xuat['khu_cong_nghiep']['id'];
                        $co_so_san_xuat['quan_huyen_id'] = $co_so_san_xuat['quan_huyen']['id'];
                        $co_so_san_xuat['tinh_thanh_id'] = $co_so_san_xuat['tinh_thanh']['id'];
                        $co_so_san_xuat['organization_id'] = $ketquathanhtra->organization_id;

                        $new_co_so_San_xuat_ids[$co_so_san_xuat['id']] = null;
                        $cososanxuat = \App\CoSoSanXuat::create(array_except($co_so_san_xuat, 'id'));
                        $new_co_so_San_xuat_ids[$co_so_san_xuat['id']] = $cososanxuat->id;

                        foreach ($co_so_san_xuat['ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs'] as $item) {
                            ChiTietCongSuat::query()->create([
                                'co_so_san_xuat_id' => $cososanxuat->id,
                                'don_vi_id' => empty($item['don_vi_thiet_ke']) ? null : $item['don_vi_thiet_ke']['id'],
                                'thong_so' => empty($item['thong_so_thiet_ke']) ? null : $item['thong_so_thiet_ke'],
                                'loai_hinh_id' => empty($item['loai_hinh_co_so']) ? null : $item['loai_hinh_co_so']['id'],
                                'don_vi_hoat_dong_id' => empty($item['don_vi_hoat_dong']) ? null : $item['don_vi_hoat_dong']['id'],
                                'thong_so_hoat_dong' => empty($item['thong_so_hoat_dong']) ? null : $item['thong_so_hoat_dong'],
                                'ghi_chu' => empty($item['ghi_chu']) ? null : $item['ghi_chu'],
                            ]);
                        }
                        if (!empty($co_so_san_xuat['don_vi_dien_tich'])) {
                            $co_so_san_xuat['dien_tich'] = $co_so_san_xuat['don_vi_dien_tich']['ty_le'] * $co_so_san_xuat['dien_tich'];
                        }
                        if (!empty($co_so_san_xuat['don_vi_nuoc_su_dung'])) {
                            $co_so_san_xuat['luong_nuoc_su_dung'] = $co_so_san_xuat['don_vi_nuoc_su_dung']['ty_le'] * $co_so_san_xuat['luong_nuoc_su_dung'];
                        }
                        $co_so_san_xuat['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                        $co_so_san_xuat['co_so_san_xuat_id'] = $cososanxuat->id;

                        $ketquathanhtrachung = KetQuaThanhTraChung::create(array_except($co_so_san_xuat, ['id']));
                    }
                } else {
                    $ketquathanhtrachung = $kequathanhtrachungs->where('id', $co_so_san_xuat['id'])->first();
                    $co_so_san_xuat['created_by'] = $user->id;
                    $co_so_san_xuat['updated_by'] = $user->id;
                    $co_so_san_xuat['co_quan_cap_giay_chung_nhan_kinh_doanh'] = empty($co_so_san_xuat['co_quan_kinh_doanh']) ? null : $co_so_san_xuat['co_quan_kinh_doanh']['id'];
                    $co_so_san_xuat['co_quan_cap_giay_chung_nhan_dau_tu'] = empty($co_so_san_xuat['co_quan_dau_tu']) ? null : $co_so_san_xuat['co_quan_dau_tu']['id'];

                    $co_so_san_xuat['khu_cong_nghiep_id'] = empty($co_so_san_xuat['khu_cong_nghiep']) ? null : $co_so_san_xuat['khu_cong_nghiep']['id'];
                    $co_so_san_xuat['quan_huyen_id'] = empty($co_so_san_xuat['quan_huyen']) ? null : $co_so_san_xuat['quan_huyen']['id'];
                    $co_so_san_xuat['tinh_thanh_id'] = empty($co_so_san_xuat['tinh_thanh']) ? null : $co_so_san_xuat['tinh_thanh']['id'];

                    $co_so_san_xuat['quan_huyen_id'] = empty($co_so_san_xuat['quan_huyen']) ? null : $co_so_san_xuat['quan_huyen']['id'];
                    $co_so_san_xuat['tinh_thanh_id'] = empty($co_so_san_xuat['tinh_thanh']) ? null : $co_so_san_xuat['tinh_thanh']['id'];
                    if (!empty($co_so_san_xuat['don_vi_dien_tich'])) {
                        $co_so_san_xuat['dien_tich'] = $co_so_san_xuat['don_vi_dien_tich']['ty_le'] * $co_so_san_xuat['dien_tich'];
                    }
                    if (!empty($co_so_san_xuat['don_vi_nuoc_su_dung'])) {
                        $co_so_san_xuat['luong_nuoc_su_dung'] = $co_so_san_xuat['don_vi_nuoc_su_dung']['ty_le'] * $co_so_san_xuat['luong_nuoc_su_dung'];
                    }

                    $co_so_san_xuat['chuyen_doi_don_vi_id'] = $co_so_san_xuat['chuyen_doi_don_vi']['id'] ?? null;

                    $ketquathanhtrachung->update(array_except($co_so_san_xuat, ['id']));

                    /* =============================================
                    *  ✅ CẬP NHẬT CO_SO_SAN_XUAT (AN TOÀN, KHÔNG MẤT DỮ LIỆU)
                    * ============================================= */
                    if (!empty($co_so_san_xuat['co_so_san_xuat']) && !empty($co_so_san_xuat['co_so_san_xuat']['id'])) {
                        $cs = \App\CoSoSanXuat::find($co_so_san_xuat['co_so_san_xuat']['id']);
                        // dd(json_encode($co_so_san_xuat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                        if ($cs) {
                            // Dữ liệu cập nhật cơ bản
                            $updateData = [
                                'ten' => $co_so_san_xuat['ten'] ?? null,
                                'dia_chi_lien_he' => $co_so_san_xuat['dia_chi_lien_he'] ?? null,
                                'dia_chi_hoat_dong' => $co_so_san_xuat['dia_chi_hoat_dong'] ?? null,
                                'kinh_do' => $co_so_san_xuat['kinh_do'] ?? null,
                                'vi_do' => $co_so_san_xuat['vi_do'] ?? null,
                                'khu_cong_nghiep_id' => $co_so_san_xuat['khu_cong_nghiep_id'] ?? null,
                                'loai_co_so_id' => $co_so_san_xuat['loai_co_so_id'] ?? null,
                                'updated_by' => $user->id,
                                'dien_tich' => $co_so_san_xuat['dien_tich'] ?? null,
                                'luong_nuoc_su_dung' => $co_so_san_xuat['luong_nuoc_su_dung'] ?? null,
                                'so_nguoi_lao_dong' => $co_so_san_xuat['so_nguoi_lao_dong'] ?? null,
                                'nguyen_lieu_chinh_su_dung' => $co_so_san_xuat['nguyen_lieu_chinh_su_dung'] ?? null,
                                'nhien_lieu_chinh_su_dung' => $co_so_san_xuat['nhien_lieu_chinh_su_dung'] ?? null,
                                'hoa_chat_su_dung' => $co_so_san_xuat['hoa_chat_su_dung'] ?? null,
                                'nguon_nuoc_su_dung' => $co_so_san_xuat['nguon_nuoc_su_dung'] ?? null,
                                'loai_khu_cong_nghiep' => $co_so_san_xuat['loai_khu_cong_nghiep']['ma'] ?? null,
                                'ty_le_lap_day' => $co_so_san_xuat['ty_le_lap_day'] ?? null,
                                'tong_dien_tich' => $co_so_san_xuat['tong_dien_tich'] ?? null,
                                'chuyen_doi_don_vi_id' => $co_so_san_xuat['chuyen_doi_don_vi']['id'] ?? null,
                                'dien_tich_dat_cn' => $co_so_san_xuat['dien_tich_dat_cn'] ?? null,
                                'dien_tich_dat_cho_thue' => $co_so_san_xuat['dien_tich_dat_cho_thue'] ?? null,
                                'dien_tich_dat_cay_xanh' => $co_so_san_xuat['dien_tich_dat_cay_xanh'] ?? null,

                            ];

                            // ✅ ép ngày_ket_luan = ngày_thanh_tra
                            if (!empty($info['ngay_thanh_tra'])) {
                                $updateData['ngay_ket_luan'] = $info['ngay_thanh_tra'];
                            }

                            // Giữ nguyên các trường có giá trị cũ nếu payload không gửi lên
                            $updateData = array_filter($updateData, fn($v) => $v !== null);

                            // Cập nhật hành chính cấp 1–3 nếu có
                            if (!empty($co_so_san_xuat['co_so_san_xuat']['tinh_thanh_id'])) {
                                $updateData['tinh_thanh_id'] = $co_so_san_xuat['co_so_san_xuat']['tinh_thanh_id'];
                            }
                            if (!empty($co_so_san_xuat['co_so_san_xuat']['quan_huyen_id'])) {
                                $updateData['quan_huyen_id'] = $co_so_san_xuat['co_so_san_xuat']['quan_huyen_id'];
                            }
                            if (!empty($co_so_san_xuat['co_so_san_xuat']['xa_phuong'])) {
                                $updateData['xa_phuong'] = $co_so_san_xuat['co_so_san_xuat']['xa_phuong'];
                            }

                            // ✅ Chỉ sync khi có dữ liệu — tránh mất quan hệ khi payload rỗng
                            if (array_key_exists('tinh_thanhs', $co_so_san_xuat) && !empty($co_so_san_xuat['tinh_thanhs'])) {
                                $cs->tinhThanhs()->sync(collect($co_so_san_xuat['tinh_thanhs'])->pluck('id'));
                            }

                            if (array_key_exists('quan_huyens', $co_so_san_xuat) && !empty($co_so_san_xuat['quan_huyens'])) {
                                $cs->quanHuyens()->sync(collect($co_so_san_xuat['quan_huyens'])->pluck('id'));
                            }

                            if (array_key_exists('phuong_xas', $co_so_san_xuat) && !empty($co_so_san_xuat['phuong_xas'])) {
                                $cs->phuongXas()->sync(collect($co_so_san_xuat['phuong_xas'])->pluck('id'));
                            }

                            // Thực hiện cập nhật
                            $cs->update($updateData);
                        }
                    }

                    KetQuaThanhTraChungLoaiHinhHoatDong::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    KetQuaThanhTraNuocThai::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    KetQuaThanhTraKhiThai::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    KetQuaThanhTraChatThaiRanSinhHoat::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    KetQuaThanhTraChatThaiRanCNTT::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    KetQuaThanhTraChatThaiNguyHai::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    KetQuaThanhTraThuTucHanhChinh::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    ThongBaoQuyetDinhThanhTra::where('ket_qua_thanh_tra_id', $ketquathanhtra->id)->delete();
                    KetQuaPhanTichMoiTruong::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)->delete();
                    CoSoSanXuatLoaiHinhONhiem::where('co_so_san_xuat_id', $co_so_san_xuat['co_so_san_xuat_id'])->delete();
                    if (isset($co_so_san_xuat['loai_hinh_o_nhiem']) && count($co_so_san_xuat['loai_hinh_o_nhiem']) > 0) {
                        foreach ($co_so_san_xuat['loai_hinh_o_nhiem'] as $it) {
                            $it = (array)$it;
                            CoSoSanXuatLoaiHinhONhiem::create([
                                'co_so_san_xuat_id' => $co_so_san_xuat['co_so_san_xuat_id'],
                                'loai_hinh_o_nhiem_id' => $it['id']
                            ]);
                        }
                    }

                    foreach ($ketquathanhtra->nhomHanhViViPhams as $nhom_hanh_vi_vi_pham) {
                        foreach ($nhom_hanh_vi_vi_pham->chiTietViPhamXaThais as $chi_tiet_vi_pham_xa_thai) {
                            ChiTietPhatTangThem::where('chi_tiet_vi_pham_xa_thai_id', $chi_tiet_vi_pham_xa_thai->id)->delete();
                        }
                        $nhom_hanh_vi_vi_pham->delete();
                    }
                    \App\NhomHanhViViPham::where('ket_qua_thanh_tra_id', $ketquathanhtra->id)->delete();

                    QuyetDinhXuPhat::where('ket_qua_thanh_tra_id', $ketquathanhtra->id)->delete();
                    KetQuaKhacPhucHauQua::where('ket_qua_thanh_tra_id', $ketquathanhtra->id)->delete();
                }

                if (!empty($co_so_san_xuat['ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs'])) {
                    foreach ($co_so_san_xuat['ket_qua_thanh_tra_chung_loai_hinh_hoat_dongs'] as $item) {
                        KetQuaThanhTraChungLoaiHinhHoatDong::create([
                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                            'don_vi_thiet_ke_id' => empty($item['don_vi_thiet_ke']) ? null : $item['don_vi_thiet_ke']['id'],
                            'thong_so_thiet_ke' => empty($item['thong_so_thiet_ke']) ? null : $item['thong_so_thiet_ke'],
                            'loai_hinh_co_so_id' => empty($item['loai_hinh_co_so']) ? null : $item['loai_hinh_co_so']['id'],
                            'don_vi_hoat_dong_id' => empty($item['don_vi_hoat_dong']) ? null : $item['don_vi_hoat_dong']['id'],
                            'thong_so_hoat_dong' => empty($item['thong_so_hoat_dong']) ? null : $item['thong_so_hoat_dong'],
                            'ghi_chu' => empty($item['ghi_chu']) ? null : $item['ghi_chu'],
                        ]);
                    }
                }

                /** ==========================
                 * 1️⃣ Xử lý NƯỚC THẢI
                 * ========================== */
                if (!empty($co_so_san_xuat['ket_qua_thanh_tra_nuoc_thais']['nuoc_thais'])) {
                    $phieuData = $co_so_san_xuat['ket_qua_thanh_tra_nuoc_thais']['phieu_thu_nghiem'] ?? [];

                    $phieu = null;
                    if (!empty($phieuData)) {
                        if (!empty($phieuData['id']) && $phieu = PhieuThuNghiems::find($phieuData['id'])) {
                            $phieu->update([
                                'dia_diem_lay_mau' => $phieuData['dia_diem_lay_mau'] ?? null,
                                'dia_chi' => $phieuData['dia_chi'] ?? null,
                                'updated_at' => now(),
                            ]);
                        } else {
                            $phieu = PhieuThuNghiems::create([
                                'ten_khach_hang' => $phieuData['ten_khach_hang'] ?? null,
                                'dia_diem_lay_mau' => $phieuData['dia_diem_lay_mau'] ?? null,
                                'dia_chi' => $phieuData['dia_chi'] ?? null,
                            ]);
                        }
                    }
                    $attNT = data_get($co_so_san_xuat, 'ket_qua_thanh_tra_nuoc_thais.phieu_thu_nghiem.attachment');
                    if (!empty($attNT['id']) && !empty($phieu)) {
                        Attachment::where('id', $attNT['id'])->update([
                            'reference_id'   => $phieu->id,
                            'reference_type' => 'phieu_thu_nghiem_nuoc_thai',
                        ]);
                    }

                    $phieu_thu_nghiem_id = isset($phieu) ? $phieu->id : null;

                    foreach ($co_so_san_xuat['ket_qua_thanh_tra_nuoc_thais']['nuoc_thais'] as $nt) {
                        KetQuaThanhTraNuocThai::create([
                            'luu_luong' => $nt['luu_luong'] ?? null,
                            'thanh_phan' => $nt['thanh_phan'] ?? null,
                            'cong_suat_he_thong_xu_ly' => $nt['cong_suat_he_thong_xu_ly'] ?? null,
                            'nguon_tiep_nhan' => $nt['nguon_tiep_nhan'] ?? null,
                            'thong_so_nuoc_thai_vuot_chuan' => $nt['thong_so_nuoc_thai_vuot_chuan'] ?? null,
                            'co_so_san_xuat_id' => $cososanxuat->id ?? ($co_so_san_xuat['co_so_san_xuat_id'] ?? null),
                            'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra_id'],
                            'so_ket_luan' => $info['so_quyet_dinh'],
                            'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                            'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id,
                            'loai_hinh_quan_trac' => is_array($nt['loai_hinh_quan_trac'] ?? null)
                                ? ($nt['loai_hinh_quan_trac']['id'] ?? null)
                                : ($nt['loai_hinh_quan_trac'] ?? null),
                        ]);
                    }
                }



                /** ==========================
                 * 2️⃣ Xử lý KHÍ THẢI
                 * ========================== */
                if (!empty($co_so_san_xuat['ket_qua_thanh_tra_khi_thais']['khi_thais'])) {
                    $phieuData = $co_so_san_xuat['ket_qua_thanh_tra_khi_thais']['phieu_thu_nghiem'] ?? [];

                    $phieu = null;
                    if (!empty($phieuData)) {
                        if (!empty($phieuData['id']) && $phieu = PhieuThuNghiems::find($phieuData['id'])) {
                            $phieu->update([
                                'dia_diem_lay_mau' => $phieuData['dia_diem_lay_mau'] ?? null,
                                'dia_chi' => $phieuData['dia_chi'] ?? null,
                                'updated_at' => now(),
                            ]);
                        } else {
                            $phieu = PhieuThuNghiems::create([
                                'ten_khach_hang' => $phieuData['ten_khach_hang'] ?? null,
                                'dia_diem_lay_mau' => $phieuData['dia_diem_lay_mau'] ?? null,
                                'dia_chi' => $phieuData['dia_chi'] ?? null,
                            ]);
                        }
                    }

                    $attKT = data_get($co_so_san_xuat, 'ket_qua_thanh_tra_khi_thais.phieu_thu_nghiem.attachment');
                    if (!empty($attKT['id']) && !empty($phieu)) {
                        Attachment::where('id', $attKT['id'])->update([
                            'reference_id'   => $phieu->id,
                            'reference_type' => 'phieu_thu_nghiem_khi_thai',
                        ]);
                    }
                    $phieu_thu_nghiem_id = isset($phieu) ? $phieu->id : null;

                    foreach ($co_so_san_xuat['ket_qua_thanh_tra_khi_thais']['khi_thais'] as $kt) {
                        KetQuaThanhTraKhiThai::create([
                            'luu_luong' => $kt['luu_luong'] ?? null,
                            'thanh_phan' => $kt['thanh_phan'] ?? null,
                            'cong_suat_he_thong_xu_ly' => $kt['cong_suat_he_thong_xu_ly'] ?? null,
                            'nguon_tiep_nhan' => $kt['nguon_tiep_nhan'] ?? null,
                            'thong_so_khi_thai_vuot_chuan' => $kt['thong_so_khi_thai_vuot_chuan'] ?? null,
                            'quy_trinh_xu_ly' => $kt['quy_trinh_xu_ly'] ?? null,
                            'co_so_san_xuat_id' => $cososanxuat->id ?? ($co_so_san_xuat['co_so_san_xuat_id'] ?? null),
                            'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra_id'],
                            'so_ket_luan' => $info['so_quyet_dinh'],
                            'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                            'phieu_thu_nghiem_id' => $phieu_thu_nghiem_id,
                            'loai_hinh_quan_trac' => is_array($kt['loai_hinh_quan_trac'] ?? null)
                                ? ($kt['loai_hinh_quan_trac']['id'] ?? null)
                                : ($kt['loai_hinh_quan_trac'] ?? null),
                        ]);
                    }
                }



                if (!empty($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['ctrcntt'])) {

                    foreach ($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['ctrcntt'] as $nt) {
                        KetQuaThanhTraChatThaiRanCNTT::create([
                            'khoi_luong_phat_sinh' => empty($nt['khoi_luong_phat_sinh']) ? null : $nt['khoi_luong_phat_sinh'],
                            'thanh_phan_chinh' => empty($nt['thanh_phan_chinh']) ? null : $nt['thanh_phan_chinh'],
                            'tu_xu_ly' => empty($nt['tu_xu_ly']) ? false : true,
                            'thue_xu_ly' => empty($nt['thue_xu_ly']) ? false : true,
                            'co_quan_thue_xu_ly' => empty($nt['co_quan_thue_xu_ly']) ? null : $nt['co_quan_thue_xu_ly'],
                            'co_so_san_xuat_id' => $co_so_san_xuat['co_so_san_xuat_id'],
                            'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra_id'],
                            'so_ket_luan' => $info['so_quyet_dinh'],
                            'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                            'phieu_thu_nghiem_id' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['id']

                        ]);
                    }
                    $phieuId = $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['id'] ?? null;
                    if (!empty($phieuId)) {
                        PhieuThuNghiems::findOrFail($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['id'])->update([
                            // 'ten_khach_hang' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['ten_khach_hang'],
                            'dia_diem_lay_mau' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['dia_diem_lay_mau'],
                            'dia_chi' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['dia_chi'],
                            // 'vi_tri_quan_trac' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['vi_tri_quan_trac'],
                            // 'kinh_do' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['kinh_do'],
                            // 'vi_do' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['vi_do'],
                            // 'ngay_lay_mau' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['ngay_quan_trac'],
                            // 'thoi_tiet' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['thoi_tiet'],
                            // 'ngay_phan_tich_start' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['ngay_phan_tich'],
                            // 'ngay_phan_tich_end' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['ngay_ket_thuc_phan_tich'],
                            // 'nguoi_phan_tich' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_c_n_t_t']['phieu_thu_nghiem']['nguoi_phan_tich'],
                        ]);
                    }
                }

                if (!empty($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['ctrsh'])) {
                    foreach ($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['ctrsh'] as $nt) {
                        KetQuaThanhTraChatThaiRanSinhHoat::create(
                            [
                                'khoi_luong_phat_sinh' => empty($nt['khoi_luong_phat_sinh']) ? null : $nt['khoi_luong_phat_sinh'],
                                'thanh_phan_chinh' => empty($nt['thanh_phan_chinh']) ? null : $nt['thanh_phan_chinh'],
                                'tu_xu_ly' => empty($nt['tu_xu_ly']) ? false : true,
                                'thue_xu_ly' => empty($nt['thue_xu_ly']) ? false : true,
                                'co_quan_thue_xu_ly' =>  empty($nt['co_quan_thue_xu_ly']) ? null : $nt['co_quan_thue_xu_ly'],
                                'co_so_san_xuat_id' => $co_so_san_xuat['co_so_san_xuat_id'],
                                'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra_id'],
                                'so_ket_luan' => $info['so_quyet_dinh'],
                                'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                                'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                'phieu_thu_nghiem_id' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['id']

                            ]
                        );
                    }
                    $phieuId = $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['id'] ?? null;
                    if (!empty($phieuId)) {

                        PhieuThuNghiems::findOrFail($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['id'])->update([
                            // 'ten_khach_hang' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['ten_khach_hang'],
                            'dia_diem_lay_mau' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['dia_diem_lay_mau'],
                            'dia_chi' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['dia_chi'],
                            // 'vi_tri_quan_trac' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['vi_tri_quan_trac'],
                            // 'kinh_do' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['kinh_do'],
                            // 'vi_do' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['vi_do'],
                            // 'ngay_lay_mau' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['ngay_quan_trac'],
                            // 'thoi_tiet' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['thoi_tiet'],
                            // 'ngay_phan_tich_start' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['ngay_phan_tich'],
                            // 'ngay_phan_tich_end' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['ngay_ket_thuc_phan_tich'],
                            // 'nguoi_phan_tich' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_ran_sinh_hoats']['phieu_thu_nghiem']['nguoi_phan_tich'],
                        ]);
                    }
                }

                if (!empty($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['ctnh'])) {

                    foreach ($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['ctnh'] as $nt) {
                        KetQuaThanhTraChatThaiNguyHai::create([
                            'khoi_luong_phat_sinh_thuc_te' => empty($nt['khoi_luong_phat_sinh_thuc_te']) ? null : $nt['khoi_luong_phat_sinh_thuc_te'],
                            'khoi_luong_phat_sinh_theo_so_dang_ki' => empty($nt['khoi_luong_phat_sinh_theo_so_dang_ki']) ? null : $nt['khoi_luong_phat_sinh_theo_so_dang_ki'],
                            'thanh_phan_chinh' => empty($nt['thanh_phan_chinh']) ? null : $nt['thanh_phan_chinh'],
                            'tu_xu_ly' => empty($nt['tu_xu_ly']) ? false : true,
                            'thue_xu_ly' => empty($nt['thue_xu_ly']) ? false : true,
                            'co_quan_thue_xu_ly' =>  empty($nt['co_quan_thue_xu_ly']) ? null : $nt['co_quan_thue_xu_ly'],
                            'co_so_san_xuat_id' => $co_so_san_xuat['co_so_san_xuat_id'],
                            'quyet_dinh_thanh_tra_id' => $info['quyet_dinh_thanh_tra_id'],
                            'so_ket_luan' => $info['so_quyet_dinh'],
                            'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                            'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                        ]);
                    }
                    $phieuId = $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['id'] ?? null;
                    if (!empty($phieuId)) {

                        PhieuThuNghiems::findOrFail($co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['id'])->update([
                            // 'ten_khach_hang' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['ten_khach_hang'],
                            'dia_diem_lay_mau' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['dia_diem_lay_mau'],
                            'dia_chi' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['dia_chi'],
                            // 'vi_tri_quan_trac' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['vi_tri_quan_trac'],
                            // 'kinh_do' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['kinh_do'],
                            // 'vi_do' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['vi_do'],
                            // 'ngay_lay_mau' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['ngay_quan_trac'],
                            // 'thoi_tiet' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['thoi_tiet'],
                            // 'ngay_phan_tich_start' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['ngay_phan_tich'],
                            // 'ngay_phan_tich_end' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['ngay_ket_thuc_phan_tich'],
                            // 'nguoi_phan_tich' => $co_so_san_xuat['ket_qua_thanh_tra_chat_thai_nguy_hai']['phieu_thu_nghiem']['nguoi_phan_tich'],
                        ]);
                    }
                }

                if (!empty($co_so_san_xuat['ket_qua_phan_tich_moi_truong'])) {
                    // Lấy danh sách ID hiện có để sau cùng xóa những bản ghi không còn
                    $existingIds = KetQuaPhanTichMoiTruong::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)
                        ->pluck('id')
                        ->toArray();

                    $processedIds = [];

                    foreach ($co_so_san_xuat['ket_qua_phan_tich_moi_truong'] as $loai_mt) {
                        if (empty($loai_mt) || empty($loai_mt['mau_moi_truong'])) continue;

                        // Lặp qua từng mẫu môi trường
                        foreach ($loai_mt['mau_moi_truong'] as $mau) {
                            if (empty($mau)) continue;

                            // Bỏ qua nếu không có thông số
                            if (empty($mau['thong_sos']) || !is_array($mau['thong_sos'])) continue;

                            // Lấy file PDF riêng cho mẫu này
                            $attPT = data_get($mau, 'attachment');
                            $tep_pdf = $mau['tep_pdf'] ?? ($attPT['link'] ?? null);

                            foreach ($mau['thong_sos'] as $index => $thong_so) {
                                // Bỏ qua dòng trống
                                if (
                                    empty($thong_so['thong_so']) &&
                                    empty($thong_so['ket_qua']) &&
                                    empty($thong_so['gia_tri_gioi_han'])
                                ) continue;

                                $recordId = $thong_so['id'] ?? null;

                                $data = [
                                    'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id ?? null,
                                    'loai_moi_truong' => $loai_mt['loai_moi_truong'] ?? null,
                                    'dia_diem' => $mau['dia_diem'] ?? null,
                                    'vi_tri' => $mau['vi_tri'] ?? null,
                                    'loai_mau' => $mau['loai_mau'] ?? null,
                                    'kinh_do' => $mau['kinh_do'] ?? null,
                                    'vi_do' => $mau['vi_do'] ?? null,
                                    'thong_so' => $thong_so['thong_so'] ?? null,
                                    'don_vi_tinh' => $thong_so['don_vi_tinh'] ?? null,
                                    'phuong_phap_phan_tich' => $thong_so['phuong_phap_phan_tich'] ?? null,
                                    'ket_qua' => $thong_so['ket_qua'] ?? null,
                                    'gia_tri_gioi_han' => $thong_so['gia_tri_gioi_han'] ?? null,
                                    'so_lan_vuot' => $thong_so['so_lan_vuot'] ?? 0,
                                    'tep_pdf' => $tep_pdf,
                                ];

                                // Update nếu có ID, ngược lại tạo mới
                                if ($recordId && $existing = KetQuaPhanTichMoiTruong::find($recordId)) {
                                    $existing->update($data);
                                    $kqpt = $existing;
                                } else {
                                    $kqpt = KetQuaPhanTichMoiTruong::create($data);
                                }

                                $processedIds[] = $kqpt->id;

                                // ✅ Gắn file PDF chỉ cho dòng đầu tiên trong mỗi mẫu
                                if ($index === 0) {
                                    if (!empty($attPT['id'])) {
                                        Attachment::where('id', $attPT['id'])
                                            ->update([
                                                'reference_id'   => $kqpt->id,
                                                'reference_type' => 'ket_qua_phan_tich_moi_truong',
                                            ]);
                                    } elseif (!empty($tep_pdf)) {
                                        $att = Attachment::where('link', $tep_pdf)->first();
                                        if ($att) {
                                            $att->update([
                                                'reference_id'   => $kqpt->id,
                                                'reference_type' => 'ket_qua_phan_tich_moi_truong',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Xóa bản ghi cũ không còn trong danh sách
                    $idsToDelete = array_diff($existingIds, $processedIds);
                    if (!empty($idsToDelete)) {
                        KetQuaPhanTichMoiTruong::whereIn('id', $idsToDelete)->delete();
                    }
                }

                /** ==========================
                 * 6️⃣ Xử lý KẾT QUẢ QUAN TRẮC (KQQT)
                 * ========================== */
                if (!empty($co_so_san_xuat['ket_qua_quan_trac'])) {

                    $existing = KetQuaQuanTrac::where('ket_qua_thanh_tra_chung_id', $ketquathanhtrachung->id)
                        ->pluck('id')->toArray();

                    $processed = [];

                    foreach ($co_so_san_xuat['ket_qua_quan_trac'] as $loai) {

                        if (empty($loai['mau_moi_truong'])) continue;

                        foreach ($loai['mau_moi_truong'] as $mau) {

                            if (empty($mau['thong_sos'])) continue;

                            $att = data_get($mau, 'attachment');
                            $tep_pdf = $mau['tep_pdf'] ?? ($att['link'] ?? null);

                            foreach ($mau['thong_sos'] as $index => $ts) {

                                if (
                                    empty($ts['thong_so']) &&
                                    empty($ts['ket_qua']) &&
                                    empty($ts['gia_tri_gioi_han'])
                                ) continue;

                                $recordId = $ts['id'] ?? null;

                                $data = [
                                    'ket_qua_thanh_tra_chung_id' => $ketquathanhtrachung->id,
                                    'loai_moi_truong' => $loai['loai_moi_truong'] ?? null,
                                    'dia_diem' => $mau['dia_diem'] ?? null,
                                    'vi_tri' => $mau['vi_tri'] ?? null,
                                    'loai_mau' => $mau['loai_mau'] ?? null,
                                    'kinh_do' => $mau['kinh_do'] ?? null,
                                    'vi_do' => $mau['vi_do'] ?? null,
                                    'thong_so' => $ts['thong_so'] ?? null,
                                    'don_vi_tinh' => $ts['don_vi_tinh'] ?? null,
                                    'phuong_phap_phan_tich' => $ts['phuong_phap_phan_tich'] ?? null,
                                    'ket_qua' => $ts['ket_qua'] ?? null,
                                    'gia_tri_gioi_han' => $ts['gia_tri_gioi_han'] ?? null,
                                    'so_lan_vuot' => $ts['so_lan_vuot'] ?? 0,
                                    'tep_pdf' => $tep_pdf,
                                ];

                                // update hoặc create
                                if ($recordId && $exist = KetQuaQuanTrac::find($recordId)) {
                                    $exist->update($data);
                                    $kq = $exist;
                                } else {
                                    $kq = KetQuaQuanTrac::create($data);
                                }

                                $processed[] = $kq->id;

                                // gắn file PDF cho dòng đầu tiên
                                if ($index === 0) {
                                    if (!empty($att['id'])) {
                                        Attachment::where('id', $att['id'])->update([
                                            'reference_id' => $kq->id,
                                            'reference_type' => 'ket_qua_quan_trac',
                                        ]);
                                    } elseif (!empty($tep_pdf)) {
                                        $attachmentRow = Attachment::where('link', $tep_pdf)->first();
                                        if ($attachmentRow) {
                                            $attachmentRow->update([
                                                'reference_id' => $kq->id,
                                                'reference_type' => 'ket_qua_quan_trac',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Xóa cái không còn
                    $toDelete = array_diff($existing, $processed);
                    if (!empty($toDelete)) {
                        KetQuaQuanTrac::whereIn('id', $toDelete)->delete();
                    }
                }


                if (!empty($co_so_san_xuat['danhsachthutuchanhchinh'])) {
                    foreach ($co_so_san_xuat['danhsachthutuchanhchinh'] as $key => $item) {
                        KetQuaThanhTraThuTucHanhChinh::create([
                            'loai_thu_tuc_hanh_chinh_id' => $item['loai_thu_tuc_hanh_chinh_id'],
                            'so_quyet_dinh_phe_duyet' => $item['so_quyet_dinh_phe_duyet'],
                            'co_quan_phe_duyet_id' => $item['co_quan_phe_duyet_id'],
                            'thoi_gian_phe_duyet' => $item['thoi_gian_phe_duyet'],
                            'so_ket_luan' => $info['so_quyet_dinh'],
                            'co_so_san_xuat_id' =>  $co_so_san_xuat['co_so_san_xuat_id'],
                            'quyet_dinh_thanh_tra_id' =>  $info['quyet_dinh_thanh_tra_id'],
                            'ngay_ket_luan_thanh_tra' => $info['ngay_thanh_tra'],
                            'quyet_dinh_thanh_tra_id' =>  $info['quyet_dinh_thanh_tra_id'],
                            'ghi_chu' => empty($item['ghi_chu']) ? null : $item['ghi_chu'],
                            'ket_qua_thanh_tra_chung_id' =>  $ketquathanhtrachung->id
                        ]);
                    }
                }
            }


            if (!empty($info['nhom_hanh_vi_vi_phams'])) {
                foreach ($info['nhom_hanh_vi_vi_phams'] as $thongbathanhtra) {

                    $thongbathanhtra['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                    $thongbathanhtra['ma_loai_hinh_quan_trac'] = empty($thongbathanhtra['loai_hinh_quan_trac']['id']) ? null : $thongbathanhtra['loai_hinh_quan_trac']['id'];
                    $nhom_hanh_vi_vi_pham = \App\NhomHanhViViPham::create($thongbathanhtra);

                    if (!empty($thongbathanhtra['vi_pham_xa_thai_khi_thais'])) {
                        $this->createViPhamXaThai($thongbathanhtra['vi_pham_xa_thai_khi_thais'], $nhom_hanh_vi_vi_pham, 'khi_thai', $new_co_so_San_xuat_ids);
                    }

                    if (!empty($thongbathanhtra['vi_pham_xa_thai_nuoc_thais'])) {
                        $this->createViPhamXaThai($thongbathanhtra['vi_pham_xa_thai_nuoc_thais'], $nhom_hanh_vi_vi_pham, 'nuoc_thai', $new_co_so_San_xuat_ids);
                    }
                }
            }
            if (!empty($info['danh_sach_quyet_dinh_xu_phat'])) {
                foreach ($info['danh_sach_quyet_dinh_xu_phat'] as $xuphat) {
                    if (
                        isset($xuphat['so_quyet_dinh']) ||
                        isset($xuphat['co_quan_quyet_dinh_xu_phat']) ||
                        isset($xuphat['thoi_gian_ban_hanh']) || isset($xuphat['ghi_chu']) ||
                        isset($xuphat['so_quyet_dinh_sua_doi']) ||
                        isset($xuphat['ngay_sua_doi_quyet_dinh'])
                    ) {
                        $xuphat['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                        $xuphat['co_quan_quyet_dinh_xu_phat_id'] = empty($xuphat['co_quan_quyet_dinh_xu_phat']) ? null : $xuphat['co_quan_quyet_dinh_xu_phat']['id'];
                        if (isset($xuphat['id'])) {
                            QuyetDinhXuPhat::where('id', $xuphat['id'])->delete();
                            \App\XuPhatChinh::where('quyet_dinh_xu_phat_id', $xuphat['id'])->delete();
                        }
                        $quyetdinhxuphat = QuyetDinhXuPhat::create($xuphat);
                        if (!empty($xuphat['xu_phat_chinh']) && is_array($xuphat['xu_phat_chinh'])) {
                            foreach ($xuphat['xu_phat_chinh'] as $item) {
                                $item['quyet_dinh_xu_phat_id'] = $quyetdinhxuphat->id;
                                $xuPhatChinh = \App\XuPhatChinh::query()->create($item);
                                foreach ($item['hanh_vi_vi_phams'] as $hanhvivipham) {
                                    // $hanhvivipham['xu_phat_chinh_id'] = $xuPhatChinh->id;
                                    // $hanhvivipham['dieu_id'] = empty($hanhvivipham['dieu']) ? null : $hanhvivipham['dieu']['id'];
                                    // $hanhvivipham['khoan_id'] = empty($hanhvivipham['khoan']) ? null : $hanhvivipham['khoan']['id'];
                                    // $hanhvivipham['muc_id'] = empty($hanhvivipham['muc']) ? null : $hanhvivipham['muc']['id'];
                                    // HanhViViPham::create($hanhvivipham);
                                    HanhViViPhamNew::create(['xu_phat_chinh_id' => $xuPhatChinh->id, 'danh_muc_hanh_vi_id' => $hanhvivipham['hanh_vi']['id']]);
                                }
                            }
                        }
                        if (!empty($xuphat['xu_phat_bo_sung']) && is_array($xuphat['xu_phat_bo_sung'])) {
                            foreach ($xuphat['xu_phat_bo_sung'] as $item) {
                                $item['quyet_dinh_xu_phat_id'] = $quyetdinhxuphat->id;
                                $xuPhatBoSung = \App\XuPhatBoSung::query()->create($item);
                                foreach ($item['chi_tiet_xu_phat_bo_sungs'] as $chiTietXuPhatBoSung) {
                                    $chiTietXuPhatBoSung['xu_phat_bo_sung_id'] = $xuPhatBoSung->id;
                                    $chiTietXuPhatBoSung['loai_xu_phat_bo_sung_id'] = empty($chiTietXuPhatBoSung['loai_xu_phat_bo_sung']) ? null : $chiTietXuPhatBoSung['loai_xu_phat_bo_sung']['id'];
                                    \App\ChiTietXuPhatBoSung::create($chiTietXuPhatBoSung);
                                }
                            }
                        }
                        if (!empty($xuphat['bien_phap_khac_phuc_hau_qua']) && is_array($xuphat['bien_phap_khac_phuc_hau_qua'])) {
                            foreach ($xuphat['bien_phap_khac_phuc_hau_qua'] as $item) {
                                $item['quyet_dinh_xu_phat_id'] = $quyetdinhxuphat->id;
                                $bienPhapKhacPhucHauQua = \App\BienPhapKhacPhucHauQua::query()->create($item);
                                foreach ($item['chi_tiet_bien_phap_khac_phuc_hau_quas'] as $chiTiet) {
                                    $chiTiet['bien_phap_khac_phuc_hau_qua_id'] = $bienPhapKhacPhucHauQua->id;
                                    $chiTiet['cac_bien_phap_khac_phuc_hau_qua_id'] = empty($chiTiet['cac_bien_phap_khac_phuc_hau_qua']) ? null : $chiTiet['cac_bien_phap_khac_phuc_hau_qua']['id'];
                                    \App\ChiTietBienPhapKhacPhucHauQua::create($chiTiet);
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($info['danh_sach_ket_qua_khac_phuc_vi_pham'])) {
                foreach ($info['danh_sach_ket_qua_khac_phuc_vi_pham'] as $khacphucvipham) {
                    $khacphucvipham['ket_qua_thanh_tra_id'] = $ketquathanhtra->id;
                    $khacPhucHauQua = \App\KetQuaKhacPhucHauQua::query()
                        ->create($khacphucvipham);
                }
            }

            if (!empty($info['attachments'])) {
                foreach ($info['attachments'] as $attachment) {
                    Attachment::where('id', $attachment['id'])
                        ->update(['reference_id' => $id, 'reference_type' => 'ket_luan_thanh_tra']);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Thành công',
                'result' => [],
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollback();
            dd($exception);
            return response()->json([
                'message' => 'Lỗi',
                'result' => $exception->getMessage(),
            ], 500, []);
        }
    }
    function createViPhamXaThai($vi_pham_xa_thais, $nhom_hanh_vi_vi_pham, $type, $new_co_so_San_xuat_ids)
    {

        foreach ($vi_pham_xa_thais as $vi_pham_xa_thai) {
            $co_so_san_xuat_vi_pham = $vi_pham_xa_thai['co_so_san_xuat'];
            if (!empty($co_so_san_xuat_vi_pham)) {
                if (!empty($co_so_san_xuat_vi_pham['is_new']) && $co_so_san_xuat_vi_pham['is_new']) {
                    $vi_pham_xa_thai['co_so_san_xuat_id'] = $new_co_so_San_xuat_ids[$co_so_san_xuat_vi_pham['id']];
                } else {
                    $vi_pham_xa_thai['co_so_san_xuat_id'] = $co_so_san_xuat_vi_pham['id'];
                }
            } else {
                $vi_pham_xa_thai['co_so_san_xuat_id'] = null;
            }

            $chi_tiet_vi_pham = ChiTietViPhamXaThai::create([
                'nhom_hanh_vi_vi_pham_id' => $nhom_hanh_vi_vi_pham->id,
                'muc_vi_pham_id' => !empty($vi_pham_xa_thai['muc']) ? $vi_pham_xa_thai['muc']['id'] : null,
                'khoan_vi_pham_id' => !empty($vi_pham_xa_thai['khoan']) ? $vi_pham_xa_thai['khoan']['id'] : null,
                'thong_so_id' => !empty($vi_pham_xa_thai['thong_so']) ? $vi_pham_xa_thai['thong_so']['id'] : null,
                'don_vi_id' => !empty($vi_pham_xa_thai['don_vi']) ? $vi_pham_xa_thai['don_vi']['id'] : null,
                'co_so_san_xuat_id' => $vi_pham_xa_thai['co_so_san_xuat_id'],
                'ket_qua' => !empty($vi_pham_xa_thai['ket_qua']) ? $vi_pham_xa_thai['ket_qua'] : null,
                'so_lan_vuot' => !empty($vi_pham_xa_thai['so_lan_vuot']) ? $vi_pham_xa_thai['so_lan_vuot'] : null,
                'type' => $type,
                'dia_diem_lay_mau' => empty($vi_pham_xa_thai['dia_diem_lay_mau']) ? '' : $vi_pham_xa_thai['dia_diem_lay_mau']
            ]);

            foreach ($vi_pham_xa_thai['chi_tiet_phat_tang_thems'] as $item) {
                ChiTietPhatTangThem::create([
                    'chi_tiet_vi_pham_xa_thai_id' => $chi_tiet_vi_pham->id,
                    'phat_tang_them_id' => !empty($item['phat_tang_them']) ? $item['phat_tang_them']['id'] : null,
                    'don_vi_id' => !empty($item['don_vi']) ? $item['don_vi']['id'] : null,
                    'thong_so_id' => !empty($item['thong_so']) ? $item['thong_so']['id'] : null,
                    'ket_qua' => $item['ket_qua'],
                    'so_lan_vuot' => $item['so_lan_vuot'],
                ]);
            }
        }
    }

    public function deleteKetQuaThanhTra(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            $ketquathanhtra = KetQuaThanhTra::find($id);

            $kequathanhtrachungs = KetQuaThanhTraChung::where('ket_qua_thanh_tra_id', $id)->get();

            $ketQuaThanhTraChungIdRemoved = $kequathanhtrachungs->pluck('id')->all();

            if (!empty($ketQuaThanhTraChungIdRemoved)) {
                KetQuaThanhTraChungLoaiHinhHoatDong::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();

                KetQuaThanhTraNuocThai::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraKhiThai::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChatThaiRanSinhHoat::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChatThaiRanCNTT::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChatThaiNguyHai::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraThuTucHanhChinh::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaQuanTrac::whereIn('ket_qua_thanh_tra_chung_id', $ketQuaThanhTraChungIdRemoved)->delete();
                KetQuaThanhTraChung::whereIn('id', $ketQuaThanhTraChungIdRemoved)->delete();
            }

            foreach ($ketquathanhtra->nhomHanhViViPhams as $nhom_hanh_vi_vi_pham) {
                foreach ($nhom_hanh_vi_vi_pham->chiTietViPhamXaThais as $chi_tiet_vi_pham_xa_thai) {
                    ChiTietPhatTangThem::where('chi_tiet_vi_pham_xa_thai_id', $chi_tiet_vi_pham_xa_thai->id)->delete();
                }
                $nhom_hanh_vi_vi_pham->delete();
            }

            NhomHanhViViPham::where('ket_qua_thanh_tra_id', $ketquathanhtra->id)->delete();
            KetQuaKhacPhucHauQua::where('ket_qua_thanh_tra_id', $id)->delete();
            QuyetDinhXuPhat::where('ket_qua_thanh_tra_id', $id)->delete();
            ThongBaoQuyetDinhThanhTra::where('ket_qua_thanh_tra_id', $id)->delete();
            $ketquathanhtra->delete();

            DB::commit();
            if ($request->ajax()) {
                return response()->json([
                    'message' => __('message.success'),
                    'result' => [],
                ], 200, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.delete_success'));
        } catch (Exception $ex) {
            DB::rollback();
            if ($request->ajax()) {
                return response()->json([
                    'message' => __('message.success'),
                    'result' => [],
                ], 500, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error'));
        }
    }

    public function getChatThais(Request $request, $id)
    {
        $ketquathanhtra = KetQuaThanhTra::find($id);
        $nuocthais = KetQuaThanhTraNuocThai::where('so_ket_luan', $ketquathanhtra->so_quyet_dinh)->get();
        $khithais = KetQuaThanhTraKhiThai::where('so_ket_luan', $ketquathanhtra->so_quyet_dinh)->get();
        $ctrshs = KetQuaThanhTraChatThaiRanSinhHoat::where('so_ket_luan', $ketquathanhtra->so_quyet_dinh)->get();
        $ctrcntts = KetQuaThanhTraChatThaiRanCNTT::where('so_ket_luan', $ketquathanhtra->so_quyet_dinh)->get();
        $ctnhs = KetQuaThanhTraChatThaiNguyHai::where('so_ket_luan', $ketquathanhtra->so_quyet_dinh)->get();
        $nuocthai = [];
        $khithai = [];
        $ctrsh = [];
        $ctrcntt = [];
        $ctnh = [];
        $data = [];
        foreach ($nuocthais as $key => $item) {
            $nuocthai[$key]['luu_luong'] = $item->luu_luong;
            $nuocthai[$key]['thanh_phan'] = $item->thanh_phan;
            $nuocthai[$key]['cong_suat_he_thong_xu_ly'] = $item->cong_suat_he_thong_xu_ly;
            $nuocthai[$key]['thong_so_nuoc_thai_vuot_chuan'] = $item->thong_so_nuoc_thai_vuot_chuan;
            $nuocthai[$key]['nguon_tiep_nhan'] = $item->nguon_tiep_nhan;
        }
        $data['nuocthai'] = $nuocthai;
        foreach ($khithais as $key => $item) {
            $khithai[$key]['luu_luong'] = $item->luu_luong;
            $khithai[$key]['thanh_phan'] = $item->thanh_phan;
            $khithai[$key]['cong_suat_he_thong_xu_ly'] = $item->cong_suat_he_thong_xu_ly;
            $khithai[$key]['thong_so_nuoc_thai_vuot_chuan'] = $item->thong_so_nuoc_thai_vuot_chuan;
            $khithai[$key]['nguon_tiep_nhan'] = $item->nguon_tiep_nhan;
        }
        $data['khithai'] = $khithai;
        foreach ($ctrshs as $key => $item) {
            $ctrsh[$key]['khoi_luong_phat_sinh'] = $item->khoi_luong_phat_sinh;
            $ctrsh[$key]['thanh_phan_chinh'] = $item->thanh_phan_chinh;
            $ctrsh[$key]['tu_xu_ly'] = $item->tu_xu_ly;
            $ctrsh[$key]['thue_xu_ly'] = $item->thue_xu_ly;
            $ctrsh[$key]['co_quan_thue_xu_ly'] = $item->co_quan_thue_xu_ly;
        }
        $data['ctrsh'] = $ctrsh;
        foreach ($ctrcntts as $key => $item) {
            $ctrcntt[$key]['khoi_luong_phat_sinh'] = $item->khoi_luong_phat_sinh;
            $ctrcntt[$key]['thanh_phan_chinh'] = $item->thanh_phan_chinh;
            $ctrcntt[$key]['tu_xu_ly'] = $item->tu_xu_ly;
            $ctrcntt[$key]['thue_xu_ly'] = $item->thue_xu_ly;
            $ctrcntt[$key]['co_quan_thue_xu_ly'] = $item->co_quan_thue_xu_ly;
        }
        $data['ctrcntt'] = $ctrcntt;
        foreach ($ctnhs as $key => $item) {
            $ctnh[$key]['khoi_luong_phat_sinh_thuc_te'] = $item->khoi_luong_phat_sinh_thuc_te;
            $ctnh[$key]['khoi_luong_phat_sinh_theo_so_dang_ki'] = $item->khoi_luong_phat_sinh_theo_so_dang_ki;
            $ctnh[$key]['thanh_phan_chinh'] = $item->thanh_phan_chinh;
            $ctnh[$key]['tu_xu_ly'] = $item->tu_xu_ly;
            $ctnh[$key]['thue_xu_ly'] = $item->thue_xu_ly;
            $ctnh[$key]['co_quan_thue_xu_ly'] = $item->co_quan_thue_xu_ly;
        }
        $data['ctnh'] = $ctnh;
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function indexQuyetDinhThanhTras(Request $request)
    {
        $search = $request->get('search');
        if (isset($search)) {
            return response()->json([
                'message' => 'Thành công',
                'result' => QuyetDinhThanhTra::query()->where('so_quyet_dinh', 'ilike', "%{$search}%")->orderBy('created_at')->get(),
            ], 200, []);
        } else {
            return response()->json([
                'message' => 'Thành công',
                'result' => QuyetDinhThanhTra::query()->orderBy('created_at')->get(),
            ], 200, []);
        }
    }

    public function indexCoSoSanXuatByThanhTras(Request $request)
    {
        $search = $request->get('search');
        if (isset($search)) {
            return response()->json([
                'message' => 'Thành công',
                'result' => CoSoSanXuat::query()->where('ten', 'ilike', "%{$search}%")->get(),
            ], 200, []);
        }
    }

    public function deleteDoanThanhTra(Request $request, $id)
    {
        Auth::user();
        $qdtt = QuyetDinhThanhTra::query()->find($id);
        $attachments = Attachment::where('reference_id', $id)->where('reference_type', 'quyet_dinh_thanh_tra');
        DB::beginTransaction();
        try {
            $attachments->delete();

            KetQuaThanhTraChatThaiRanCNTT::where('quyet_dinh_thanh_tra_id', $id)->update([
                'quyet_dinh_thanh_tra_id' => null
            ]);

            $qdtt->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error'));
        }

        return redirect()
            ->back()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.delete_success'));
    }

    function exportKetQuaThanhTra(Request $request, $id)
    {
        $donViGoc = $this->getDataByName(\App\ChuyenDoiDonVi::class)->where('don_vi_goc', true)->whereIn('loai', ['dien_tich', 'luu_luong_nuoc_thai']);
        $donViGocDienTich = $donViGoc->firstWhere('loai', 'dien_tich');
        $donViGocLuuLuongNuocThai = $donViGoc->firstWhere('loai', 'luu_luong_nuoc_thai');
        $ketLuanThanhTra = \App\KetQuaThanhTra::query()
            ->with([
                'quyetDinhThanhTra',
                'ketQuaThanhTraChungs.khuCongNghiep',
                'ketQuaThanhTraChungs.quanHuyen',
                'ketQuaThanhTraChungs.tinhThanh',
                'ketQuaThanhTraChungs.ketQuaThanhTraNuocThais',
                'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc',
                'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs',
                'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.coQuanToChuc',
                'ketQuaThanhTraChungs.ketQuaThanhTraKhiThais',
                'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanSinhHoats',
                'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanCNTT',
                'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiNguyHai',
                'quyetDinhXuPhats.coQuanQuyetDinhXuPhat',
                'quyetDinhXuPhats.xuPhatBoSung.chiTietXuPhatBoSungs.loaiXuPhatBoSung',
                'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.dieu',
                'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.khoan',
                'quyetDinhXuPhats.xuPhatChinh.hanhViViPhams.muc',
                'quyetDinhXuPhats.bienPhapKhacPhucHauQua.chiTietBienPhapKhacPhucHauQuas.cacBienPhapKhacPhucHauQua',
                'ketQuaKhacPhucHauQuas',
                'thongBaoQuyetDinhThanhTras.coQuanThongBao',
            ])
            ->find($id);
        $excelFile = public_path() . '/report/chi_tiet_quyet_dinh_thanh_tra.xlsx';

        \Excel::load($excelFile, function ($doc) use ($ketLuanThanhTra, $donViGocDienTich, $donViGocLuuLuongNuocThai) {
            $doc->sheet('Thông tin chung', function ($sheet) use ($ketLuanThanhTra, $donViGocDienTich, $donViGocLuuLuongNuocThai) {
                $this->setValue($sheet, 'B3', $ketLuanThanhTra->quyetDinhThanhTra->so_quyet_dinh);
                $this->setValue($sheet, 'B4', $ketLuanThanhTra->so_quyet_dinh);
                $this->setValue($sheet, 'B5', $ketLuanThanhTra->ngay_thanh_tra);
                $ketLuanThanhTra->ketQuaThanhTraChungs->each(function ($item, $key) use ($sheet, $donViGocDienTich, $donViGocLuuLuongNuocThai) {
                    $this->setValue($sheet, 'A' . strval($key + 8), "Tên");
                    $this->setValue($sheet, 'A' . strval($key + 9), "Khu CCN");
                    $this->setValue($sheet, 'A' . strval($key + 10), "Xã, phường");
                    $this->setValue($sheet, 'A' . strval($key + 11), "Quận huyện");
                    $this->setValue($sheet, 'A' . strval($key + 12), "Tỉnh thành");
                    $this->setValue($sheet, 'A' . strval($key + 13), "Địa chỉ hoạt động");
                    $this->setValue($sheet, 'A' . strval($key + 14), "Nguyên liệu chính");

                    $this->setValue($sheet, 'C' . strval($key + 8), "Chứng nhận kinh doanh");
                    $this->setValue($sheet, 'C' . strval($key + 9), "Chứng nhận đầu tư");
                    $this->setValue($sheet, 'C' . strval($key + 10), "Diện tích");
                    $this->setValue($sheet, 'C' . strval($key + 11), "Lượng nước sử dụng");
                    $this->setValue($sheet, 'C' . strval($key + 12), "Số lượng lao động");
                    $this->setValue($sheet, 'C' . strval($key + 13), "Nhiên liệu chính");
                    $this->setValue($sheet, 'C' . strval($key + 14), "Hóa chất sử dụng");

                    $this->setValue($sheet, 'E' . strval($key + 8), "Ngày cấp");
                    $this->setValue($sheet, 'E' . strval($key + 9), "Ngày cấp");
                    $this->setValue($sheet, 'E' . strval($key + 10), "Đơn vị");
                    $this->setValue($sheet, 'E' . strval($key + 11), "Đơn vị");
                    $this->setValue($sheet, 'E' . strval($key + 12), "Nguồn nước sử dụng");

                    $this->setValue($sheet, 'A' . strval($key + 7), 'Địa điểm hoạt động số ' . ($key + 1));
                    $this->setValue($sheet, 'B' . strval($key + 8), $item->ten);
                    $this->setValue($sheet, 'B' . strval($key + 9), $item->khuCongNghiep->ten);
                    $this->setValue($sheet, 'B' . strval($key + 10), $item->xa_phuong);
                    $this->setValue($sheet, 'B' . strval($key + 11), $item->quanHuyen->ten);
                    $this->setValue($sheet, 'B' . strval($key + 12), $item->tinhThanh->ten);
                    $this->setValue($sheet, 'B' . strval($key + 13), $item->dia_chi_hoat_dong);
                    $this->setValue($sheet, 'B' . strval($key + 14), $item->nguyen_lieu_chinh);
                    $this->setValue($sheet, 'D' . strval($key + 8), " " . $item->so_giay_chung_nhan_kinh_doanh);
                    $this->setValue($sheet, 'D' . strval($key + 9), " " . $item->so_giay_chung_nhan_dau_tu);
                    $this->setValue($sheet, 'D' . strval($key + 10), $item->dien_tich);
                    $this->setValue($sheet, 'D' . strval($key + 11), $item->luong_nuoc_su_dung);
                    $this->setValue($sheet, 'D' . strval($key + 12), $item->so_nguoi_lao_dong);
                    $this->setValue($sheet, 'D' . strval($key + 13), $item->nhien_lieu_chinh_su_dung);
                    $this->setValue($sheet, 'D' . strval($key + 14), $item->nguyen_lieu_chinh_su_dung);
                    $this->setValue($sheet, 'F' . strval($key + 8), $item->ngay_cap_giay_chung_nhan_kinh_doanh);
                    $this->setValue($sheet, 'F' . strval($key + 9), $item->ngay_cap_giay_chung_nhan_dau_tu);
                    $this->setValue($sheet, 'F' . strval($key + 10), $donViGocDienTich->ten);
                    $this->setValue($sheet, 'F' . strval($key + 11), $donViGocLuuLuongNuocThai->ten);
                    $this->setValue($sheet, 'F' . strval($key + 12), $item->nguon_nuoc_su_dung);
                });
            });

            $doc->sheet('Kết quả thanh tra', function ($sheet) use ($ketLuanThanhTra) {
                $ketLuanThanhTra->ketQuaThanhTraChungs->each(function ($item, $key) use ($sheet) {
                    $item->ketQuaThanhTraNuocThais->each(function ($nuocThai, $key) use ($sheet) {
                        $this->setValue($sheet, 'A' . strval($key + 4), $nuocThai->luu_luong);
                        $this->setValue($sheet, 'B' . strval($key + 4), $nuocThai->cong_suat_he_thong_xu_ly);
                        $this->setValue($sheet, 'C' . strval($key + 4), $nuocThai->thanh_phan);
                        $this->setValue($sheet, 'D' . strval($key + 4), $nuocThai->nguon_tiep_nhan);
                        $this->setValue($sheet, 'E' . strval($key + 4), $nuocThai->thong_so_nuoc_thai_vuot_chuan);
                    });
                    $item->ketQuaThanhTraKhiThais->each(function ($khiThai, $key) use ($sheet) {
                        $this->setValue($sheet, 'F' . strval($key + 4), $khiThai->luu_luong);
                        $this->setValue($sheet, 'G' . strval($key + 4), $khiThai->cong_suat_he_thong_xu_ly);
                        $this->setValue($sheet, 'H' . strval($key + 4), $khiThai->thanh_phan);
                        $this->setValue($sheet, 'I' . strval($key + 4), $khiThai->nguon_tiep_nhan);
                        $this->setValue($sheet, 'J' . strval($key + 4), $khiThai->thong_so_nuoc_thai_vuot_chuan);
                    });
                    $item->ketQuaThanhTraChatThaiRanSinhHoats->each(function ($chatThaiRanSinhHoat, $key) use ($sheet) {
                        $this->setValue($sheet, 'K' . strval($key + 4), $chatThaiRanSinhHoat->khoi_luong_phat_sinh);
                        $this->setValue($sheet, 'L' . strval($key + 4), $chatThaiRanSinhHoat->thanh_phan_chinh);
                        $this->setValue($sheet, 'M' . strval($key + 4), $chatThaiRanSinhHoat->tu_xu_ly ? '√' : '');
                        $this->setValue($sheet, 'N' . strval($key + 4), $chatThaiRanSinhHoat->thue_xu_ly ? '√' : '');
                    });
                    $item->ketQuaThanhTraChatThaiRanCNTT->each(function ($chatThaiRanCongNghiep, $key) use ($sheet) {
                        $this->setValue($sheet, 'O' . strval($key + 4), $chatThaiRanCongNghiep->khoi_luong_phat_sinh);
                        $this->setValue($sheet, 'P' . strval($key + 4), $chatThaiRanCongNghiep->thanh_phan_chinh);
                        $this->setValue($sheet, 'Q' . strval($key + 4), $chatThaiRanCongNghiep->tu_xu_ly ? '√' : '');
                        $this->setValue($sheet, 'R' . strval($key + 4), $chatThaiRanCongNghiep->thue_xu_ly ? '√' : '');
                    });
                    $item->ketQuaThanhTraChatThaiNguyHai->each(function ($chatThaiNguyHai, $key) use ($sheet) {
                        $this->setValue($sheet, 'S' . strval($key + 4), $chatThaiNguyHai->khoi_luong_phat_sinh_thuc_te);
                        $this->setValue($sheet, 'T' . strval($key + 4), $chatThaiNguyHai->khoi_luong_phat_sinh_theo_so_dang_ki);
                        $this->setValue($sheet, 'U' . strval($key + 4), $chatThaiNguyHai->thanh_phan_chinh);
                        $this->setValue($sheet, 'V' . strval($key + 4), $chatThaiNguyHai->tu_xu_ly ? '√' : '');
                        $this->setValue($sheet, 'W' . strval($key + 4), $chatThaiNguyHai->thue_xu_ly ? '√' : '');
                    });
                    $temp = 0;
                    $temp1 = 0;
                    $temp2 = 0;
                    $temp3 = 0;
                    $temp4 = 0;
                    $temp5 = 0;
                    $temp6 = 0;
                    $temp7 = 0;
                    $item->ketQuaThanhTraThuTucHanhChinhs->each(function ($thutuchanhchinh, $key) use ($sheet, &$temp, &$temp1, &$temp2, &$temp3, &$temp4, &$temp5, &$temp6, &$temp7) {
                        if ($thutuchanhchinh->loaiThuTuc->ma == "dtm") {
                            $this->setValue($sheet, 'A' . strval($temp + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'B' . strval($temp + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'C' . strval($temp + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "dabvmt") {
                            $this->setValue($sheet, 'D' . strval($temp1 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'E' . strval($temp1 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'F' . strval($temp1 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp1++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "pabvmt") {
                            $this->setValue($sheet, 'G' . strval($temp2 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'H' . strval($temp2 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'I' . strval($temp2 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp2++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "gxnctbvmt") {
                            $this->setValue($sheet, 'J' . strval($temp3 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'K' . strval($temp3 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'L' . strval($temp3 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp3++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "sdkcntctnh") {
                            $this->setValue($sheet, 'M' . strval($temp4 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'P' . strval($temp4 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'Q' . strval($temp4 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp4++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "gxnddkbvmtnkpl") {
                            $this->setValue($sheet, 'T' . strval($temp5 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'U' . strval($temp5 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'V' . strval($temp5 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp5++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "gpxt") {
                            $this->setValue($sheet, 'Y' . strval($temp6 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'Z' . strval($temp6 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'AA' . strval($temp6 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp6++;
                        }
                        if ($thutuchanhchinh->loaiThuTuc->ma == "ttk") {
                            $this->setValue($sheet, 'AB' . strval($temp7 + 21), $thutuchanhchinh->so_quyet_dinh_phe_duyet);
                            $this->setValue($sheet, 'AC' . strval($temp7 + 21), $thutuchanhchinh->coQuanToChuc->ten);
                            $this->setValue($sheet, 'AD' . strval($temp7 + 21), $thutuchanhchinh->thoi_gian_phe_duyet);
                            $temp7++;
                        }
                    });
                });
                $ketLuanThanhTra->quyetDinhXuPhats->each(function ($quyetDinhXuphat, $key) use ($sheet) {
                    $this->setValue($sheet, 'D' . strval($key + 37), empty($quyetDinhXuphat->coQuanQuyetDinhXuPhat) ? '' : $quyetDinhXuphat->coQuanQuyetDinhXuPhat->ten);
                    $this->setValue($sheet, 'E' . strval($key + 37), $quyetDinhXuphat->so_quyet_dinh);
                    $this->setValue($sheet, 'F' . strval($key + 37), $quyetDinhXuphat->thoi_gian_ban_hanh);
                    $this->setValue($sheet, 'G' . strval($key + 37), $quyetDinhXuphat->so_quyet_dinh_sua_doi);
                    $this->setValue($sheet, 'H' . strval($key + 37), $quyetDinhXuphat->ngay_sua_doi_quyet_dinh);
                    $this->setValue($sheet, 'I' . strval($key + 37), $quyetDinhXuphat->ngay_sua_doi_quyet_dinh);
                    $this->setValue($sheet, 'J' . strval($key + 37), $quyetDinhXuphat->ghi_chu);
                    if ($quyetDinhXuphat->xuPhatChinh->count() > 0) {
                        $this->setValue($sheet, 'K' . strval($key + 37), $quyetDinhXuphat->xuPhatChinh[0]->so_tien_xu_phat_chinh);
                        $danhSachHanhViViPham = collect();
                        $quyetDinhXuphat->xuPhatChinh->each(function ($value, $key) use ($danhSachHanhViViPham) {
                            $value->hanhViViPhams->each(function ($value, $key) use ($danhSachHanhViViPham) {
                                $danhSachHanhViViPham->push($value->dieu->ten . ", Khoản: " . $value->khoan->ten . ",Mục: " . $value->muc->ten);
                            });
                        });
                        $this->setValue($sheet, 'L' . strval($key + 37), $quyetDinhXuphat->xuPhatChinh[0]->noi_dung_vi_pham . "\n" . $danhSachHanhViViPham->implode("\n"));
                    }
                    if ($quyetDinhXuphat->xuPhatBoSung->count() > 0) {
                        $this->setValue($sheet, 'M' . strval($key + 37), $quyetDinhXuphat->xuPhatBoSung[0]->so_tien_xu_phat_bo_sung);
                        $danhSachChiTietXuPhatBoSung = collect();
                        $quyetDinhXuphat->xuPhatBoSung->each(function ($value, $key) use ($danhSachChiTietXuPhatBoSung) {
                            $value->chiTietXuPhatBoSungs->each(function ($value, $key) use ($danhSachChiTietXuPhatBoSung) {
                                if (isset($value->loaiXuPhatBoSung)) {
                                    $danhSachChiTietXuPhatBoSung->push($value->loaiXuPhatBoSung->ten);
                                }
                            });
                        });
                        $this->setValue($sheet, 'O' . strval($key + 37), $quyetDinhXuphat->xuPhatBoSung[0]->noi_dung_xu_phat_bo_sung . "\n" . $danhSachChiTietXuPhatBoSung->implode("\n"));
                    }
                    if ($quyetDinhXuphat->bienPhapKhacPhucHauQua->count() > 0) {
                        $this->setValue($sheet, 'P' . strval($key + 37), $quyetDinhXuphat->bienPhapKhacPhucHauQua[0]->so_tien_chung_cau);
                        $danhSachChiTietBienPhapKhacPhucHauQua = collect();
                        $quyetDinhXuphat->bienPhapKhacPhucHauQua->each(function ($value, $key) use ($danhSachChiTietBienPhapKhacPhucHauQua) {
                            $value->chiTietBienPhapKhacPhucHauQuas->each(function ($value, $key) use ($danhSachChiTietBienPhapKhacPhucHauQua) {
                                if (isset($value->cacBienPhapKhacPhucHauQua)) {
                                    $danhSachChiTietBienPhapKhacPhucHauQua->push($value->cacBienPhapKhacPhucHauQua->ten);
                                }
                            });
                        });
                        $this->setValue($sheet, 'Q' . strval($key + 37), $quyetDinhXuphat->bienPhapKhacPhucHauQua[0]->noi_dung_bien_phap_khac_phuc_hau_qua . "\n" . $danhSachChiTietBienPhapKhacPhucHauQua->implode("\n"));
                    }
                });
                $ketLuanThanhTra->ketQuaKhacPhucHauQuas->each(function ($khacphuchauqua, $key) use ($sheet) {
                    $this->setValue($sheet, 'T' . strval($key + 37), $khacphuchauqua->so_quyet_dinh);
                    if ($khacphuchauqua->da_nop_phat) {
                        $this->setValue($sheet, 'U' . strval($key + 37), 'x');
                    }
                    if ($khacphuchauqua->da_khac_phuc) {
                        $this->setValue($sheet, 'V' . strval($key + 37), 'x');
                    }
                    $this->setValue($sheet, 'Y' . strval($key + 37), $khacphuchauqua->tinh_trang_bao_cao);
                    $this->setValue($sheet, 'Z' . strval($key + 37), $khacphuchauqua->thoi_gian_ban_hanh);
                    $this->setValue($sheet, 'AA' . strval($key + 37), $khacphuchauqua->so_hieu_bao_cao);
                    $this->setValue($sheet, 'AB' . strval($key + 37), $khacphuchauqua->ngay_ban_hanh_bao_cao);
                    $this->setValue($sheet, 'AD' . strval($key + 37), $khacphuchauqua->so_tien);
                    $this->setValue($sheet, 'AE' . strval($key + 37), $khacphuchauqua->noi_dung_da_khac_phuc);
                    $this->setValue($sheet, 'AF' . strval($key + 37), $khacphuchauqua->noi_dung_chua_khac_phuc);
                });
                $ketLuanThanhTra->thongBaoQuyetDinhThanhTras->each(function ($thongBaoQuyetDinhThanhTra, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 37), $thongBaoQuyetDinhThanhTra->so_ket_luan);
                    $this->setValue($sheet, 'B' . strval($key + 37), $thongBaoQuyetDinhThanhTra->thoi_gian);
                    $this->setValue($sheet, 'C' . strval($key + 37), $thongBaoQuyetDinhThanhTra->coQuanThongBao->ten);
                });
            });
        })->download('xlsx');
    }
}
