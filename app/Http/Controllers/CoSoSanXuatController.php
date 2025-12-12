<?php

namespace App\Http\Controllers;

use App\ChiTietCongSuat;
use App\ChuDauTu;
use App\ChuyenDoiDonVi;
use App\CoSoSanXuat;
use App\CoQuanToChuc;
use App\CoSoSanXuatLoaiHinhONhiem;
use App\CoSoSanXuatLoaiNganhNghe;
use App\DiaDiemCoSoSanXuat;
use App\KetQuaQtmt;
use App\KetQuaThanhTra;
use App\KetQuaThanhTraChatThaiNguyHai;
use App\KetQuaThanhTraChatThaiRanCNTT;
use App\KetQuaThanhTraChatThaiRanSinhHoat;
use App\KetQuaThanhTraChung;
use App\KetQuaThanhTraKhiThai;
use App\KetQuaThanhTraNuocThai;
use App\KetQuaThanhTraThuTucHanhChinh;
use App\KhuCongNghiep;
use App\LoaiHinhCoSo;
use App\Organization;
use App\QuanHuyen;
use App\QuyetDinhThanhTra;
use App\QuyetDinhXuPhat;
use App\ThongBaoQuyetDinhThanhTra;
use App\TinhThanh;
use App\Traits\ExecuteExcel;
use App\Traits\ExecuteString;
use App\Traits\GeoCode;
use App\Traits\GetDataCache;
use App\XuPhatBoSung;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class CoSoSanXuatController extends Controller
{
    use ExecuteString, GetDataCache, GeoCode, ExecuteExcel;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getCoSoSanXuat', 'checkLogin']]);
    }

    public function index(Request $request)
    {
        $arrData = $this->getDataIndex($request);
        if ($request->headers->get('accept') != 'xlsx') {
            return view('cososanxuat.index', array_merge($arrData, [
                'loaicosos' => $this->getDataByName(\App\LoaiHinhCoSo::class),
                'tinhthanhs' => \App\TinhThanh::query()->get()
            ]));
        } else {
            $data = $arrData['data'];
            $input = $request->all();
            $excelFile = public_path() . '/report/danh_sach_co_so_thanh_tra.xlsx';

            $this->load($excelFile, 'Sheet1', function ($sheet) use (&$data, &$input) {
                $rowKey =  4;
                foreach ($data as $key => $cssx) {

                    if (count($cssx->ketQuaThanhTras) > 1) {
                        $sheet->mergeCells('B' . $rowKey . ':B' . ($rowKey + count($cssx->ketQuaThanhTras) - 1));
                        $sheet->mergeCells('C' . $rowKey . ':C' . ($rowKey + count($cssx->ketQuaThanhTras) - 1));
                    }
                    if (count($cssx->ketQuaThanhTras) == 0) {
                        $sheet->row($rowKey, [
                            $rowKey + 1,
                            $cssx->ten,
                            $cssx->dia_chi_lien_he,
                            '',
                            '',
                            '',
                            ''
                        ]);
                        $rowKey++;
                    } else {

                        foreach ($cssx->ketQuaThanhTras as $ketquathanhtra) {
                            $sheet->row($rowKey, [
                                $rowKey - 3,
                                $cssx->ten,
                                $cssx->dia_chi_lien_he,
                                $ketquathanhtra->quyetDinhThanhtra->so_quyet_dinh,
                                $ketquathanhtra->quyetDinhThanhtra->ngay_ra_quyet_dinh,
                                $ketquathanhtra->so_quyet_dinh,
                                $ketquathanhtra->ngay_thanh_tra,
                                $ketquathanhtra->nguoiCapNhat->name
                            ]);
                            $rowKey++;
                        }
                    }
                }
            })->download('xlsx');
        }
    }

    public function getDataIndex(Request $request)
    {
        $perPage = $request->get('perpage', 100);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $search_khu_cong_nghiep = $request->get('search_khu_cong_nghiep');
        $search_tencososanxuat = $request->get('search_tencososanxuat');
        $search_masothue = $request->get('search_masothue');
        $search_year = $request->get('search_year');
        $tu_ngay = $request->get('tu_ngay');
        $den_ngay = $request->get('den_ngay');
        $tu_ngay_QD = $request->get('tu_ngay_QD');
        $den_ngay_QD = $request->get('den_ngay_QD');
        $search_loai_co_so = $request->get('search_loai_co_so');
        $search_tinh_thanh = $request->get('search_tinh_thanh');
        $search_quan_huyen = $request->get('search_quan_huyen');
        $search_luu_vuc_song = $request->get('search_luu_vuc_song');
        $search_mien = $request->get('search_mien');
        $search_vung_kinh_te_trong_diem = $request->get('search_vung_kinh_te_trong_diem');
        $search_co_so_da_thay_doi_thong_tin = $request->get('search_co_so_da_thay_doi_thong_tin');
        $order_column = $request->input('order_column', 'ten');
        $order_direction = $request->input('order_direction', 'asc');
        // $coSoDongBo = CoSoSanXuat::where('trang_thai_dong_bo', 'da_dong_bo')->pluck('organization_id')->toArray();
        $query = \App\Organization::has('ketQuaThanhTras');

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('ten', 'ilike', "%{$search}%");
                $query->orWhere('dia_chi_lien_he', 'ilike', "%{$search}%");
                $query->orWhereHas('ketQuaThanhTras', function ($query) use ($search) {
                    $query->where('so_quyet_dinh', 'ilike', "%{$search}%");
                    $query->orWhere('ma_dinh_danh', 'ilike', "%{$search}%");
                    $query->orWhereHas('quyetDinhThanhTra', function ($query) use ($search) {
                        $query->where('so_quyet_dinh', 'ilike', "%{$search}%");
                        $query->orWhere('ma_dinh_danh', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('coSoSanXuats', function ($query) use ($search) {
                    $query->where('ma_dinh_danh', 'ilike', "%{$search}%");
                    $query->where('ten', 'ilike', "%{$search}%");
                });
            });
        }
        if (isset($search_tencososanxuat)) {
            $query->where('ten', 'ilike', "%{$search_tencososanxuat}%");
        }
        if (isset($search_masothue)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_masothue) {
                $query->where('so_giay_chung_nhan_kinh_doanh', 'ilike', "%{$search_masothue}%");
            });
        }
        if (!empty($tu_ngay) && !empty($den_ngay)) {
            $tu_ngay = Carbon::createFromFormat(config('app.format_date'), $tu_ngay);
            $den_ngay = Carbon::createFromFormat(config('app.format_date'), $den_ngay);

            $query->whereHas('ketQuaThanhTras', function ($query) use ($tu_ngay, $den_ngay) {
                $query->where('ngay_thanh_tra', '<=', $den_ngay);
                $query->where('ngay_thanh_tra', '>=', $tu_ngay);
            });

            $query->with(['ketQuaThanhTras' => function ($query) use ($tu_ngay, $den_ngay) {
                $query->where('ngay_thanh_tra', '<=', $den_ngay);
                $query->where('ngay_thanh_tra', '>=', $tu_ngay);
                $query->with(['quyetDinhThanhTra', 'nguoiCapNhat']);
            }]);
        } else {
            $query->with(['ketQuaThanhTras.quyetDinhThanhTra', 'ketQuaThanhTras.nguoiCapNhat']);
        }

        if (!empty($tu_ngay_QD) && !empty($den_ngay_QD)) {
            $tu_ngay_QD = Carbon::createFromFormat(config('app.format_date'), $tu_ngay_QD);
            $den_ngay_QD = Carbon::createFromFormat(config('app.format_date'), $den_ngay_QD);

            $query->whereHas('ketQuaThanhTras.quyetDinhThanhTra', function ($query) use ($tu_ngay_QD, $den_ngay_QD) {
                $query->where('ngay_ra_quyet_dinh', '<=', $den_ngay_QD)
                    ->where('ngay_ra_quyet_dinh', '>=', $tu_ngay_QD);
            });

            $query->with(['ketQuaThanhTras' => function ($query) use ($tu_ngay_QD, $den_ngay_QD) {
                $query->whereHas('quyetDinhThanhTra', function ($q) use ($tu_ngay_QD, $den_ngay_QD) {
                    $q->where('ngay_ra_quyet_dinh', '<=', $den_ngay_QD)
                        ->where('ngay_ra_quyet_dinh', '>=', $tu_ngay_QD);
                })->with(['quyetDinhThanhTra', 'nguoiCapNhat']);
            }]);
        } else {
            $query->with(['ketQuaThanhTras.quyetDinhThanhTra', 'ketQuaThanhTras.nguoiCapNhat']);
        }


        if (isset($search_co_so_da_thay_doi_thong_tin) && $search_co_so_da_thay_doi_thong_tin === "true") {
            $query->whereHas('coSoSanXuats', function ($query) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) {
                    $query->whereHas('ketQuaThanhTra');
                    $query->whereRaw("ket_qua_thanh_tra_chungs.ten not like co_so_san_xuats.ten");
                });
            });
        }


        if (isset($search_khu_cong_nghiep)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_khu_cong_nghiep) {
                $query->whereIn('khu_cong_nghiep_id', $search_khu_cong_nghiep);
            });
        }
        if (isset($search_loai_co_so)) {

            $chitietcongsuat = ChiTietCongSuat::query()->whereIn('loai_hinh_id', $search_loai_co_so)->get();
            $query->whereHas('coSoSanXuats', function ($query) use ($chitietcongsuat) {
                $query->whereIn('id', $chitietcongsuat->pluck('co_so_san_xuat_id'));
            });
        }
        if (isset($search_tinh_thanh)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_tinh_thanh) {
                $query->whereIn('tinh_thanh_id', $search_tinh_thanh);
            });
        }
        if (isset($search_quan_huyen)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_quan_huyen) {
                $query->whereIn('quan_huyen_id', $search_quan_huyen);
            });
        }
        if (isset($search_luu_vuc_song)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_luu_vuc_song) {
                $query->whereIn('luu_vuc_song_id', $search_luu_vuc_song);
            });
        }
        if (isset($search_mien)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_mien) {
                $query->whereIn('mien_id', $search_mien);
            });
        }
        if (isset($search_vung_kinh_te_trong_diem)) {
            $query->whereHas('coSoSanXuats', function ($query) use ($search_vung_kinh_te_trong_diem) {
                $query->whereIn('vung_kinh_te_trong_diem_id', $search_vung_kinh_te_trong_diem);
            });
        }
        $donvidt = ChuyenDoiDonVi::query()->where('loai', 'dien_tich')->where('don_vi_goc', true)->first();

        switch ($order_column) {
            case 'ten':
                $query->orderBy('ten', $order_direction);
                break;
            case 'dia_chi_lien_he':
                $query->orderBy('dia_chi_lien_he', $order_direction);
                break;
        }


        $query->with('coSoSanXuats.khuCongNghiep');
        if ($request->headers->get('accept') != 'xlsx') {
            $data = $query->paginate($perPage, ['*'], 'page', $page);

            return  [
                'data' => $data,
                'perPage' => $perPage,
                'donvidt' => $donvidt,
                'order_column' => $order_column,
                'order_direction' => $order_direction,
            ];
        } else {
            $data = $query->get();
            return ['data' => $data];
        }
    }

    public function showFormAdd()
    {
        return view('cososanxuat.add');
    }

    public function traCuu()
    {

        $user = Auth::user();
        return view('cososanxuat.tracuu');
    }

    public function getLatLonByAddress(Request $request)
    {
        $diachi = $request->get('diachi');
        $latlon = $this->getLatLonByAddressText($diachi, false);
        return response()->json([
            'message' => 'Thành công',
            'result' => $latlon,
        ], 200, []);
    }

    public function add(Request $request)
    {
        $info = $request->get('coso');
        $congsuathd = $request->get('congsuathd');
        $latlon = $this->getLatLonByAddressText($info['dia_chi_hoat_dong'], false);
        if ($info['kinh_do'] == null || $info['vi_do'] == null) {
            $info['kinh_do'] = $latlon['long'];
            $info['vi_do'] = $latlon['lat'];
        }
        $tinh = TinhThanh::query()->where('id', $info['tinh_thanh_id'])->first();
        $info['vung_kinh_te_trong_diem_id'] = $tinh->vung_kinh_te_trong_diem_id;
        $user = Auth::user();
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'tinh_thanh_id' => 'required',
        ]);
        $info['ma'] = $tinh->ma . '-' . $info['ten'];
        $info['luu_vuc_song_id'] = $tinh->luu_vuc_song_id;
        if ($validator->fails()) {
            return response()->json([
                'message' => __('system.invalid'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        DB::beginTransaction();
        try {

            $info['created_by'] = $user->id;
            $info['updated_by'] = $user->id;
            $info['mien_id'] = $tinh->mien_id;
            $organization = \App\Organization::query()->create($info);
            $info['organization_id'] = $organization->id;
            $cososanxuat = CoSoSanXuat::query()->create($info);
            foreach ($congsuathd as $item) {
                ChiTietCongSuat::query()->create([
                    'co_so_san_xuat_id' => $cososanxuat->id,
                    'don_vi_id' => $item['don_vi_thiet_ke'],
                    'thong_so' => $item['thong_so_thiet_ke'],
                    'loai_hinh_id' => $item['loai_hinh_co_so'],
                    'don_vi_hoat_dong_id' => $item['don_vi_hoat_dong']['id'],
                    'thong_so_hoat_dong' => $item['thong_so_hoat_dong'],
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'Thành công',
                'result' => [],
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error',
                'result' => $exception->getMessage(),
            ], 500, []);
        }
    }

    public function showFormAddCoSo()
    {
        $tinhs = TinhThanh::where('trang_thai_dong_bo', 'da_dong_bo')->get();
        $huyens =  QuanHuyen::select('id', 'ma', 'ma_dinh_danh', 'ten', 'tinh_thanh_id')->whereNotNull('ma_dinh_danh')->get();
        $chuDauTus = ChuDauTu::select('id', 'ten', 'trang_thai_dong_bo')->has('organization')->with('organization')->get();
        return view('cososanxuat.formadd', ['usersystem' => Auth::user(), 'tinhs' => $tinhs,  'huyens' => $huyens, 'chudautus' => $chuDauTus]);
    }

    public function showUpdateCoSo($id)
    {
        $tinhs = TinhThanh::where('trang_thai_dong_bo', 'da_dong_bo')->get();
        $huyens =  QuanHuyen::select('id', 'ma', 'ma_dinh_danh', 'ten', 'tinh_thanh_id')->whereNotNull('ma_dinh_danh')->get();
        $chuDauTus = ChuDauTu::select('id', 'ten', 'trang_thai_dong_bo')->has('organization')->with('organization')->get();
        $coSoSanXuat =  CoSoSanXuat::with(['tinhThanhs', 'quanHuyens', 'phuongXas', 'loaiKhuCongNghiep', 'loaiHinhONhiem', 'loaiNganhNghe', 'khuCongNghiep', 'chuyenDoiDonVi'])->where('id', $id)->first();
        $organization =  $coSoSanXuat &&  $coSoSanXuat->organization_id ? Organization::where('id', $coSoSanXuat->organization_id)->first() : null;
        $chuDauTu = $organization && $organization->chu_dau_tu_id ? ChuDauTu::select('id', 'ten', 'trang_thai_dong_bo')->where('id', $organization->chu_dau_tu_id)->with('organization')->first() : collect();
        return view('cososanxuat.formupdate', ['usersystem' => Auth::user(), 'tinhs' => $tinhs,  'huyens' => $huyens, 'chudautus' => $chuDauTus, 'cososanxuat' => $coSoSanXuat, 'chudautu' => $chuDauTu]);
    }

    public function updateCoSoSanXuat(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'ten' => 'required|max:255',
        ]);
        if (!$data['ten']) {
            return response()->json([
                'message' => 'Tên cơ sở không được bỏ trống',
            ], 400, []);
        }
        if (!$data['organization_id']) {
            return response()->json([
                'message' => 'Chủ đầu tư không được bỏ trống',
            ], 400, []);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => __('system.invalid'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $dienTich = null;
        $luongNuoc = null;
        if (!empty($data['dien_tich']) && !empty($data['don_vi_dien_tich'])) {
            $dienTich = $data['don_vi_dien_tich']['ty_le'] * $data['dien_tich'];
        }
        if (!empty($data['luong_nuoc_su_dung']) && !empty($data['don_vi_nuoc_su_dung'])) {
            $luongNuoc = $data['don_vi_nuoc_su_dung']['ty_le'] * $data['luong_nuoc_su_dung'];
        }
        try {
            DB::beginTransaction();
            $cs = CoSoSanXuat::where('id', $id)->update([
                'ten' => $data['ten'],
                'dia_chi_lien_he' => $data['dia_chi_lien_he'],
                'dia_chi_hoat_dong' => $data['dia_chi_hoat_dong'],
                'organization_id' => $data['organization_id'],
                // 'created_by' =>  Auth::user()->id,
                'updated_by' =>  Auth::user()->id,
                'trang_thai_dong_bo' => 'chua_dong_bo',
                'loai_du_lieu' => 'du_lieu_moi',
                'nhien_lieu_chinh_su_dung' => $data['nhien_lieu_chinh_su_dung'],
                'hoa_chat_su_dung' => $data['hoa_chat_su_dung'],
                'nguyen_lieu_chinh_su_dung' => $data['nguyen_lieu_chinh_su_dung'],
                'ma_dinh_danh' => $data['ma_dinh_danh'],
                'loai_khu_cong_nghiep' => isset($data['loai_khu_cong_nghiep']) && $data['loai_khu_cong_nghiep'] && isset($data['loai_khu_cong_nghiep']['ma']) ? $data['loai_khu_cong_nghiep']['ma'] : null,
                'luong_nuoc_su_dung' => $luongNuoc,
                'dien_tich' => $dienTich,
                'nguon_nuoc_su_dung' => $data['nguon_nuoc_su_dung'],
                'kinh_do' => $data['kinh_do'],
                'vi_do' => $data['vi_do'],
                'so_nguoi_lao_dong' => $data['so_nguoi_lao_dong'],
                'khu_cong_nghiep_id' => isset($data['khu_cong_nghiep']) && $data['khu_cong_nghiep'] && isset($data['khu_cong_nghiep']['id']) ? $data['khu_cong_nghiep']['id'] : null,

                'ty_le_lap_day' => $data['ty_le_lap_day'] ?? null,
                'tong_dien_tich' => $data['tong_dien_tich'] ?? null,
                'chuyen_doi_don_vi_id' => $data['chuyen_doi_don_vi']['id'] ?? null,
                'dien_tich_dat_cn' => $data['dien_tich_dat_cn'] ?? null,
                'dien_tich_dat_cho_thue' => $data['dien_tich_dat_cho_thue'] ?? null,
                'dien_tich_dat_cay_xanh' => $data['dien_tich_dat_cay_xanh'] ?? null,
            ]);
            // dd($data);
            // ⭐ UPDATE vào bảng KetQuaThanhTraChung luôn
            KetQuaThanhTraChung::where('co_so_san_xuat_id', $id)->update([
                'ten'                      => $data['ten'],
                'dia_chi_lien_he'          => $data['dia_chi_lien_he'],
                'dia_chi_hoat_dong'        => $data['dia_chi_hoat_dong'],
                'khu_cong_nghiep_id'       => isset($data['khu_cong_nghiep']['id']) ? $data['khu_cong_nghiep']['id'] : null,
                // 'tinh_thanh_id'            => $data['tinh_thanh_id'],
                // 'quan_huyen_id'            => $data['quan_huyen_id'],
                'xa_phuong'                => $data['xa_phuong'] ?? null,
                'dien_tich'                => $dienTich,
                'luong_nuoc_su_dung'       => $luongNuoc,
                'so_nguoi_lao_dong'        => $data['so_nguoi_lao_dong'],
                'nguyen_lieu_chinh_su_dung' => $data['nguyen_lieu_chinh_su_dung'],
                'nhien_lieu_chinh_su_dung' => $data['nhien_lieu_chinh_su_dung'],
                'hoa_chat_su_dung'         => $data['hoa_chat_su_dung'],
                'nguon_nuoc_su_dung'       => $data['nguon_nuoc_su_dung'],
                'ty_le_lap_day'            => $data['ty_le_lap_day'] ?? null,
                'tong_dien_tich'           => $data['tong_dien_tich'] ?? null,
                'chuyen_doi_don_vi_id'     => $data['chuyen_doi_don_vi']['id'] ?? null,
                'dien_tich_dat_cn'         => $data['dien_tich_dat_cn'] ?? null,
                'dien_tich_dat_cho_thue'   => $data['dien_tich_dat_cho_thue'] ?? null,
                'dien_tich_dat_cay_xanh'   => $data['dien_tich_dat_cay_xanh'] ?? null,
            ]);
            CoSoSanXuatLoaiHinhONhiem::where('co_so_san_xuat_id', $id)->delete();
            if (isset($data['loai_hinh_o_nhiem']) && count($data['loai_hinh_o_nhiem']) > 0) {
                foreach ($data['loai_hinh_o_nhiem'] as $it) {
                    $it = (array)$it;
                    CoSoSanXuatLoaiHinhONhiem::create([
                        'co_so_san_xuat_id' => $id,
                        'loai_hinh_o_nhiem_id' => $it['id']
                    ]);
                }
            }
            CoSoSanXuatLoaiNganhNghe::where('co_so_id', $id)->delete();
            if (isset($data['loai_nganh_nghe']) && count($data['loai_nganh_nghe']) > 0) {
                foreach ($data['loai_nganh_nghe'] as $it) {
                    $it = (array)$it;
                    CoSoSanXuatLoaiNganhNghe::create([
                        'co_so_id' => $id,
                        'loai_nganh_nghe_id' => $it['id']
                    ]);
                }
            }
            DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $id)->delete();
            if (isset($data['phuong_xas']) && count($data['phuong_xas']) > 0) {
                foreach ($data['phuong_xas'] as $it) {
                    $it = (array)$it;
                    $quan = QuanHuyen::where('id', $it['quan_huyen_id'])->first();
                    if ($quan) {
                        DiaDiemCoSoSanXuat::create([
                            'co_so_san_xuat_id' => $id,
                            'phuong_xa_id' => $it['id'],
                            'quan_huyen_id' => $it['quan_huyen_id'],
                            'tinh_thanh_id' => $quan->tinh_thanh_id
                        ]);
                    }
                }
            }
            if (isset($data['quan_huyens']) && count($data['quan_huyens']) > 0) {
                foreach ($data['quan_huyens'] as $it) {
                    $it = (array)$it;
                    $quan = QuanHuyen::where('id', $it['id'])->first();
                    $tinh = TinhThanh::where('id', $it['tinh_thanh_id'])->first();
                    $check = DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $id)->where('quan_huyen_id', $quan->id)->first();
                    if ($tinh && !$check) {
                        DiaDiemCoSoSanXuat::create([
                            'co_so_san_xuat_id' => $id,
                            'quan_huyen_id' => $it['id'],
                            'tinh_thanh_id' => $tinh->id
                        ]);
                    }
                }
            }
            if (isset($data['tinh_thanhs']) && count($data['tinh_thanhs']) > 0) {
                foreach ($data['tinh_thanhs'] as $it) {
                    $it = (array)$it;
                    $tinh = TinhThanh::where('id', $it['id'])->first();
                    $check = DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $id)->where('tinh_thanh_id', $it['id'])->first();
                    if ($tinh && !$check) {
                        DiaDiemCoSoSanXuat::create([
                            'co_so_san_xuat_id' => $id,
                            'tinh_thanh_id' =>  $tinh->id
                        ]);
                    }
                }
            }
            DB::commit();
            return response(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function addCoSoSanXuat(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'ten' => 'required|max:255',
        ]);
        if (!$data['ten']) {
            return response()->json([
                'message' => 'Tên cơ sở không được bỏ trống',
            ], 400, []);
        }
        if (!$data['organization_id']) {
            return response()->json([
                'message' => 'Chủ đầu tư không được bỏ trống',
            ], 400, []);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => __('system.invalid'),
                'result' => $validator->errors(),
            ], 400, []);
        }
        $dienTich = null;
        $luongNuoc = null;
        if (!empty($data['dien_tich']) && !empty($data['don_vi_dien_tich'])) {
            // CASE cũ
            $dienTich = $data['don_vi_dien_tich']['ty_le'] * $data['dien_tich'];
        } elseif (!empty($data['tong_dien_tich']) && !empty($data['chuyen_doi_don_vi_id'])) {
            // CASE KCN: dùng tổng diện tích + đơn vị mới
            $donVi = ChuyenDoiDonVi::find($data['chuyen_doi_don_vi_id']);
            if ($donVi) {
                $dienTich = $donVi->ty_le * $data['tong_dien_tich'];
            }
        }
        if (!empty($data['luong_nuoc_su_dung']) && !empty($data['don_vi_nuoc_su_dung'])) {
            $luongNuoc = $data['don_vi_nuoc_su_dung']['ty_le'] * $data['luong_nuoc_su_dung'];
        }
        try {
            DB::beginTransaction();
            $cs = CoSoSanXuat::create([
                'ten' => $data['ten'],
                'dia_chi_lien_he' => $data['dia_chi_lien_he'],
                'dia_chi_hoat_dong' => $data['dia_chi_hoat_dong'],
                'organization_id' => $data['organization_id'],
                'created_by' =>  Auth::user()->id,
                'updated_by' =>  Auth::user()->id,
                'trang_thai_dong_bo' => 'chua_dong_bo',
                'loai_du_lieu' => 'du_lieu_moi',
                'nhien_lieu_chinh_su_dung' => $data['nhien_lieu_chinh_su_dung'],
                'hoa_chat_su_dung' => $data['hoa_chat_su_dung'],
                'nguyen_lieu_chinh_su_dung' => $data['nguyen_lieu_chinh_su_dung'],
                'ma_dinh_danh' => $data['ma_dinh_danh'],
                'loai_khu_cong_nghiep' => isset($data['loai_khu_cong_nghiep']) && $data['loai_khu_cong_nghiep'] && isset($data['loai_khu_cong_nghiep']['ma']) ? $data['loai_khu_cong_nghiep']['ma'] : null,
                'luong_nuoc_su_dung' => $luongNuoc,
                'dien_tich' => $dienTich,
                'nguon_nuoc_su_dung' => $data['nguon_nuoc_su_dung'],
                'kinh_do' => $data['kinh_do'],
                'vi_do' => $data['vi_do'],
                'so_nguoi_lao_dong' => $data['so_nguoi_lao_dong'],
                'khu_cong_nghiep_id' => isset($data['khu_cong_nghiep']) && $data['khu_cong_nghiep'] && isset($data['khu_cong_nghiep']['id']) ? $data['khu_cong_nghiep']['id'] : null,

                'ty_le_lap_day'            => $data['ty_le_lap_day']            ?? null,
                'tong_dien_tich'           => $data['tong_dien_tich']           ?? null,
                'chuyen_doi_don_vi_id'     => $data['chuyen_doi_don_vi_id']     ?? null,
                'dien_tich_dat_cn'         => $data['dien_tich_dat_cn']         ?? null,
                'dien_tich_dat_cho_thue'   => $data['dien_tich_dat_cho_thue']   ?? null,
                'dien_tich_dat_cay_xanh'   => $data['dien_tich_dat_cay_xanh']   ?? null,
            ]);
            if (isset($data['loai_hinh_o_nhiem']) && count($data['loai_hinh_o_nhiem']) > 0) {
                foreach ($data['loai_hinh_o_nhiem'] as $it) {
                    $it = (array)$it;
                    CoSoSanXuatLoaiHinhONhiem::create([
                        'co_so_san_xuat_id' => $cs->id,
                        'loai_hinh_o_nhiem_id' => $it['id']
                    ]);
                }
            }
            if (isset($data['loai_nganh_nghes']) && count($data['loai_nganh_nghes']) > 0) {
                foreach ($data['loai_nganh_nghes'] as $it) {
                    $it = (array)$it;
                    CoSoSanXuatLoaiNganhNghe::create([
                        'co_so_id' => $cs->id,
                        'loai_nganh_nghe_id' => $it['id']
                    ]);
                }
            }
            if (isset($data['phuong_xas']) && count($data['phuong_xas']) > 0) {
                foreach ($data['phuong_xas'] as $it) {
                    $it = (array)$it;
                    $quan = QuanHuyen::where('id', $it['quan_huyen_id'])->first();
                    if ($quan) {
                        DiaDiemCoSoSanXuat::create([
                            'co_so_san_xuat_id' => $cs->id,
                            'phuong_xa_id' => $it['id'],
                            'quan_huyen_id' => $it['quan_huyen_id'],
                            'tinh_thanh_id' => $quan->tinh_thanh_id
                        ]);
                    }
                }
            }
            if (isset($data['quan_huyens']) && count($data['quan_huyens']) > 0) {
                foreach ($data['quan_huyens'] as $it) {
                    $it = (array)$it;
                    $quan = QuanHuyen::where('id', $it['id'])->first();
                    $tinh = TinhThanh::where('id', $it['tinh_thanh_id'])->first();
                    $check = DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $cs->id)->where('quan_huyen_id', $quan->id)->first();
                    if ($tinh && !$check) {
                        DiaDiemCoSoSanXuat::create([
                            'co_so_san_xuat_id' => $cs->id,
                            'quan_huyen_id' => $it['id'],
                            'tinh_thanh_id' => $tinh->id
                        ]);
                    }
                }
            }
            if (isset($data['tinh_thanhs']) && count($data['tinh_thanhs']) > 0) {
                foreach ($data['tinh_thanhs'] as $it) {
                    $it = (array)$it;
                    $tinh = TinhThanh::where('id', $it['id'])->first();
                    $check = DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $cs->id)->where('tinh_thanh_id', $it['id'])->first();
                    if ($tinh && !$check) {
                        DiaDiemCoSoSanXuat::create([
                            'co_so_san_xuat_id' => $cs->id,
                            'tinh_thanh_id' =>  $tinh->id
                        ]);
                    }
                }
            }
            DB::commit();
            return response(['message' => 'Success', 'data' => $cs], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function showFormUpdate($id)
    {
        $toChuc = \App\Organization::query()
            ->with([
                'chuDauTu.tinhThanh',
                'chuDauTu.quanHuyen',
                'coSoSanXuats.quanHuyen',
                'coSoSanXuats.khuCongNghiep',
                'coSoSanXuats.tinhThanh',
                'coSoSanXuats.coQuanKinhDoanh',
                'coSoSanXuats.coQuanDauTu',
                'coSoSanXuats.chiTietCongSuat.loaiHinh',
                'coSoSanXuats.chiTietCongSuat.donVi',
                'coSoSanXuats.chiTietCongSuat.donViHD',
                'coSoSanXuats.loaiHinhONhiem',
                'coSoSanXuats.loaiNganhNghe',
                'coSoSanXuats.loaiKhuCongNghiep',
                'coSoSanXuats.tinhThanhs',
                'coSoSanXuats.quanHuyens',
                'coSoSanXuats.phuongXas',
            ])
            ->find($id);
        // dd($toChuc->coSoSanXuats[0]);
        return view('cososanxuat.edit', [
            'to_chuc' =>  $toChuc,
            'ket_qua_thanh_tra' => KetQuaThanhTra::where('organization_id', $id)->with(['quyetDinhThanhTra', 'nguoiCapNhat'])->get(),
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $info = $request->get('co_so_san_xuat');
    //     $cososanxuats = $info['co_so_san_xuats'];

    //     $user = Auth::user();

    //     $validator = Validator::make($info, [
    //         'ten' => 'required',
    //         'co_so_san_xuat' => 'nullable||array',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => __('system.invalid'),
    //             'result' => $validator->errors(),
    //         ], 400, []);
    //     }
    //     $tochuc = \App\Organization::find($id);

    //     DB::beginTransaction();
    //     try {
    //         $tochuc->update([
    //             'ten' => $info['ten'],
    //             'dia_chi_lien_he' => $info['dia_chi_lien_he'],
    //             'chu_dau_tu_id' => $info['chu_dau_tu_id']
    //         ]);
    //         $cososx = CoSoSanXuat::where('organization_id', $id)->get();
    //         if (!empty($cososanxuats)) {
    //             foreach ($cososanxuats as $cososanxuat) {
    //                 // $cososanxuat['quan_huyen_id'] = empty($cososanxuat['quan_huyen']) ? null : $cososanxuat['quan_huyen']['id'];
    //                 // $cososanxuat['khu_cong_nghiep_id'] = empty($cososanxuat['khu_cong_nghiep']) ? null : $cososanxuat['khu_cong_nghiep']['id'];
    //                 // $cososanxuat['tinh_thanh_id'] = empty($cososanxuat['tinh_thanh']) ? null : $cososanxuat['tinh_thanh']['id'];

    //                 $cososanxuat['co_quan_cap_giay_chung_nhan_kinh_doanh'] = empty($cososanxuat['co_quan_kinh_doanh']) ? null : $cososanxuat['co_quan_kinh_doanh']['id'];
    //                 $cososanxuat['co_quan_cap_giay_chung_nhan_dau_tu'] = empty($cososanxuat['co_quan_dau_tu']) ? null : $cososanxuat['co_quan_dau_tu']['id'];
    //                 if ($cososanxuat['so_nguoi_lao_dong'] < 0) {
    //                     $cososanxuat['so_nguoi_lao_dong'] = 0;
    //                 }

    //                 if (!empty($cososanxuat['dien_tich']) && !empty($cososanxuat['don_vi_dien_tich'])) {
    //                     $cososanxuat['dien_tich'] = $cososanxuat['don_vi_dien_tich']['ty_le'] * $cososanxuat['dien_tich'];
    //                 }

    //                 if (!empty($cososanxuat['luong_nuoc_su_dung']) && !empty($cososanxuat['donvinc'])) {
    //                     $cososanxuat['luong_nuoc_su_dung'] = $cososanxuat['luong_nuoc_su_dung'] * $cososanxuat['donvinc']['ty_le'];
    //                 }

    //                 $tinh = \App\TinhThanh::query()->where('id', $cososanxuat['tinh_thanh_id'])->first();

    //                 $latlon = $this->getLatLonByAddressText($cososanxuat['dia_chi_hoat_dong'], false);
    //                 $cososanxuat['updated_by'] = $user->id;
    //                 if (!empty($latlon['long']) && !empty($latlon['lat'])) {
    //                     $cososanxuat['kinh_do'] = $latlon['long'];
    //                     $cososanxuat['vi_do'] = $latlon['lat'];
    //                 }
    //                 unset($cososanxuat['loai_khu_cong_nghiep']);
    //                 $cososx->where('id', $cososanxuat['id'])->first()->update($cososanxuat);
    //                 ChiTietCongSuat::query()->where('co_so_san_xuat_id', $cososanxuat['id'])->delete();

    //                 if (!empty($cososanxuat['chi_tiet_cong_suat'])) {
    //                     $congsuathd = $cososanxuat['chi_tiet_cong_suat'];
    //                     foreach ($congsuathd as $item) {
    //                         ChiTietCongSuat::query()->create([
    //                             'don_vi_id' => empty($item['don_vi']) ? null : $item['don_vi']['id'],
    //                             'thong_so' => $item['thong_so'],
    //                             'loai_hinh_id' => empty($item['loai_hinh']) ? null : $item['loai_hinh']['id'],
    //                             'don_vi_hoat_dong_id' => empty($item['don_vi_h_d']) ? null : $item['don_vi_h_d']['id'],
    //                             'thong_so_hoat_dong' => $item['thong_so_hoat_dong'],
    //                             'co_so_san_xuat_id' => $cososanxuat['id'],
    //                         ]);
    //                     }
    //                 }
    //                 CoSoSanXuatLoaiHinhONhiem::where('co_so_san_xuat_id', $cososanxuat['id'])->delete();
    //                 if ($cososanxuat['loaiONhiem'] && count($cososanxuat['loaiONhiem']) > 0) {
    //                     foreach ($cososanxuat['loaiONhiem'] as $loaiONhiem) {
    //                         CoSoSanXuatLoaiHinhONhiem::create([
    //                             'co_so_san_xuat_id' => $cososanxuat['id'],
    //                             'loai_hinh_o_nhiem_id' => $loaiONhiem['id']
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'message' => 'Thành công',
    //             'result' => [],
    //         ], 200, []);
    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Lỗi hệ thống',
    //             'result' => [],
    //         ], 500, []);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $info = $request->get('co_so_san_xuat');
        $cososanxuats = $info['co_so_san_xuats'] ?? [];

        $user = Auth::user();

        $validator = Validator::make($info, [
            'ten' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('system.invalid'),
                'result' => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            /** ==============================
             * 1️⃣ Cập nhật tổ chức (Organization)
             * ============================== */
            $tochuc = \App\Organization::findOrFail($id);
            $tochuc->update([
                'ten' => $info['ten'],
                'dia_chi_lien_he' => $info['dia_chi_lien_he'],
                'chu_dau_tu_id' => $info['chu_dau_tu_id'] ?? null,
                'updated_at' => now(),
            ]);

            /** ==============================
             * 2️⃣ Cập nhật danh sách Cơ sở sản xuất
             * ============================== */
            foreach ($cososanxuats as $cososanxuat) {
                $cs = \App\CoSoSanXuat::find($cososanxuat['id']);
                if (!$cs) continue;

                /** 👉 Xử lý dữ liệu cơ bản */
                $cososanxuat['co_quan_cap_giay_chung_nhan_kinh_doanh'] =
                    empty($cososanxuat['co_quan_kinh_doanh'])
                    ? null
                    : $cososanxuat['co_quan_kinh_doanh']['id'];

                $cososanxuat['co_quan_cap_giay_chung_nhan_dau_tu'] =
                    empty($cososanxuat['co_quan_dau_tu'])
                    ? null
                    : $cososanxuat['co_quan_dau_tu']['id'];

                if ($cososanxuat['so_nguoi_lao_dong'] < 0) {
                    $cososanxuat['so_nguoi_lao_dong'] = 0;
                }

                // Tính lại diện tích & lưu lượng
                if (!empty($cososanxuat['dien_tich']) && !empty($cososanxuat['don_vi_dien_tich'])) {
                    $cososanxuat['dien_tich'] = $cososanxuat['don_vi_dien_tich']['ty_le'] * $cososanxuat['dien_tich'];
                }

                if (!empty($cososanxuat['luong_nuoc_su_dung']) && !empty($cososanxuat['donvinc'])) {
                    $cososanxuat['luong_nuoc_su_dung'] = $cososanxuat['luong_nuoc_su_dung'] * $cososanxuat['donvinc']['ty_le'];
                }

                /** 👉 Tự động cập nhật kinh độ - vĩ độ nếu có địa chỉ hoạt động */
                $latlon = $this->getLatLonByAddressText($cososanxuat['dia_chi_hoat_dong'], false);
                if (!empty($latlon['long']) && !empty($latlon['lat'])) {
                    $cososanxuat['kinh_do'] = $latlon['long'];
                    $cososanxuat['vi_do'] = $latlon['lat'];
                }

                $cososanxuat['updated_by'] = $user->id;

                /** ==============================
                 * 3️⃣ Cập nhật hành chính: Tỉnh / Huyện / Xã
                 * ============================== */
                // Nếu có tỉnh đơn (tinh_thanh_id) trong payload thì update trực tiếp
                // if (!empty($cososanxuat['tinh_thanh_id'])) {
                //     $cososanxuat['tinh_thanh_id'] = $cososanxuat['tinh_thanh_id'];
                // } else {
                //     // Nếu có mảng tinh_thanhs -> sync
                //     if (isset($cososanxuat['tinh_thanhs']) && count($cososanxuat['tinh_thanhs']) > 0) {
                //         $cs->tinhThanhs()->sync(collect($cososanxuat['tinh_thanhs'])->pluck('id')->toArray());
                //     }
                // }
                // Nếu có tỉnh đơn (tinh_thanh_id) trong payload thì update trực tiếp
                if (isset($cososanxuat['tinh_thanhs']) && count($cososanxuat['tinh_thanhs']) > 0) {
                    // Nếu có nhiều tỉnh được chọn -> sync
                    $cs->tinhThanhs()->sync(collect($cososanxuat['tinh_thanhs'])->pluck('id')->toArray());
                } elseif (!empty($cososanxuat['tinh_thanh_id'])) {
                    // Nếu chỉ có 1 tỉnh -> gán trực tiếp (trường hợp cơ sở chỉ có 1 tỉnh duy nhất)
                    $cs->tinh_thanh_id = $cososanxuat['tinh_thanh_id'];
                    $cs->save();
                }

                if (isset($cososanxuat['quan_huyens']) && count($cososanxuat['quan_huyens']) > 0) {
                    $cs->quanHuyens()->sync(collect($cososanxuat['quan_huyens'])->pluck('id')->toArray());
                }

                if (isset($cososanxuat['phuong_xas']) && count($cososanxuat['phuong_xas']) > 0) {
                    $cs->phuongXas()->sync(collect($cososanxuat['phuong_xas'])->pluck('id')->toArray());
                }

                /** ==============================
                 * 4️⃣ Cập nhật thông tin chính CoSoSanXuat
                 * ============================== */
                $updateData = [
                    'ten' => $cososanxuat['ten'] ?? null,
                    'dia_chi_lien_he' => $cososanxuat['dia_chi_lien_he'] ?? null,
                    'dia_chi_hoat_dong' => $cososanxuat['dia_chi_hoat_dong'] ?? null,
                    'kinh_do' => $cososanxuat['kinh_do'] ?? null,
                    'vi_do' => $cososanxuat['vi_do'] ?? null,
                    'dien_tich' => $cososanxuat['dien_tich'] ?? null,
                    'luong_nuoc_su_dung' => $cososanxuat['luong_nuoc_su_dung'] ?? null,
                    'so_nguoi_lao_dong' => $cososanxuat['so_nguoi_lao_dong'] ?? null,
                    'updated_by' => $user->id,
                ];

                // loại bỏ null tránh ghi đè dữ liệu cũ
                $updateData = array_filter($updateData, fn($v) => $v !== null);

                $cs->update($updateData);

                /** ==============================
                 * 5️⃣ Cập nhật chi tiết công suất & loại ô nhiễm
                 * ============================== */
                ChiTietCongSuat::where('co_so_san_xuat_id', $cs->id)->delete();
                if (!empty($cososanxuat['chi_tiet_cong_suat'])) {
                    foreach ($cososanxuat['chi_tiet_cong_suat'] as $item) {
                        ChiTietCongSuat::create([
                            'don_vi_id' => $item['don_vi']['id'] ?? null,
                            'thong_so' => $item['thong_so'] ?? null,
                            'loai_hinh_id' => $item['loai_hinh']['id'] ?? null,
                            'don_vi_hoat_dong_id' => $item['don_vi_h_d']['id'] ?? null,
                            'thong_so_hoat_dong' => $item['thong_so_hoat_dong'] ?? null,
                            'co_so_san_xuat_id' => $cs->id,
                        ]);
                    }
                }

                CoSoSanXuatLoaiHinhONhiem::where('co_so_san_xuat_id', $cs->id)->delete();
                if (!empty($cososanxuat['loaiONhiem'])) {
                    foreach ($cososanxuat['loaiONhiem'] as $loaiONhiem) {
                        CoSoSanXuatLoaiHinhONhiem::create([
                            'co_so_san_xuat_id' => $cs->id,
                            'loai_hinh_o_nhiem_id' => $loaiONhiem['id'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Cập nhật thành công',
                'result' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Lỗi hệ thống',
                'result' => $e->getMessage(),
            ], 500);
        }
    }


    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cososanxuat = CoSoSanXuat::where('id', $id)->first();
            if ($cososanxuat) {
                ChiTietCongSuat::query()->where('co_so_san_xuat_id', $cososanxuat->id)->delete();
            }
            $ketquathanhtra = $cososanxuat
                ? KetQuaThanhTra::where('organization_id', $cososanxuat->id)->get()
                : KetQuaThanhTra::where('organization_id', $id)->get();

            $ketquathanhtrachungs = \App\KetQuaThanhTraChung::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->get();
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
            // KetQuaThanhTra::where('organization_id', $cososanxuat->organization_id)->delete();
            KetQuaThanhTra::where('organization_id', $id)->delete();
            if ($cososanxuat) {
                \App\KetQuaThanhTraChung::where('co_so_san_xuat_id', $cososanxuat->id)->delete();
                CoSoSanXuat::where('id', $id)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);

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


    public function getCoSoSanXuat(Request $request)
    {
        $mien_id = $request->get('mien_id');
        $paging = $request->get('paging', false);
        $tinh_id = $request->get('tinh_id');
        $luu_vuc_id = $request->get('luuvucsong_id');
        $search = $request->get('search');
        $nhap_lieu_cssx = $request->get('nhap_lieu_cssx');
        if ($nhap_lieu_cssx) {
            $query = \App\Organization::query();
            $query->with('coSoSanXuats.khuCongNghiep', 'ketQuaThanhTras.nguoiCapNhat', 'ketQuaThanhTras.quyetDinhThanhTra');
        } else {
            $query = CoSoSanXuat::query();
            $query->with([
                'khuCongNghiep',
                'mien',
                'tinhThanh',
            ]);
        }

        if (isset($mien_id) && $mien_id != 0) {
            $query->where('mien_id', $mien_id);
        }
        if (isset($tinh_id) && $tinh_id != 0) {
            $query->where('tinh_thanh_id', $tinh_id);
        }
        if (isset($luu_vuc_id) && $luu_vuc_id != 0) {
            $query->where('luu_vuc_song_id', $luu_vuc_id);
        }
        if (isset($search) && $search != 0) {
            $query->where('id', $search);
        }

        if ($paging) {
            $perPage = $request->get('perpage', 10);
            $page = $request->get('page', 1);
            $data = $query->paginate($perPage, ['*'], 'page', $page);
        } else {
            $data = $query->get();
        }
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }



    public function asynOrganization(Request $request)
    {
        $search = $request->get('search');
        if (isset($search)) {
            return response()->json([
                'message' => 'Thành công',
                'result' => \App\Organization::query()->where('ten', 'ilike', "%{$search}%")->orderBy('created_at')->get(),
            ], 200, []);
        } else {
            return response()->json([
                'message' => 'Thành công',
                'result' => Organization::query()->orderBy('created_at')->get(),
            ], 200, []);
        }
    }

    public function coSoSanXuat(Request $request)
    {
        $to_chuc_id = $request->get('to_chuc_id');

        $search = $request->get('search');
        $query = CoSoSanXuat::query();

        if (isset($to_chuc_id)) {
            $query->where('organization_id', $to_chuc_id);
        }
        $query->with(['loaiNganhNghe', 'loaiHinhONhiem', 'chiTietCongSuat.loaiHinhCoSo', 'chiTietCongSuat.donViThietKe', 'chiTietCongSuat.donViHoatDong', 'quanHuyen', 'tinhThanh', 'khuCongNghiep', 'coQuanKinhDoanh', 'coQuanDauTu', 'tinhThanhs', 'quanHuyens', 'phuongXas', 'loaiKhuCongNghiep', 'chuyenDoiDonVi']);
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function xuatExcel(Request $request, $id)
    {
        $cosothanhtra = \App\Organization::query()
            ->with('coSoSanXuats.khuCongNghiep', 'coSoSanXuats.quanHuyen', 'coSoSanXuats.tinhThanh')
            ->find($id);
        $donViGoc = $this->getDataByName(\App\ChuyenDoiDonVi::class)->where('don_vi_goc', true)->whereIn('loai', ['dien_tich', 'luu_luong_nuoc_thai']);
        $donViGocDienTich = $donViGoc->firstWhere('loai', 'dien_tich');
        $donViGocLuuLuongNuocThai = $donViGoc->firstWhere('loai', 'luu_luong_nuoc_thai');
        $query = KetQuaThanhTra::query()
            ->where('organization_id', $id)
            ->with(['quyetDinhThanhTra']);
        $tungay = $request->get('tungay');
        $denngay = $request->get('denngay');
        if (empty($tungay)) {
            $tungay = Carbon::now()->addYears(-10);
        } else {
            $tungay = Carbon::createFromFormat(config('app.format_date'), $tungay)->startOfDay();
        }
        if (empty($denngay)) {
            $denngay = Carbon::now();
        } else {
            $denngay = Carbon::createFromFormat(config('app.format_date'), $denngay)->startOfDay();
        }
        $ketquathanhtra = $query->where('ngay_thanh_tra', '>=', $tungay)->where('ngay_thanh_tra', '<=', $denngay)->get();

        $excelFile = public_path() . '/report/bao_cao_chi_tiet_co_so_san_xuat.xlsx';

        \Excel::load($excelFile, function ($doc) use ($cosothanhtra, $donViGocDienTich, $donViGocLuuLuongNuocThai, $ketquathanhtra, $tungay, $denngay) {
            $doc->sheet('Thông tin chung', function ($sheet) use ($cosothanhtra, $donViGocDienTich, $donViGocLuuLuongNuocThai) {
                $this->setValue($sheet, 'B3', $cosothanhtra->ten);
                $this->setValue($sheet, 'B4', $cosothanhtra->dia_chi_lien_he);
                $cosothanhtra->coSoSanXuats->each(function ($item, $key) use ($sheet, $donViGocDienTich, $donViGocLuuLuongNuocThai) {
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
            $doc->sheet('Kết quả thanh tra', function ($sheet) use ($ketquathanhtra, $tungay, $denngay) {
                $this->setValue($sheet, 'C2', $tungay->format(config('app.format_date')));
                $this->setValue($sheet, 'C3', $denngay->format(config('app.format_date')));
                $ketquathanhtra->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 5), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 5), $item->quyetDinhThanhTra->so_quyet_dinh);
                    $this->setValue($sheet, 'C' . strval($key + 5), $item->so_quyet_dinh);
                    $this->setValue($sheet, 'D' . strval($key + 5), $item->ngay_thanh_tra);
                });
            });
        })->download('xlsx');
    }

    public function deleteDiaDiemHoatDong($id)
    {
        DB::beginTransaction();
        try {

            $cososanxuat = CoSoSanXuat::find($id);
            $tochuc = Organization::with('coSoSanXuats')->find($cososanxuat->organization_id);
            if (count($tochuc->coSoSanXuats) > 1) {
                ChiTietCongSuat::query()->where('co_so_san_xuat_id', $cososanxuat->id)->delete();
                $ketquathanhtra = KetQuaThanhTra::where('organization_id', $tochuc->id)->get();
                $ketquathanhtrachungs = \App\KetQuaThanhTraChung::where('co_so_san_xuat_id', $cososanxuat->id)->get();
                \App\KetQuaThanhTraChungLoaiHinhHoatDong::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                \App\KetQuaThanhTraThuTucHanhChinh::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();

                \App\ThongBaoQuyetDinhThanhTra::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->delete();
                $quyetdinhxuphat = \App\QuyetDinhXuPhat::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->get();
                // \App\ThongBaoQuyetDinhThanhTra::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                // $quyetdinhxuphat = \App\QuyetDinhXuPhat::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->get();
                \App\KetQuaThanhTraNuocThai::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                \App\KetQuaThanhTraKhiThai::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                \App\KetQuaThanhTraChatThaiRanSinhHoat::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                \App\KetQuaThanhTraChatThaiRanCNTT::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                \App\KetQuaThanhTraChatThaiNguyHai::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();
                \App\HanhViViPham::whereIn('quyet_dinh_xu_phat_id', $quyetdinhxuphat->pluck('id'))->delete();
                \App\QuyetDinhXuPhat::whereIn('ket_qua_thanh_tra_id', $ketquathanhtra->pluck('id'))->delete();
                // \App\QuyetDinhXuPhat::whereIn('ket_qua_thanh_tra_chung_id', $ketquathanhtrachungs->pluck('id'))->delete();

                \App\KetQuaThanhTraChung::where('co_so_san_xuat_id', $cososanxuat->id)->delete();
                $cososanxuat->delete();
                DB::commit();
                return response()->json([
                    'message' => 'Thành công',
                    'result' => [],
                ], 200, []);
            } else {
                return response()->json([
                    'message' => 'Không thể xóa cơ sở thanh tra có 1 địa điểm hoạt động.',
                    'result' => [],
                ], 500, []);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Lỗi hệ thống' . $e->getMessage(),
                'result' => [],
            ], 500, []);
        }
    }
    public function showDanhSachCoSoSanXuat()
    {
        return view('cososanxuat.showdanhsach');
    }
    public function getDanhSachCoSo(Request $request)
    {
        // $query = CoSoSanXuat::with(['organization.chuDauTu', 'tinhThanhs', 'diemTramQtmtTheoTinh']);
        $query = CoSoSanXuat::with(['organization.chuDauTu', 'tinhThanhs', 'tinhThanh', 'tinhThanh.diemTramQtmtTheoTinh']);
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $trangThaiDongBo = $request->get('dong_bo');
        if ($search) {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ma_dinh_danh', 'ilike', "%{$search}%");
            });
        }
        if ($trangThaiDongBo) {
            $query->where('trang_thai_dong_bo', $trangThaiDongBo);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function detailCoSoSanXuat($id)
    {
        $data = CoSoSanXuat::where('id', $id)->with(['organization.chuDauTu', 'coQuanDauTu', 'mien', 'khuCongNghiep', 'phuongXas', 'tinhThanhs', 'quanHuyens', 'luuVucSong', 'vungKinhTeTrongDiem', 'coQuanKinhDoanh', 'chiTietCongSuat', 'loaiCoSo', 'loaiHinhONhiem', 'loaiNganhNghe'])->first();
        // dd($data);
        return view('cososanxuat.detailcososanxuat', ['data' => json_encode($data)]);
    }

    private function getToken()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  'https://api.cebid.vn/CSDL/quantridulieu/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'key: wqB4WakPOjfqia7VsXwB4jclr1ftJo52'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = (array)json_decode($response);
        return $response['access_token'];
    }

    // public function showKetQuaQTMT($id)
    // {
    //     @set_time_limit(0);

    //     $token   = $this->getToken();
    //     $client  = new Client([
    //         'base_uri'     => 'https://api.cebid.vn',
    //         'verify'       => false,   
    //         'timeout'      => 0,      
    //         'http_errors'  => false, 
    //     ]);

    //     $payload = [
    //         "bool" => [
    //             "must" => [
    //                 ["match" => ["site" => "csdl_mt"]],
    //             ],
    //         ],
    //     ];

    //     $all           = [];
    //     $resumption    = 0;
    //     $visitedTokens = [];
    //     $maxLoops      = 1000;

    //     for ($i = 0; $i < $maxLoops; $i++) {
    //         $resp = $client->post("/CSDL/quantridulieu/T_KetQuaQTMT?resumptionToken={$resumption}", [
    //             'headers' => [
    //                 'Authorization' => "Bearer {$token}",
    //                 'Content-Type'  => 'application/json',
    //                 'key'           => 'kdQw8czmVzwL9BvzSsa2m2TeeS0bQb7k',
    //             ],
    //             'json' => $payload,
    //         ]);

    //         $data = json_decode((string) $resp->getBody(), true) ?: [];
    //         $rows = $data['data'] ?? [];
    //         if (!empty($rows)) {
    //             $all = array_merge($all, $rows);
    //         }

    //         $hasMore   = ($data['isResumptionToken'] ?? false) || ($data['hasMore'] ?? false);
    //         $nextToken = $data['resumptionToken'] ?? null;

    //         if (!$hasMore || empty($nextToken) || in_array($nextToken, $visitedTokens, true)) {
    //             break;
    //         }
    //         $visitedTokens[] = $nextToken;
    //         $resumption      = $nextToken;
    //     }

    //     // dd($id, $token, $all);
    //     return view('ketquaqtmt.showdanhsach', [
    //         'data' => json_encode($all),
    //         'id' => $id,
    //     ]);
    // }

    public function showKetQuaQTMT($id)
    {
        @set_time_limit(0);

        $records = KetQuaQtmt::where('ma_tram', $id)
            ->orderByDesc('nam_quan_trac')
            ->orderByDesc('thang_quan_trac')
            ->get([
                'ma_dinh_danh',
                'ma_tram',
                'ten_tram',
                'thong_so_ma',
                'thong_so_ten',
                'gia_tri_quan_trac',
                'don_vi_ten',
                'quy_chuan_ma',
                'quy_chuan_ten',
                'loai_hinh_qtmt',
                'loai_so_lieu_qtmt',
                'nam_quan_trac',
                'thang_quan_trac',
                'trang_thai',
                'nguon_tham_chieu',
                'modified_at',
                'gia_tri_gioi_han_max'
            ]);

        return view('ketquaqtmt.showdanhsach', [
            'data' => json_encode($records),
            'id'   => $id,
        ]);
    }
}
