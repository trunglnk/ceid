<?php

namespace App\Http\Controllers;

use App\CheDoThanhTra;
use App\ChuDauTu;
use App\CoQuanToChuc;
use App\CoSoSanXuat;
use App\CoSoSanXuatLoaiHinhONhiem;
use App\CoSoSanXuatLoaiNganhNghe;
use App\CoSoSanXuatQuocGia;
use App\DanhMucThongSoVuotChuan;
use App\LoaiHinhCoSo;
use App\LoaiHinhOiNhiem;
use App\LoaiKhuCongNghiep;
use App\LoaiThuTucHanhChinh;
use App\LuuVucSong;
use App\Mien;
use App\Organization;
use App\QuanHuyen;
use App\PhuongXa;
use App\ThoiGianDongBo;
use App\TinhThanh;
use App\DanhMucHanhViViPham;
use App\DiaDiemCoSoSanXuat;
use App\DiemTramQTMT;
use App\DongBoMtqgLog;
use App\KetQuaQtmt;
use App\KetQuaThanhTra;
use App\LoaiThongSo;
use App\Muc;
use App\NghiDinh;
use App\QuyetDinhThanhTra;
use App\QuyetDinhXuPhat;
use App\TinhThanhLuuVucSong;
use App\TinhThanhQuyetDinhThanhLap;
use App\Traits\DongBoMtqgLogger;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class MoiTruongQuocGiaController extends Controller
{
    use DongBoMtqgLogger;
    protected $API_MTQG = '';
    protected $API_CEBID = '';
    public function __construct()
    {
        $this->API_MTQG = config('app.api_mtqg');
        $this->API_CEBID = config('app.api_cebid');
        // $this->API_MTQG = 'https://api.ceid.gov.vn';
        $this->middleware('check.mtqg');
    }

    const type = [
        'C_VungMien' => 'Vùng miền',
        'C_LuuVucSong' => 'Lưu vực sông',
        'T_MoiTruongCoSo' => 'Cơ sở sản xuất',
        'C_LoaiNganhNgheKinhTe' => 'Loại hình ngành nghề kinh tế',
        'C_LoaiHinhGayONhiem' => 'Loại hình gây ô nhiễm',
        'C_LoaiHinhCoSo' => 'Loại hình cơ sở',
        'C_TinhThanh' => 'Tỉnh Thành',
        'T_MoiTruongCoSo' => 'Môi trường cơ sở / Cơ sở sản xuất',
        'C_QuanHuyen' => 'Quận huyện',
        'C_HanhViVPHC' => 'Hành vi vi phạm',
        'C_LoaiVanBanDTM' => 'Loại văn bản ĐTM',
        'C_LoaiGiayPhepMoiTruong' => 'Loại giấy phép môi trường',
        'T_ChuDauTu' => 'Chủ đầu tư',
        'T_DoanThanhTraKiemTra' => 'Đoàn thanh tra kiểm tra',
        'C_PhuongXa' => 'Phường Xã',
        'T_DoanThanhTraKiemTra' => 'Đoàn thanh tra kiểm tra',
        'T_DiemTramQTMT' => 'Điểm trạm QTMT',
        'T_KetQuaQTMT' => 'Kết quả QTMT'
    ];
    private function getToken()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $this->API_CEBID . '/CSDL/quantridulieu/token',
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
    private function getData($page = 0, $type = '', $from = null)
    {
        $token = $this->getToken();
        $curl = curl_init();
        $url = "$this->API_CEBID/CSDL/quantridulieu/$type?resumptionToken=$page";
        if ($from) {
            $from = $from->toIso8601ZuluString();
            $url = "$url&from=$from";
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "bool": {
                    "must": [
                        {
                            "match": {
                                "site": "csdl_mt"
                            }
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                'Content-Type: application/json',
                'key: kdQw8czmVzwL9BvzSsa2m2TeeS0bQb7k'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = (array)json_decode($response);

        return  $response['data'] ?? [];
    }
    private function getTotalPage($type = '', $from = null)
    {
        set_time_limit(0);
        $token = $this->getToken();
        $page = 0;
        $url = "$this->API_CEBID/CSDL/quantridulieu/$type?resumptionToken=$page";
        // dd($url);

        if ($from) {
            $from = $from->toIso8601ZuluString();
            $url = "$url&from=$from";
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "bool": {
                    "must": [
                        {
                            "match": {
                                "site": "csdl_mt"
                            }
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                'Content-Type: application/json',
                'key: kdQw8czmVzwL9BvzSsa2m2TeeS0bQb7k'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $total = $response['total'];
        $perPage = count($response['data']);
        if ($perPage == 0) {
            return 0;
        }
        $page = (int)($total / $perPage) + 1;
        return $page;
    }
    private function getAllData($type, $from = null)
    {
        $page = $this->getTotalPage($type, $from);
        $data = [];
        for ($i = 0; $i <= $page; $i++) {
            $res = $this->getData($i, $type, $from);
            $res = (array)$res;
            if ($res && count($res) > 0) {
                $data = array_merge($data, $res);
            };
        }
        return $data;
    }


    public function importDuLieuCoSoQuocGia()
    {
        set_time_limit(0);
        $data = $this->getAllData('T_MoiTruongCoSo___1');
        CoSoSanXuatQuocGia::query()->delete();
        foreach ($data as $item) {
            $item = (array) $item;
            CoSoSanXuatQuocGia::create([
                'ten' => $item['TenGoi'] ? $item['TenGoi'] : '',
                'cap_phep_phe_lieu' => isset($item['CapPhepPheLieu']) ? json_encode($item['CapPhepPheLieu']) : null,
                'day_truyen_phan_khu' => isset($item['DayTruyenPhanKhu']) ? json_encode($item['DayTruyenPhanKhu']) : null,
                'chu_dau_tu' => isset($item['ChuDauTu']) ? json_encode($item['ChuDauTu']) : null,
                'dia_chi' => isset($item['DiaChi']) ? json_encode($item['DiaChi']) : null,
                'loai_hinh_co_so' => isset($item['LoaiHinhCoSo']) ? json_encode($item['LoaiHinhCoSo']) : null,
                'loai_hinh_o_nhiem' => isset($item['LoaiHinhGayONhiem']) ? json_encode($item['LoaiHinhGayONhiem']) : null,
                'loai_nganh_nghe_kinh_te' => isset($item['LoaiNganhNgheKinhTe']) ? json_encode($item['LoaiNganhNgheKinhTe']) : null,
                'ma_dinh_danh' => isset($item['MaDinhDanh']) ? $item['MaDinhDanh'] : '',
                'van_ban_gpmt' => isset($item['VanBanGPMT']) ? json_encode($item['VanBanGPMT']) : null,
                'van_ban_dtm' => isset($item['VanBanDRM']) ? json_encode($item['VanBanDTM']) : null,
                'tinh_trang_hoat_dong' => isset($item['TinhTrangHoatDong']) ? json_encode($item['TinhTrangHoatDong']) : null,
                'id_csdlqg' => isset($item['ID']) ? $item['ID'] : '',
                'ton_tai_tap_trung' => isset($item['TonTaiTapTrung']) ? json_encode($item['TonTaiTapTrung']) : null,
            ]);
        }
        DB::commit();
        return response(['message' => 'Done'], 200);
    }
    private function dongBoThoiGian($type)
    {
        $data = ThoiGianDongBo::where('danh_muc_dong_bo', $type)->first();
        if (empty($data)) {
            ThoiGianDongBo::create([
                'danh_muc_dong_bo' => $type,
            ]);
        } else {
            ThoiGianDongBo::where('danh_muc_dong_bo', $type)->update([
                'danh_muc_dong_bo' => $type,
            ]);
        }
    }
    public function dongBoLoaiNganhNgheKinhTe()
    {
        $data = $this->getAllData('C_LoaiNganhNgheKinhTe');
        DB::beginTransaction();
        try {
            DB::table('loai_hinh_co_sos')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
            foreach ($data as $item) {
                $loaiHinh = LoaiHinhCoSo::where('ma', $item->MaMuc)->first();
                if ($loaiHinh) {
                    $loaiHinh->update([
                        'ten' => $item->TenMuc,
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                } else {
                    LoaiHinhCoSo::create([
                        'ten' => $item->TenMuc,
                        'ma' => $item->MaMuc,
                        'van_ban_phap_luat' =>  '27/2018/QĐ-TTg',
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                }
            }
            $this->dongBoThoiGian('loai_hinh_co_so');
            DB::commit();
            $this->logDongBo('Đồng bộ loại ngành nghề kinh tế', 'C_LoaiNganhNgheKinhTe', $data, $from ?? null);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function dongBoLuuVucSong()
    {
        $data = $this->getAllData('C_LuuVucSong');
        DB::beginTransaction();
        DB::table('luu_vuc_songs')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        $luuVucs = LuuVucSong::select('id', 'ten', 'ma')->get();
        $tinhThanhsCategory = TinhThanh::select('id', 'ma', 'ten')->get();
        foreach ($data as $item) {
            $item = (array) $item;
            $luuVucSongItem = $luuVucs->where('ma', $item['MaMuc'])->first();
            $value = [
                'ten' => $item['TenMuc'],
                'ma' => $item['MaMuc'],
                'trang_thai_dong_bo' => 'da_dong_bo'
            ];
            $tinhThanhNews = isset($item['TinhThanh']) ? $item['TinhThanh'] : [];
            $luuVuc = $luuVucSongItem;
            if (!empty($luuVucSongItem)) {
                TinhThanhLuuVucSong::where('luu_vuc_song_id', $luuVuc->id)->delete();
                $luuVucSongItem->update($value);
            } else {
                $luuVuc =  LuuVucSong::create($value);
            }

            foreach ($tinhThanhNews as $tinhThanhNew) {
                if (isset($tinhThanhNew->MaMuc)) {
                    $tinhThanh = $tinhThanhsCategory->where('ma', $tinhThanhNew->MaMuc)->first();
                    if ($tinhThanh) {
                        TinhThanhLuuVucSong::create([
                            'luu_vuc_song_id' => $luuVuc->id,
                            'tinh_thanh_id' => $tinhThanh->id
                        ]);
                    }
                }
            }
        }
        $this->dongBoThoiGian('luu_vuc_song');
        DB::commit();
        $this->logDongBo('Đồng bộ lưu vực sông', 'C_LuuVucSong', $data, $from ?? null);
        return response(['message' => 'Done'], 200);
    }
    public function dongBoMien()
    {
        $data = $this->getAllData('C_VungMien');
        DB::beginTransaction();
        DB::table('miens')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        try {
            foreach ($data as $item) {
                $item = (array) $item;
                $mien = Mien::where('ma_dinh_danh', $item['MaMuc'])->orWhere('ten', $item['TenMuc'])->first();
                if ($mien) {
                    $mien->update([
                        'ma_dinh_danh' => $item['MaMuc'],
                        'ten' => $item['TenMuc'],
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                } else {
                    Mien::create([
                        'ma_dinh_danh' => $item['MaMuc'],
                        'ma' => $item['MaMuc'],
                        'ten' => $item['TenMuc'],
                        'trang_thai_dong_bo' => 'da_dong_bo'

                    ]);
                }
            }
            $this->dongBoThoiGian('vung_mien');
            DB::commit();
            $this->logDongBo('Đồng bộ vùng miền', 'C_VungMien', $data);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    public function dongBoLoaiThongSo()
    {
        $data = $this->getAllData('C_LoaiHinhQTMT');
        DB::beginTransaction();
        DB::table('loai_thong_sos')->update([
            'trang_thai_dong_bo' => 'chua_dong_bo'
        ]);
        try {
            foreach ($data as $item) {
                $item = (array)$item;
                $check = LoaiThongSo::where('ma', $item['MaMuc'])->first();
                if ($check) {
                    $check->update([
                        'ten' => isset($item['TenMuc']) ? $item['TenMuc'] : null,
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                } else {
                    LoaiThongSo::create([
                        'ten' => isset($item['TenMuc']) ? $item['TenMuc'] : null,
                        'ma' => isset($item['MaMuc']) ? $item['MaMuc'] : null,
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                }
            }
            $this->dongBoThoiGian('loai_thong_so');
            DB::commit();
            $this->logDongBo('Đồng bộ loại thông số', 'C_LoaiHinhQTMT', $data);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    public function dongBoThongSo()
    {
        $data = $this->getAllData('C_ThongSoMoiTruong');
        $loaiHinh = [
            'nuoc_thai' => 'Nước thải',
            'chat_thai_ran' => 'Chất thải rắn',
            'khong_khi_xung_quanh' => 'Không khí xung quanh',
            'nuoc_mat' => 'Nước mặt',
            'tram_tich' => 'Trầm tích',
            'nuoc_duoi_dat' => 'Nước dưới đất',
            'dat' => 'Đất',
            'chua_phan_loai' => 'Chưa phân loại'
        ];
        $this->dongBoLoaiThongSo();
        DB::beginTransaction();
        $daDongBo = ThoiGianDongBo::where('danh_muc_dong_bo', 'thong_so')->first();
        DB::table('danh_muc_thong_so_vuot_chuans')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        foreach ($data as $index => $item) {
            $item = (array)$item;
            $ten = $item['TenMuc'];
            $kyHieu = isset($item['KyHieuHoaHoc']) && $item['KyHieuHoaHoc'] ? $item['KyHieuHoaHoc'] : null;
            $maLoai = null;
            $maDinhDanh = $item['MaMuc'];
            $tenLoai = 'Chưa phân loại';
            if (isset($item['LoaiHinhQTMT']) && $item['LoaiHinhQTMT']) {
                $item = (array) $item['LoaiHinhQTMT'];
                $tenLoai = isset($item['TenMuc']) && $item['TenMuc'] ? $item['TenMuc'] :  'Chưa phân loại';
                $maLoai = isset($item['MaMuc']) && $item['MaMuc'] ? $item['MaMuc'] : null;
            }

            if (!$daDongBo && $kyHieu) {
                $check = DanhMucThongSoVuotChuan::where('ten', $ten)->orWhere('ky_hieu_hoa_hoc', $kyHieu)->first();
                if ($check) {
                    $check->update([
                        'ten' => $ten,
                        'ky_hieu_hoa_hoc' => $kyHieu,
                        'ma_loai' => $maLoai,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'ma' => $maDinhDanh
                    ]);
                } else {
                    DanhMucThongSoVuotChuan::create([
                        'ten' => $ten,
                        'ky_hieu_hoa_hoc' => $kyHieu,
                        'ma_loai' => $maLoai,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'ma' => $maDinhDanh
                    ]);
                }
            } else if (!$daDongBo && !$kyHieu) {
                $checkTen = DanhMucThongSoVuotChuan::where('ten', $ten)->first();
                if ($checkTen) {
                    $checkTen->update([
                        'ten' => $ten,
                        'ky_hieu_hoa_hoc' => $kyHieu,
                        'ma_loai' => $maLoai,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'ma' => $maDinhDanh
                    ]);
                } else {
                    DanhMucThongSoVuotChuan::create([
                        'ten' => $ten,
                        'ky_hieu_hoa_hoc' => $kyHieu,
                        'ma_loai' => $maLoai,
                        'ma' => $maDinhDanh,
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                }
            } else if ($daDongBo) {
                $checkMa = DanhMucThongSoVuotChuan::where('ma', $maDinhDanh)->first();
                if ($checkMa) {
                    $checkMa->update([
                        'ten' => $ten,
                        'ky_hieu_hoa_hoc' => $kyHieu,
                        'ma_loai' => $maLoai,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                    ]);
                } else {
                    DanhMucThongSoVuotChuan::create([
                        'ten' => $ten,
                        'ky_hieu_hoa_hoc' => $kyHieu,
                        'ma_loai' => $maLoai,
                        'ma' => $maDinhDanh,
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                }
            }
            // $type = array_search($tenLoai, $loaiHinh);
            // if ($kyHieu && $type) {
            //     $check = DanhMucThongSoVuotChuan::where('ten', $ten)->orWhere('ky_hieu_hoa_hoc', $kyHieu)->first();
            //     if ($check) {
            //         $check->update([
            //             'ten' => $ten,
            //             'ky_hieu_hoa_hoc' => $kyHieu,
            //             'type' => $type
            //         ]);
            //     } else {
            //         DanhMucThongSoVuotChuan::create([
            //             'ten' => $ten,
            //             'ky_hieu_hoa_hoc' => $kyHieu,
            //             'type' => $type
            //         ]);
            //     }
            // } else if ($type) {
            //     $checkTen = DanhMucThongSoVuotChuan::where('ten', $ten)->first();
            //     if ($checkTen) {
            //         $checkTen->update([
            //             'ky_hieu_hoa_hoc' => $kyHieu,
            //             'type' => $type
            //         ]);
            //     } else {
            //         DanhMucThongSoVuotChuan::create([
            //             'ten' => $ten,
            //             'ky_hieu_hoa_hoc' => $kyHieu,
            //             'type' => $type
            //         ]);
            //     }
            // }
        };
        $this->dongBoThoiGian('thong_so');
        DB::commit();
        $this->logDongBo('Đồng bộ Thông số môi trường', 'C_ThongSoMoiTruong', $data, $from ?? null);
        return response(['message' => 'Done'], 200);
    }

    public function dongBoLoaiHinhNguyCoOiNhiem()
    {
        $data = $this->getAllData('C_LoaiHinhGayONhiem');
        DB::beginTransaction();
        DB::table('loai_hinh_oi_nhiems')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        foreach ($data as $item) {
            $item = (array) $item;
            $check = LoaiHinhOiNhiem::query()->where('ma', $item['MaMuc'])->first();
            if (empty($check)) {
                LoaiHinhOiNhiem::create([
                    'ma' => $item['MaMuc'],
                    'ten' => $item['TenMuc'],
                    'trang_thai_dong_bo' => 'da_dong_bo'
                ]);
            } else {
                $check->update([
                    'ten' => $item['TenMuc'],
                    'trang_thai_dong_bo' => 'da_dong_bo'
                ]);
            }
        }
        $this->dongBoThoiGian('loai_hinh_nguy_co_oi_nhiem');
        DB::commit();
        $this->logDongBo('Đồng bộ loại hình nguy cơ gây ô nhiễm', 'C_LoaiHinhGayONhiem', $data);
        return response(['message' => 'Done'], 200);
    }

    public function dongBoLoaiKhuCongNghiep()
    {
        $data = $this->getAllData('C_LoaiHinhCoSo');
        DB::beginTransaction();
        DB::table('loai_khu_cong_nghieps')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        foreach ($data as $item) {
            $item = (array) $item;
            $check = LoaiKhuCongNghiep::where('ten', $item['TenMuc'])->orWhere('ma_muc', $item['MaMuc'])->first();
            if ($check) {
                LoaiKhuCongNghiep::where('ten', $item['TenMuc'])->orWhere('ma_muc', $item['MaMuc'])->update([
                    'ten' => $item['TenMuc'],
                    'ma_muc' => $item['MaMuc'],
                    'trang_thai_dong_bo' => 'da_dong_bo'
                ]);
            } else {
                LoaiKhuCongNghiep::create([
                    'ma' => $item['MaMuc'],
                    'ten' => $item['TenMuc'],
                    'ma_muc' => $item['MaMuc'],

                ]);
            }
        }
        $this->dongBoThoiGian('loai_khu_cong_nghiep');
        DB::commit();
        $this->logDongBo('Đồng bộ loại khu công nghiệp', 'C_LoaiHinhCoSo', $data);
        return response(['message' => 'Done'], 200);
    }

    public function dongBoTinhThanh()
    {
        $data = $this->getAllData('C_TinhThanh');
        DB::beginTransaction();
        DB::table('tinh_thanhs')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        foreach ($data as $item) {
            $item = (array)$item;
            $check = TinhThanh::where('ma', $item['MaMuc'])->first();
            if ($check) {
                TinhThanh::where('ma', $item['MaMuc'])->update([
                    'ten' => $item['TenMuc'],
                    'trang_thai_dong_bo' => 'da_dong_bo'
                ]);
            } else {
                TinhThanh::create([
                    'ma' => $item['MaMuc'],
                    'ten' => $item['TenMuc'],
                    'trang_thai' => true,
                    'trang_thai_dong_bo' => 'da_dong_bo'
                ]);
            }
        }
        $this->dongBoThoiGian('tinh_thanh');
        DB::commit();
        $this->logDongBo('Đồng bộ tỉnh thành', 'C_TinhThanh', $data);
        return response(['message' => 'Done'], 200);
    }

    public function dongBoChuDauTu()
    {
        set_time_limit(0);
        $thoiGianDongBo = ThoiGianDongBo::where('danh_muc_dong_bo', 'chu_dau_tu')->first();
        $from = $thoiGianDongBo ? $thoiGianDongBo->updated_at : null;
        $data = $this->getAllData('T_ChuDauTu', $from);
        $data = array_filter($data, function ($value) {
            $item = (array) $value;
            return $item['TrangThaiDuLieu']->MaMuc === '02';
        });
        DB::beginTransaction();
        DB::table('chu_dau_tus')->update([
            'trang_thai_dong_bo' => 'chua_dong_bo',
        ]);
        foreach ($data as $key => $item) {
            $item = (array)$item;
            if (isset($item['TenGoi']) && $item['TenGoi'] && isset($item['MaDinhDanh']) && $item['MaDinhDanh']) {
                $cdt = ChuDauTu::where('ma_dinh_danh', $item['MaDinhDanh'])->orWhere('ten', $item['TenGoi'])->first();
                $tinhThanh = isset($item['DiaChi']) && isset($item['DiaChi']->TinhThanh) && isset($item['DiaChi']->TinhThanh->MaMuc) && $item['DiaChi']->TinhThanh->MaMuc ? TinhThanh::where('ma', $item['DiaChi']->TinhThanh->MaMuc)->first() : null;
                $quanHuyen = isset($item['DiaChi']) && isset($item['DiaChi']->QuanHuyen) && isset($item['DiaChi']->QuanHuyen->MaMuc) && $item['DiaChi']->QuanHuyen->MaMuc ? QuanHuyen::where('ma_dinh_danh', $item['DiaChi']->QuanHuyen->MaMuc)->first() : null;
                if ($cdt) {
                    $cdt->update([
                        'dia_chi' => isset($item['DiaChi']) && isset($item['DiaChi']->DiaChiChiTiet) ? $item['DiaChi']->DiaChiChiTiet : null,
                        'fax' => isset($item['DanhBaLienLac']) && isset($item['DanhBaLienLac']->SoFax) ? $item['DanhBaLienLac']->SoFax : null,
                        'email' => isset($item['DanhBaLienLac']) && isset($item['DanhBaLienLac']->ThuDienTu) ? $item['DanhBaLienLac']->ThuDienTu : null,
                        'so_dien_thoai' => isset($item['DanhBaLienLac']) && isset($item['DanhBaLienLac']->SoDienThoai) ? $item['DanhBaLienLac']->SoDienThoai : null,
                        'tinh_thanh_id' => $tinhThanh ? $tinhThanh->id : null,
                        'quan_huyen_id' => $quanHuyen ? $quanHuyen->id : null,
                        'xa_phuong' => isset($item['DiaChi']) && isset($item['DiaChi']->PhuongXa) && isset($item['DiaChi']->PhuongXa->TenMuc) ? $item['DiaChi']->PhuongXa->TenMuc : null,
                        'ma_so_qlctnh' => isset($item['MaSoQLCTNH']) ? $item['MaSoQLCTNH'] : null,

                        'so_giay_chung_nhan_dang_ky_kinh_doanh' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->SoGiay) ? $item['GiayToChungNhan']->SoGiay : null,
                        'co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->NoiCap) ? $item['GiayToChungNhan']->NoiCap : null,
                        'ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->NgayCap) && $item['GiayToChungNhan']->NgayCap ? $this->parseDate($item['GiayToChungNhan']->NgayCap) : null,


                        'so_giay_chung_nhan_dau_tu' =>  null,
                        'noi_cap_giay_chung_nhan_dau_tu' => null,
                        'ngay_cap_giay_chung_nhan_dau_tu' => null,

                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'ma_dinh_danh' => $item['MaDinhDanh'],
                        'ten' => $item['TenGoi']
                    ]);
                } else {
                    $cdt = ChuDauTu::create([
                        'dia_chi' => isset($item['DiaChi']) && isset($item['DiaChi']->DiaChiChiTiet) ? $item['DiaChi']->DiaChiChiTiet : null,
                        'fax' => isset($item['DanhBaLienLac']) && isset($item['DanhBaLienLac']->SoFax) ? $item['DanhBaLienLac']->SoFax : null,
                        'email' => isset($item['DanhBaLienLac']) && isset($item['DanhBaLienLac']->ThuDienTu) ? $item['DanhBaLienLac']->ThuDienTu : null,
                        'so_dien_thoai' => isset($item['DanhBaLienLac']) && isset($item['DanhBaLienLac']->SoDienThoai) ? $item['DanhBaLienLac']->SoDienThoai : null,
                        'tinh_thanh_id' => $tinhThanh ? $tinhThanh->id : null,
                        'quan_huyen_id' => $quanHuyen ? $quanHuyen->id : null,
                        'xa_phuong' => isset($item['DiaChi']) && isset($item['DiaChi']->PhuongXa) && isset($item['DiaChi']->PhuongXa->TenMuc) ? $item['DiaChi']->PhuongXa->TenMuc : null,
                        'ma_so_qlctnh' => isset($item['MaSoQLCTNH']) ? $item['MaSoQLCTNH'] : null,
                        'so_giay_chung_nhan_dau_tu' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->SoGiay) ? $item['GiayToChungNhan']->SoGiay : null,
                        'noi_cap_giay_chung_nhan_dau_tu' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->NoiCap) ? $item['GiayToChungNhan']->NoiCap : null,
                        'ngay_cap_giay_chung_nhan_dau_tu' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->NgayCap) && $item['GiayToChungNhan']->NgayCap ? $this->parseDate($item['GiayToChungNhan']->NgayCap) : null,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'ma_dinh_danh' => $item['MaDinhDanh'],
                        'ten' => $item['TenGoi'],
                        'so_giay_chung_nhan_dang_ky_kinh_doanh' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->SoGiay) ? $item['GiayToChungNhan']->SoGiay : null,
                        'co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->NoiCap) ? $item['GiayToChungNhan']->NoiCap : null,
                        'ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh' => isset($item['GiayToChungNhan']) && isset($item['GiayToChungNhan']->NgayCap) && $item['GiayToChungNhan']->NgayCap ? $this->parseDate($item['GiayToChungNhan']->NgayCap) : null,
                    ]);
                }

                $organization = Organization::where('ten', 'ilike', $item['TenGoi'])->first();
                if ($organization) {
                    Organization::where('ten', 'ilike', $item['TenGoi'])->update(['chu_dau_tu_id' => $cdt->id]);
                } else {
                    Organization::create([
                        'ten' => $item['TenGoi'],
                        'dia_chi_lien_he' => isset($item['DiaChi']) && isset($item['DiaChi']->DiaChiChiTiet) ? $item['DiaChi']->DiaChiChiTiet : null,
                        'created_by' => 2,
                        'updated_by' => 2,
                        'chu_dau_tu_id' => $cdt->id
                    ]);
                }
            }
        }


        $this->dongBoThoiGian('chu_dau_tu');
        DB::commit();
        $this->logDongBo('Đồng bộ chủ đầu tư', 'T_ChuDauTu', $data);
        return response(['message' => 'Done'], 200);
    }
    public function parseDate($date)
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $date);
        } catch (\Exception $e) {
            return null;
        }
    }

    // public function dongBoCoSo()
    // {
    //     set_time_limit(0);
    //     $thoiGianDongBo = ThoiGianDongBo::where('danh_muc_dong_bo', 'co_so_san_xuat')->first();
    //     $from = $thoiGianDongBo ? $thoiGianDongBo->updated_at : null;
    //     $data = $this->getAllData('T_MoiTruongCoSo', $from);
    //     $data = array_filter($data, function ($value) {
    //         $item = (array) $value;
    //         return $item['TrangThaiDuLieu']->MaMuc === '02';
    //     });
    //     $daDongBoLanDau = ThoiGianDongBo::where('danh_muc_dong_bo', 'co_so_san_xuat')->first();

    //     try {
    //         DB::beginTransaction();
    //         DB::table('co_so_san_xuats')->where('trang_thai_dong_bo', 'chua_dong_bo')->update(['ma_dinh_danh' => null]);
    //         DB::table('co_so_san_xuats')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
    //         foreach ($data as $item) {
    //             $item = (array) $item;
    //             $diaChiLienHe = null;
    //             $tinhThanh = [];
    //             $xaPhuong = [];
    //             $quanHuyen = [];
    //             $loaiHinhONhiemIDs = [];
    //             $loaiNganhNgheIDs = [];
    //             $loaiHinhCoSo = null;
    //             $hoaChatSuDung = null;
    //             $nhienLieuSuDung = null;
    //             $nguyenLieuSuDung = null;
    //             $chucDauTuID = null;
    //             $tinhThanhID = null;
    //             $quanHuyenID = null;
    //             $tenXaPhuong = '';
    //             $kinhDo = isset($item['ViTriDiaLy']) && isset($item['ViTriDiaLy']->KinhDo) && $item['ViTriDiaLy']->KinhDo ? $item['ViTriDiaLy']->KinhDo : 0;
    //             $viDo =  isset($item['ViTriDiaLy']) && isset($item['ViTriDiaLy']->ViDo) && $item['ViTriDiaLy']->ViDo ? $item['ViTriDiaLy']->ViDo : 0;
    //             if (isset($item['ChuDauTu']) && $item['ChuDauTu'] && isset($item['ChuDauTu']->MaDinhDanh) && $item['ChuDauTu']->MaDinhDanh) {
    //                 $cdt = ChuDauTu::where('ma_dinh_danh',  $item['ChuDauTu']->MaDinhDanh)->where('trang_thai_dong_bo', 'da_dong_bo')->first();
    //                 $chucDauTuID = $cdt ?  $cdt->id : null;
    //             }
    //             if (isset($item['DiaChi']) && $item['DiaChi']) {

    //                 $diaChiLienHe = isset($item['DiaChi']->DiaChiChiTiet) && $item['DiaChi']->DiaChiChiTiet ?  $item['DiaChi']->DiaChiChiTiet : null;
    //                 if (isset($item['DiaChi']->PhuongXa) && $item['DiaChi']->PhuongXa && gettype($item['DiaChi']->PhuongXa) == 'array' && count($item['DiaChi']->PhuongXa) > 0) {
    //                     $tenXaPhuong = $item['DiaChi']->PhuongXa[0]->TenMuc ?? null;
    //                     foreach ($item['DiaChi']->PhuongXa as $xa) {
    //                         if (isset($xa->MaMuc)) {
    //                             $xaPhuong[] = $xa->MaMuc;
    //                         };
    //                     }
    //                 }
    //                 if (isset($item['DiaChi']->QuanHuyen) && $item['DiaChi']->QuanHuyen  && gettype($item['DiaChi']->QuanHuyen) == 'array' && count($item['DiaChi']->QuanHuyen) > 0) {
    //                     $quan = isset($item['DiaChi']->QuanHuyen[0]->MaMuc) ? QuanHuyen::where('ma_dinh_danh', $item['DiaChi']->QuanHuyen[0]->MaMuc)->first() : null;
    //                     $quanHuyenID = $quan ? $quan->id : null;
    //                     foreach ($item['DiaChi']->QuanHuyen as $quan) {
    //                         if (isset($quan->MaMuc)) {
    //                             $quanHuyen[] = $quan->MaMuc;
    //                         }
    //                     }
    //                 }
    //                 if (isset($item['DiaChi']->TinhThanh) && $item['DiaChi']->TinhThanh && gettype($item['DiaChi']->TinhThanh) == 'array' && count($item['DiaChi']->TinhThanh) > 0) {
    //                     $tinh = isset($item['DiaChi']->TinhThanh[0]->MaMuc) ? TinhThanh::where('ma', $item['DiaChi']->TinhThanh[0]->MaMuc)->first() : null;
    //                     $tinhThanhID = $tinh ? $tinh->id : null;
    //                     foreach ($item['DiaChi']->TinhThanh as $tinh) {
    //                         if (isset($tinh->MaMuc)) {
    //                             $tinhThanh[] = $tinh->MaMuc;
    //                         }
    //                     }
    //                 }
    //             }
    //             if (isset($item['LoaiHinhGayONhiem']) && $item['LoaiHinhGayONhiem']) {
    //                 foreach ($item['LoaiHinhGayONhiem'] as $it) {
    //                     $loaiONhiem = null;
    //                     if (isset($it->MaMuc) && $it->MaMuc) {
    //                         $loaiONhiem =  LoaiHinhOiNhiem::select('ma', 'id', 'ten')->where('ma', $it->MaMuc)->first();
    //                     } else if (isset($it->TenMuc) || !$it->TenMuc) {
    //                         $loaiONhiem =  LoaiHinhOiNhiem::select('ma', 'id', 'ten')->where('ten', 'ilike', $it->TenMuc)->first();
    //                     }
    //                     if ($loaiONhiem) {
    //                         $loaiHinhONhiemIDs[] = $loaiONhiem->id;
    //                     }
    //                 }
    //             }
    //             if (isset($item['LoaiNganhNgheKinhTe']) && $item['LoaiNganhNgheKinhTe']) {
    //                 foreach ($item['LoaiNganhNgheKinhTe'] as $it) {
    //                     $loaiNganhNghe = isset($it->TenMuc) && $it->TenMuc ?
    //                         LoaiHinhCoSo::select('id', 'ten')->where('van_ban_phap_luat', '27/2018/QĐ-TTg')->where('ten', 'ilike', $it->TenMuc)->first() : null;
    //                     if ($loaiNganhNghe) {
    //                         $loaiNganhNgheIDs[] = $loaiNganhNghe->id;
    //                     }
    //                 }
    //             }
    //             if (isset($item['HoaChatSuDung']) && $item['HoaChatSuDung']) {
    //                 $hoaChatSuDung = $item['HoaChatSuDung'];
    //             }
    //             if (isset($item['NguyenLieuSuDung']) && $item['NguyenLieuSuDung']) {
    //                 $nguyenLieuSuDung = $item['NguyenLieuSuDung'];
    //             }
    //             if (isset($item['NhienLieuSuDung']) && $item['NhienLieuSuDung']) {
    //                 $nhienLieuSuDung = $item['NhienLieuSuDung'];
    //             }
    //             if (isset($item['LoaiHinhCoSo']) && $item['LoaiHinhCoSo']) {
    //                 if (isset($item['LoaiHinhCoSo']->MaMuc)) {
    //                     $loaiHinhCoSo =  LoaiKhuCongNghiep::where('ma_muc', $item['LoaiHinhCoSo']->MaMuc)->first();
    //                 }
    //             }
    //             $check = null;
    //             if (!$daDongBoLanDau) {
    //                 if (isset($item['metadata']) && isset($item['metadata']->NguonThamChieu) && isset($item['metadata']->NguonThamChieu->MaThamChieu)) {
    //                     $id = $item['metadata']->NguonThamChieu->MaThamChieu;
    //                     $check = CoSoSanXuat::where('id', $id)->first();
    //                 }
    //             } else if (isset($item['MaDinhDanh']) && $item['MaDinhDanh']) {
    //                 $check = CoSoSanXuat::where('ma_dinh_danh', $item['MaDinhDanh'])->first();
    //             }

    //             if ($check && isset($item['MaDinhDanh'])) {
    //                 $id = $check->id;
    //                 if ($chucDauTuID) {
    //                     $organization = Organization::where('chu_dau_tu_id', $chucDauTuID)->orderBy('updated_at', 'ASC')->first();
    //                     if ($organization) {
    //                         $check->update([
    //                             'organization_id' => $organization->id
    //                         ]);
    //                     }
    //                 }
    //                 Organization::where('id', $check->organization_id)->update(['chu_dau_tu_id' => $chucDauTuID]);
    //                 $check->update([
    //                     'ten' => isset($item['TenGoi']) && $item['TenGoi'] ? $item['TenGoi'] : 'Không tên',
    //                     'dia_chi_lien_he' => $diaChiLienHe,
    //                     'tinh_thanh_id' => $tinhThanhID,
    //                     'quan_huyen_id' => $quanHuyenID,
    //                     'xa_phuong' => $tenXaPhuong,
    //                     'trang_thai_dong_bo' => 'da_dong_bo',
    //                     'nhien_lieu_su_dung' => $nhienLieuSuDung,
    //                     'hoa_chat_su_dung' => $hoaChatSuDung,
    //                     'nguyen_lieu_chinh_su_dung' => $nguyenLieuSuDung,
    //                     'ma_dinh_danh' => $item['MaDinhDanh'],
    //                     'loai_khu_cong_nghiep' => $loaiHinhCoSo ? $loaiHinhCoSo->ma : null,
    //                     'luong_nuoc_su_dung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? (int)$item['LuongNuocSuDung'] : null,
    //                     'dien_tich' => isset($item['TongDienTich']) && $item['TongDienTich'] ? (float)$item['TongDienTich'] : null,
    //                     'nguon_nuoc_su_dung' => isset($item['NguonNuocSuDung']) && $item['NguonNuocSuDung'] ? $item['NguonNuocSuDung'] : null,
    //                     'kinh_do' => $kinhDo,
    //                     'vi_do' => $viDo,
    //                     'luong_nuoc_su_sung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? $item['LuongNuocSuDung'] : null,
    //                     'nhien_lieu_chinh_su_dung' => isset($item['NguyenLieuSuDung']) && $item['NguyenLieuSuDung'] ? $item['NguyenLieuSuDung'] : null,
    //                     'hoa_chat_su_dung' =>  isset($item['HoaChatSuDung']) && $item['HoaChatSuDung'] ? $item['HoaChatSuDung'] : null,
    //                     'nguon_nuoc_su_dung' =>  isset($item['NguonNuocSuDung']) && $item['NguonNuocSuDung'] ? $item['NguonNuocSuDung'] : null,
    //                     'luong_nuoc_su_dung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? $item['LuongNuocSuDung'] : null
    //                 ]);
    //                 CoSoSanXuatLoaiHinhONhiem::where('co_so_san_xuat_id', $id)->delete();
    //                 foreach ($loaiHinhONhiemIDs as $it) {
    //                     CoSoSanXuatLoaiHinhONhiem::create([
    //                         'co_so_san_xuat_id' => $id,
    //                         'loai_hinh_o_nhiem_id' => $it
    //                     ]);
    //                 }
    //                 CoSoSanXuatLoaiNganhNghe::where('co_so_id', $id)->delete();
    //                 foreach ($loaiNganhNgheIDs as $it) {
    //                     CoSoSanXuatLoaiNganhNghe::create([
    //                         'co_so_id' => $id,
    //                         'loai_nganh_nghe_id' => $it
    //                     ]);
    //                 }
    //                 DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $id)->delete();
    //                 foreach ($xaPhuong as $codeXa) {
    //                     $xa = PhuongXa::where('ma', $codeXa)->first();
    //                     $quan = $xa ? QuanHuyen::where('id', $xa->quan_huyen_id)->first() : null;
    //                     $tinh = $quan ? TinhThanh::where('id', $quan->tinh_thanh_id)->first() : null;
    //                     if ($xa && $quan && $tinh) {
    //                         DiaDiemCoSoSanXuat::create([
    //                             'co_so_san_xuat_id' => $id,
    //                             'phuong_xa_id' => $xa->id,
    //                             'quan_huyen_id' => $quan->id,
    //                             'tinh_thanh_id' => $tinh->id
    //                         ]);
    //                     }
    //                 }

    //                 foreach ($quanHuyen as $codeQuanHuyen) {
    //                     $quan =  QuanHuyen::where('ma_dinh_danh', $codeQuanHuyen)->first();
    //                     $tinh = $quan ? TinhThanh::where('id', $quan->tinh_thanh_id)->first() : null;
    //                     $check = $quan ? DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $id)->where('quan_huyen_id', $quan->id)->first() : true;
    //                     if ($quan && $tinh && !$check) {
    //                         DiaDiemCoSoSanXuat::create([
    //                             'co_so_san_xuat_id' => $id,
    //                             'quan_huyen_id' => $quan->id,
    //                             'tinh_thanh_id' => $tinh->id
    //                         ]);
    //                     }
    //                 }
    //                 foreach ($tinhThanh as $codeTinh) {
    //                     $tinh = TinhThanh::where('ma', $codeTinh)->first();
    //                     $check = $tinh ? DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $id)->where('tinh_thanh_id', $tinh->id)->first() : true;
    //                     if ($quan && $tinh && !$check) {
    //                         DiaDiemCoSoSanXuat::create([
    //                             'co_so_san_xuat_id' => $id,
    //                             'tinh_thanh_id' => $tinh->id
    //                         ]);
    //                     }
    //                 }
    //             } else if ($daDongBoLanDau && isset($item['MaDinhDanh']) && $item['MaDinhDanh']) {
    //                 $toChuc = Organization::create([
    //                     'ten' => isset($item['TenGoi']) && $item['TenGoi'] ? $item['TenGoi'] : 'Không tên',
    //                     'dia_chi_lien_he' => $diaChiLienHe,
    //                     'created_by' => 2,
    //                     'updated_by' => 2,
    //                     'chu_dau_tu_id' => $chucDauTuID
    //                 ]);
    //                 $cs = CoSoSanXuat::create([
    //                     'ten' => isset($item['TenGoi']) && $item['TenGoi'] ? $item['TenGoi'] : 'Không tên',
    //                     'dia_chi_lien_he' => $diaChiLienHe,
    //                     'tinh_thanh_id' => $tinhThanhID,
    //                     'quan_huyen_id' => $quanHuyenID,
    //                     'xa_phuong' => $tenXaPhuong,
    //                     'organization_id' => $toChuc->id,
    //                     'created_by' => 2,
    //                     'updated_by' => 2,
    //                     'trang_thai_dong_bo' => 'da_dong_bo',
    //                     'loai_du_lieu' => 'du_lieu_moi',
    //                     'nhien_lieu_su_dung' => $nhienLieuSuDung,
    //                     'hoa_chat_su_dung' => $hoaChatSuDung,
    //                     'nguyen_lieu_chinh_su_dung' => $nguyenLieuSuDung,
    //                     'ma_dinh_danh' => $item['MaDinhDanh'],
    //                     'loai_khu_cong_nghiep' => $loaiHinhCoSo ? $loaiHinhCoSo->ma : null,
    //                     'luong_nuoc_su_dung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? (int)$item['LuongNuocSuDung'] : null,
    //                     'dien_tich' => isset($item['TongDienTich']) && $item['TongDienTich'] ? (float)$item['TongDienTich'] : null,
    //                     'nguon_nuoc_su_dung' => isset($item['NguonNuocSuDung']) && $item['NguonNuocSuDung'] ? $item['NguonNuocSuDung'] : null,
    //                     'kinh_do' => $kinhDo,
    //                     'vi_do' => $viDo,
    //                     'luong_nuoc_su_sung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? $item['LuongNuocSuDung'] : null,
    //                     'nhien_lieu_chinh_su_dung' => isset($item['NguyenLieuSuDung']) && $item['NguyenLieuSuDung'] ? $item['NguyenLieuSuDung'] : null,
    //                     'hoa_chat_su_dung' =>  isset($item['HoaChatSuDung']) && $item['HoaChatSuDung'] ? $item['HoaChatSuDung'] : null,
    //                     'nguon_nuoc_su_dung' =>  isset($item['NguonNuocSuDung']) && $item['NguonNuocSuDung'] ? $item['NguonNuocSuDung'] : null,
    //                     'luong_nuoc_su_dung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? $item['LuongNuocSuDung'] : null
    //                 ]);
    //                 foreach ($loaiHinhONhiemIDs as $it) {
    //                     CoSoSanXuatLoaiHinhONhiem::create([
    //                         'co_so_san_xuat_id' => $cs->id,
    //                         'loai_hinh_o_nhiem_id' => $it
    //                     ]);
    //                 }
    //                 foreach ($loaiNganhNgheIDs as $it) {
    //                     CoSoSanXuatLoaiNganhNghe::create([
    //                         'co_so_id' => $cs->id,
    //                         'loai_nganh_nghe_id' => $it
    //                     ]);
    //                 }

    //                 foreach ($xaPhuong as $codeXa) {
    //                     $xa = PhuongXa::where('ma', $codeXa)->first();
    //                     $quan = $xa ? QuanHuyen::where('id', $xa->quan_huyen_id)->first() : null;
    //                     $tinh = $quan ? TinhThanh::where('id', $quan->tinh_thanh_id)->first() : null;
    //                     if ($xa && $quan && $tinh) {
    //                         DiaDiemCoSoSanXuat::create([
    //                             'co_so_san_xuat_id' => $cs->id,
    //                             'phuong_xa_id' => $xa->id,
    //                             'quan_huyen_id' => $quan->id,
    //                             'tinh_thanh_id' => $tinh->id
    //                         ]);
    //                     }
    //                 }
    //                 foreach ($quanHuyen as $codeQuanHuyen) {
    //                     $quan =  QuanHuyen::where('ma_dinh_danh', $codeQuanHuyen)->first();
    //                     $tinh = $quan ? TinhThanh::where('id', $quan->tinh_thanh_id)->first() : null;
    //                     $check = $quan ?  DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $cs->id)->where('quan_huyen_id', $quan->id)->first() : true;
    //                     if ($quan && $tinh && !$check) {
    //                         DiaDiemCoSoSanXuat::create([
    //                             'co_so_san_xuat_id' => $cs->id,
    //                             'quan_huyen_id' => $quan->id,
    //                             'tinh_thanh_id' => $tinh->id
    //                         ]);
    //                     }
    //                 }
    //                 foreach ($tinhThanh as $codeTinh) {
    //                     $tinh = TinhThanh::where('ma', $codeTinh)->first();
    //                     $check = $tinh ? DiaDiemCoSoSanXuat::where('co_so_san_xuat_id', $cs->id)->where('tinh_thanh_id', $tinh->id)->first() : true;
    //                     if ($quan && $tinh && !$check) {
    //                         DiaDiemCoSoSanXuat::create([
    //                             'co_so_san_xuat_id' => $cs->id,
    //                             'tinh_thanh_id' => $tinh->id
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }
    //         $this->dongBoThoiGian('co_so_san_xuat');
    //         DB::commit();
    //         $this->logDongBo('Đồng bộ môi trường cơ sở', 'T_MoiTruongCoSo', $data);
    //         return response(['message' => 'Done'], 200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         dd($e);
    //     }
    // }

    public function dongBoCoSo()
    {
        set_time_limit(0);
        $thoiGianDongBo = ThoiGianDongBo::where('danh_muc_dong_bo', 'co_so_san_xuat')->first();
        $from = $thoiGianDongBo ? $thoiGianDongBo->updated_at : null;
        $data = $this->getAllData('T_MoiTruongCoSo', $from);
        $data = array_filter($data, function ($value) {
            $item = (array) $value;
            return $item['TrangThaiDuLieu']->MaMuc === '02';
        });
        $daDongBoLanDau = ThoiGianDongBo::where('danh_muc_dong_bo', 'co_so_san_xuat')->first();

        try {
            DB::beginTransaction();
            DB::table('co_so_san_xuats')->where('trang_thai_dong_bo', 'chua_dong_bo')->update(['ma_dinh_danh' => null]);
            DB::table('co_so_san_xuats')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
            
            foreach ($data as $item) {
                $item = (array) $item;
                $diaChiLienHe = null;
                $tinhThanh = [];
                $xaPhuong = [];
                $quanHuyen = [];
                $loaiHinhONhiemIDs = [];
                $loaiNganhNgheIDs = [];
                $loaiHinhCoSo = null;
                $hoaChatSuDung = null;
                $nhienLieuSuDung = null;
                $nguyenLieuSuDung = null;
                $chucDauTuID = null;
                $tinhThanhID = null;
                $quanHuyenID = null;
                $tenXaPhuong = '';
                $kinhDo = isset($item['ViTriDiaLy']) && isset($item['ViTriDiaLy']->KinhDo) && $item['ViTriDiaLy']->KinhDo ? $item['ViTriDiaLy']->KinhDo : 0;
                $viDo =  isset($item['ViTriDiaLy']) && isset($item['ViTriDiaLy']->ViDo) && $item['ViTriDiaLy']->ViDo ? $item['ViTriDiaLy']->ViDo : 0;
                
                if (isset($item['ChuDauTu']) && $item['ChuDauTu'] && isset($item['ChuDauTu']->MaDinhDanh) && $item['ChuDauTu']->MaDinhDanh) {
                    $cdt = ChuDauTu::where('ma_dinh_danh',  $item['ChuDauTu']->MaDinhDanh)->where('trang_thai_dong_bo', 'da_dong_bo')->first();
                    $chucDauTuID = $cdt ?  $cdt->id : null;
                }

                // Xử lý địa chỉ
                if (isset($item['DiaChi']) && $item['DiaChi']) {
                    $diaChiLienHe = isset($item['DiaChi']->DiaChiChiTiet) && $item['DiaChi']->DiaChiChiTiet ?  $item['DiaChi']->DiaChiChiTiet : null;
                    
                    // Xử lý phường/xã
                    if (isset($item['DiaChi']->PhuongXa) && $item['DiaChi']->PhuongXa && gettype($item['DiaChi']->PhuongXa) == 'array' && count($item['DiaChi']->PhuongXa) > 0) {
                        $tenXaPhuong = $item['DiaChi']->PhuongXa[0]->TenMuc ?? null;
                        foreach ($item['DiaChi']->PhuongXa as $xa) {
                            if (isset($xa->MaMuc)) {
                                $xaPhuong[] = $xa->MaMuc;
                            };
                        }
                    }

                    // Xử lý quận/huyện
                    if (isset($item['DiaChi']->QuanHuyen) && $item['DiaChi']->QuanHuyen && gettype($item['DiaChi']->QuanHuyen) == 'array' && count($item['DiaChi']->QuanHuyen) > 0) {
                        $quan = isset($item['DiaChi']->QuanHuyen[0]->MaMuc) ? QuanHuyen::where('ma_dinh_danh', $item['DiaChi']->QuanHuyen[0]->MaMuc)->first() : null;
                        $quanHuyenID = $quan ? $quan->id : null;
                        foreach ($item['DiaChi']->QuanHuyen as $quan) {
                            if (isset($quan->MaMuc)) {
                                $quanHuyen[] = $quan->MaMuc;
                            }
                        }
                    }

                    // Xử lý tỉnh thành
                    if (isset($item['DiaChi']->TinhThanh) && $item['DiaChi']->TinhThanh && gettype($item['DiaChi']->TinhThanh) == 'array' && count($item['DiaChi']->TinhThanh) > 0) {
                        $tinh = isset($item['DiaChi']->TinhThanh[0]->MaMuc) ? TinhThanh::where('ma', $item['DiaChi']->TinhThanh[0]->MaMuc)->first() : null;
                        $tinhThanhID = $tinh ? $tinh->id : null;
                        foreach ($item['DiaChi']->TinhThanh as $tinh) {
                            if (isset($tinh->MaMuc)) {
                                $tinhThanh[] = $tinh->MaMuc;
                            }
                        }
                    }
                }

                // Xử lý loại hình ô nhiễm
                if (isset($item['LoaiHinhGayONhiem']) && $item['LoaiHinhGayONhiem']) {
                    foreach ($item['LoaiHinhGayONhiem'] as $it) {
                        $loaiONhiem = null;
                        if (isset($it->MaMuc) && $it->MaMuc) {
                            $loaiONhiem =  LoaiHinhOiNhiem::select('ma', 'id', 'ten')->where('ma', $it->MaMuc)->first();
                        } else if (isset($it->TenMuc) || !$it->TenMuc) {
                            $loaiONhiem =  LoaiHinhOiNhiem::select('ma', 'id', 'ten')->where('ten', 'ilike', $it->TenMuc)->first();
                        }
                        if ($loaiONhiem) {
                            $loaiHinhONhiemIDs[] = $loaiONhiem->id;
                        }
                    }
                }

                // Xử lý các trường số (luong_nuoc_su_dung và dien_tich)
                $luongNuocSuDung = isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? $item['LuongNuocSuDung'] : null;
                if ($luongNuocSuDung && !is_numeric($luongNuocSuDung)) {
                    $luongNuocSuDung = eval("return $luongNuocSuDung;");
                }
                $luongNuocSuDung = is_numeric($luongNuocSuDung) ? (float)$luongNuocSuDung : null;

                $dienTich = isset($item['TongDienTich']) && $item['TongDienTich'] ? $item['TongDienTich'] : null;
                $dienTich = is_numeric($dienTich) ? (float)$dienTich : null;

                // Cập nhật hoặc thêm mới cơ sở sản xuất
                $check = null;
                if (!$daDongBoLanDau) {
                    if (isset($item['metadata']) && isset($item['metadata']->NguonThamChieu) && isset($item['metadata']->NguonThamChieu->MaThamChieu)) {
                        $id = $item['metadata']->NguonThamChieu->MaThamChieu;
                        $check = CoSoSanXuat::where('id', $id)->first();
                    }
                } else if (isset($item['MaDinhDanh']) && $item['MaDinhDanh']) {
                    $check = CoSoSanXuat::where('ma_dinh_danh', $item['MaDinhDanh'])->first();
                }

                if ($check && isset($item['MaDinhDanh'])) {
                    $id = $check->id;
                    $check->update([
                        'ten' => isset($item['TenGoi']) && $item['TenGoi'] ? $item['TenGoi'] : 'Không tên',
                        'dia_chi_lien_he' => $diaChiLienHe,
                        'tinh_thanh_id' => $tinhThanhID,
                        'quan_huyen_id' => $quanHuyenID,
                        'xa_phuong' => $tenXaPhuong,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'nhien_lieu_su_dung' => $nhienLieuSuDung,
                        'hoa_chat_su_dung' => $hoaChatSuDung,
                        'nguyen_lieu_chinh_su_dung' => $nguyenLieuSuDung,
                        'ma_dinh_danh' => $item['MaDinhDanh'],
                        'loai_khu_cong_nghiep' => $loaiHinhCoSo ? $loaiHinhCoSo->ma : null,
                        'luong_nuoc_su_dung' => $luongNuocSuDung,
                        'dien_tich' => $dienTich,
                        'nguon_nuoc_su_dung' => isset($item['NguonNuocSuDung']) && $item['NguonNuocSuDung'] ? $item['NguonNuocSuDung'] : null,
                        'kinh_do' => $kinhDo,
                        'vi_do' => $viDo,
                        'luong_nuoc_su_sung' => isset($item['LuongNuocSuDung']) && $item['LuongNuocSuDung'] ? $item['LuongNuocSuDung'] : null,
                        'nhien_lieu_chinh_su_dung' => isset($item['NguyenLieuSuDung']) && $item['NguyenLieuSuDung'] ? $item['NguyenLieuSuDung'] : null,
                        'hoa_chat_su_dung' =>  isset($item['HoaChatSuDung']) && $item['HoaChatSuDung'] ? $item['HoaChatSuDung'] : null,
                        'nguon_nuoc_su_dung' =>  isset($item['NguonNuocSuDung']) && $item['NguonNuocSuDung'] ? $item['NguonNuocSuDung'] : null
                    ]);
                }
            }
            $this->dongBoThoiGian('co_so_san_xuat');
            DB::commit();
            $this->logDongBo('Đồng bộ môi trường cơ sở', 'T_MoiTruongCoSo', $data);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    
    public function returnUniqueProperty($array, $property)
    {
        $tempArray = array_unique(array_column($array, $property));
        $moreUniqueArray = array_values(array_intersect_key($array, $tempArray));
        return $moreUniqueArray;
    }

    public function dongBoXaPhuong()
    {
        $data = $this->getAllData('C_PhuongXa');
        DB::beginTransaction();
        DB::table('phuong_xas')->update([
            'trang_thai_dong_bo' => 'chua_dong_bo'
        ]);
        foreach ($data as $item) {
            $item = (array)$item;
            $quanHuyen = QuanHuyen::where('ma_dinh_danh', $item['QuanHuyen']->MaMuc)->first();
            $check = PhuongXa::where('ma', $item['MaMuc'])->first();
            if ($check && $quanHuyen) {
                $check->update([
                    'ten' => $item['TenMuc'],
                    'ma' => $item['MaMuc'],
                    'quan_huyen_id' => $quanHuyen->id,
                    'trang_thai_dong_bo' => 'da_dong_bo'
                ]);
            } else if ($quanHuyen) {
                PhuongXa::create([
                    'ten' => $item['TenMuc'],
                    'ma' => $item['MaMuc'],
                    'quan_huyen_id' => $quanHuyen->id,
                    'trang_thai_dong_bo' => 'da_dong_bo',
                ]);
            }
        }
        $this->dongBoThoiGian('phuong_xa');
        DB::commit();
        $this->logDongBo('Đồng bộ phường xã', 'C_PhuongXa', $data);
        return response(['message' => 'Done'], 200);
    }

    public function dongBoQuanHuyen()
    {
        $dongBoTinh = ThoiGianDongBo::where('danh_muc_dong_bo', 'tinh_thanh')->first();
        if (!$dongBoTinh) {
            $this->dongBoTinhThanh();
        }
        $data = $this->getAllData('C_QuanHuyen');
        DB::beginTransaction();
        DB::table('quan_huyens')->update([
            'trang_thai_dong_bo' => 'chua_dong_bo'
        ]);
        $daDongBo = ThoiGianDongBo::where('danh_muc_dong_bo', 'quan_huyen')->first();
        foreach ($data as $item) {
            $item = (array)$item;
            $tinhThanh = TinhThanh::where('ma', $item['TinhThanh']->MaMuc)->first();
            if ($daDongBo) {
                $check = QuanHuyen::where('ma_dinh_danh', $item['MaMuc'])->first();
                if ($check) {
                    $check->update([
                        'ten' => $item['TenMuc'],
                        'ma_dinh_danh' => $item['MaMuc'],
                        'tinh_thanh_id' => $tinhThanh->id,
                        'trang_thai_dong_bo' => 'da_dong_bo'
                    ]);
                } else {
                    QuanHuyen::create([
                        'ten' => $item['TenMuc'],
                        'ma_dinh_danh' => $item['MaMuc'],
                        'tinh_thanh_id' => $tinhThanh->id,
                        'trang_thai_dong_bo' => 'da_dong_bo',
                        'ma' => 'QG' . $item['MaMuc']
                    ]);
                }
            } else {
                $check = DB::select("select * from (
                    SELECT id, ten,tinh_thanh_id, ts_rank(to_tsvector(ten), phraseto_tsquery(:ten)) as rank FROM quan_huyens
                     order by rank desc limit 50
                    ) query WHERE tinh_thanh_id = :tinhID and rank > 0.001", ['ten' => $item['TenMuc'], 'tinhID' => $tinhThanh->id]);
                if (count($check) > 0) {
                    $quan =  QuanHuyen::where('id', $check[0]->id)->first();
                    if (!$quan->ma_dinh_danh) {
                        $quan->update([
                            'ten' => $item['TenMuc'],
                            'ma_dinh_danh' => $item['MaMuc'],
                            'trang_thai_dong_bo' => 'da_dong_bo'
                        ]);
                    }
                }
            }
        }
        $this->dongBoThoiGian('quan_huyen');
        DB::commit();
        $this->logDongBo('Đồng bộ quận huyện', 'C_QuanHuyen', $data);
        return response(['message' => 'Done'], 200);
    }
    public function dongBoDTM()
    {
        $data = $this->getAllData('C_LoaiVanBanDTM');
        DB::beginTransaction();
        foreach ($data as $item) {
            $item = (array)$item;
            $check = LoaiThuTucHanhChinh::where('phan_loai', 'C_LoaiVanBanDTM')->where('ma', $item['MaMuc'])->first();
            if ($check) {
                $check->update([
                    'ten' => $item['TenMuc'],
                    'ma' => $item['MaMuc']
                ]);
            } else {
                LoaiThuTucHanhChinh::create([
                    'ten' => $item['TenMuc'],
                    'ma' => $item['MaMuc'],
                    'phan_loai' => 'C_LoaiVanBanDTM'
                ]);
            }
        }
        $this->dongBoThoiGian('van_ban_DTM');
        DB::commit();
        $this->logDongBo('Đồng bộ loại văn bản DTM', 'C_LoaiVanBanDTM', $data);
        return response(['message' => 'Done'], 200);
    }
    public function DongboGiayPhepMoiTruong()
    {
        $data = $this->getAllData('C_LoaiGiayPhepMoiTruong');
        DB::beginTransaction();
        foreach ($data as $item) {
            $item = (array)$item;
            $check = LoaiThuTucHanhChinh::where('phan_loai', 'C_LoaiGiayPhepMoiTruong')->where('ma', $item['MaMuc'])->first();
            if ($check) {
                $check->update([
                    'ten' => $item['TenMuc'],
                    'ma' => $item['MaMuc']
                ]);
            } else {
                LoaiThuTucHanhChinh::create([
                    'ten' => $item['TenMuc'],
                    'ma' => $item['MaMuc'],
                    'phan_loai' => 'C_LoaiGiayPhepMoiTruong'
                ]);
            }
        }
        $this->dongBoThoiGian('giay_phep_mt');
        DB::commit();
        $this->logDongBo('Đồng bộ loại giấy phép môi trường', 'C_LoaiGiayPhepMoiTruong', $data);
        return response(['message' => 'Done'], 200);
    }

    public function dongBoHanhVi(Request $request, $nghi_dinh_id)
    {
        $nghiDinh = NghiDinh::findOrFail($nghi_dinh_id);
        $nghi_dinh_code = $nghiDinh->code;
        $pos = strpos($nghi_dinh_code, '/');

        try {
            $data = $this->getAllData('C_HanhViVPHC');
            DB::beginTransaction();
            // DB::table('danh_muc_hanh_vi_vi_phams')->where('phap_ly', '45/2016/NĐ-CP')->delete();
            DB::table('danh_muc_hanh_vi_vi_phams')->where('nghi_dinh_id', $nghi_dinh_id)->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
            if ($pos == false) {
                DB::rollBack();
            }
            $soNghiDinh = substr($nghi_dinh_code, 0, $pos);
            foreach ($data as $item) {
                $item = (array) $item;
                $nhomhanhvi = isset($item['NhomHanhViVPHC']) ? (array)$item['NhomHanhViVPHC'] : null;
                if (substr($item['MaMuc'], 0, $pos) == $soNghiDinh) {
                    $check = DanhMucHanhViViPham::where('ma_hanh_vi', $item['MaMuc'])->first();
                    if ($check) {
                        if (isset($item['MucTienPhatTren']) && isset($item['MucTienPhatDuoi'])) {
                            $check->update([
                                'nhom_hanh_vi' => $nhomhanhvi['TenMuc'],
                                'ten_hanh_vi' => $item['TenMuc'],
                                'phat_nho_nhat' => $item['MucTienPhatTren'],
                                'phat_lon_nhat' => $item['MucTienPhatDuoi'],
                                'trang_thai_dong_bo' => 'da_dong_bo',
                                // 'phap_ly' => '45/2022/NĐ-CP',
                                'nghi_dinh_id' => $nghi_dinh_id,
                            ]);
                        } else {
                            $check->update([
                                'nhom_hanh_vi' => $nhomhanhvi['TenMuc'],
                                'ten_hanh_vi' => $item['TenMuc'],
                                'trang_thai_dong_bo' => 'da_dong_bo'
                            ]);
                        }
                    } else {
                        if (isset($item['MucTienPhatTren']) && isset($item['MucTienPhatDuoi'])) {
                            DanhMucHanhViViPham::create([
                                'ma_hanh_vi' => $item['MaMuc'],
                                'nhom_hanh_vi' => $nhomhanhvi ? $nhomhanhvi['TenMuc'] : null,
                                'ten_hanh_vi' => $item['TenMuc'],
                                'phat_nho_nhat' => $item['MucTienPhatTren'],
                                'phat_lon_nhat' => $item['MucTienPhatDuoi'],
                                // 'phap_ly' => '45/2022/NĐ-CP',
                                'nghi_dinh_id' => $nghi_dinh_id,
                                'trang_thai_dong_bo' => 'da_dong_bo'
                            ]);
                        } else {
                            DanhMucHanhViViPham::create([
                                'ma_hanh_vi' => $item['MaMuc'],
                                'nhom_hanh_vi' => $nhomhanhvi ? $nhomhanhvi['TenMuc'] : null,
                                'ten_hanh_vi' => $item['TenMuc'],
                                // 'phap_ly' => '45/2022/NĐ-CP',
                                'nghi_dinh_id' => $nghi_dinh_id,
                                'trang_thai_dong_bo' => 'da_dong_bo'
                            ]);
                        }
                    }
                }
            }
            $this->dongBoThoiGian($nghi_dinh_code);
            DB::commit();
            $this->logDongBo('Đồng bộ hành vi vi phạm', 'C_HanhViVPHC', $data);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function dongBoNghiDinh155()
    {
        $data = $this->getAllData('C_HanhViVPHC');
        $muc = Muc::with('khoan', 'khoan.dieu')->get();
        DB::beginTransaction();
        DB::table('danh_muc_hanh_vi_vi_phams')->where('phap_ly', '155/2016/NĐ-CP')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);
        foreach ($data as $item) {
            $item = (array) $item;
            $nhomhanhvi = isset($item['NhomHanhViVPHC']) ? (array)$item['NhomHanhViVPHC'] : null;
            if (substr($item['MaMuc'], 0, 3) == '155') {
                $check = DanhMucHanhViViPham::where('ma_hanh_vi', $item['MaMuc'])->first();
                if ($check) {
                    if (isset($item['MucTienPhatTren']) && isset($item['MucTienPhatDuoi'])) {
                        $check->update([
                            'nhom_hanh_vi' => $nhomhanhvi['TenMuc'],
                            'ten_hanh_vi' => $item['TenMuc'],
                            'phat_nho_nhat' => $item['MucTienPhatTren'],
                            'phat_lon_nhat' => $item['MucTienPhatDuoi'],
                            'trang_thai_dong_bo' => 'da_dong_bo'
                        ]);
                    } else {
                        $check->update([
                            'nhom_hanh_vi' => $nhomhanhvi['TenMuc'],
                            'ten_hanh_vi' => $item['TenMuc'],
                            'trang_thai_dong_bo' => 'da_dong_bo'
                        ]);
                    }
                } else {
                    if (isset($item['MucTienPhatTren']) && isset($item['MucTienPhatDuoi'])) {
                        DanhMucHanhViViPham::create([
                            'ma_hanh_vi' => $item['MaMuc'],
                            'nhom_hanh_vi' => $nhomhanhvi ? $nhomhanhvi['TenMuc'] : null,
                            'ten_hanh_vi' => $item['TenMuc'],
                            'phat_nho_nhat' => $item['MucTienPhatTren'],
                            'phat_lon_nhat' => $item['MucTienPhatDuoi'],
                            'phap_ly' => '155/2016/NĐ-CP',
                            'trang_thai_dong_bo' => 'da_dong_bo'
                        ]);
                    } else {
                        DanhMucHanhViViPham::create([
                            'ma_hanh_vi' => $item['MaMuc'],
                            'nhom_hanh_vi' => $nhomhanhvi ? $nhomhanhvi['TenMuc'] : null,
                            'ten_hanh_vi' => $item['TenMuc'],
                            'phap_ly' => '155/2016/NĐ-CP',
                            'trang_thai_dong_bo' => 'da_dong_bo'
                        ]);
                    }
                }
            }
        }
        $this->dongBoThoiGian('nghi_dinh_155');
        DB::commit();
        $this->logDongBo('Đồng bộ nghị định 155', 'C_HanhViVPHC', $data);
        return response(['message' => 'Done'], 200);
    }

    public function testDongBo()
    {
        $data = $this->getAllData('C_LuuVucSong');
        return $data;
        DB::beginTransaction();
        foreach ($data as $item) {
            $item = (array)$item;
            $check = 2;
            if ($check) {
                TinhThanh::where('ma', $item['MaMuc'])->update([
                    'ten' => $item['TenMuc'],

                ]);
            } else {
                TinhThanh::create([
                    'ma' => $item['MaMuc'],
                    'ten' => $item['TenMuc'],
                    'trang_thai' => true
                ]);
            }
        }
        $this->dongBoThoiGian('tinh_thanh');
        DB::commit();
        return response(['message' => 'Done'], 200);
    }

    public function dongBoQuyetDinhThanhLap()
    {
        $data = $this->getAllData('T_DoanThanhTraKiemTra');
        $daDongBoLanDau = ThoiGianDongBo::where('danh_muc_dong_bo', 'quyet_dinh_thanh_lap')->first();
        try {
            DB::beginTransaction();
            DB::table('quyet_dinh_thanh_tras')->update(['trang_thai_dong_bo_ve' => 'chua_dong_bo']);

            foreach ($data as $key => $item) {
                $item = (array) $item;
                $soQd = $item['SoHieuVanBan'] ?? null;
                $ngayQd = isset($item['NgayQuyetDinh']) && $item['NgayQuyetDinh'] ? Carbon::createFromFormat('d/m/Y', $item['NgayQuyetDinh']) : null;
                $maTinh = [];
                if (isset($item['TinhThanh']) && $item['TinhThanh']) {
                    foreach ($item['TinhThanh'] as $tinh) {
                        $tinh = (array) $tinh;
                        if (isset($tinh['MaMuc']) && $tinh['MaMuc']) {
                            $maTinh[] = $tinh['MaMuc'];
                        }
                    }
                }
                $coQuan = isset($item['DonViChuTri']) && $item['DonViChuTri'] ? $item['DonViChuTri']->TenGoi : 'Tổng cục Môi trường';
                $checkCoQuan = CoQuanToChuc::where('ten', 'ilike', "%{$coQuan}%")->first();
                $coQuanID =  $checkCoQuan ? $checkCoQuan->id : 77;

                $hinhThuc = isset($item['CheDoThanhTraKiemTra']) && $item['CheDoThanhTraKiemTra'] ? $item['CheDoThanhTraKiemTra']->MaMuc ?? null : null;
                $maHinhThuc = $hinhThuc == '01' ? 'ke_hoach' : 'dot_xuat';
                $hinhThucID = CheDoThanhTra::where('ma', $maHinhThuc)->first()->id;

                $check = null;
                if (!$daDongBoLanDau) {
                    if (isset($item['metadata']) && isset($item['metadata']->NguonThamChieu) && isset($item['metadata']->NguonThamChieu->MaNguonDuLieu) && $item['metadata']->NguonThamChieu->MaNguonDuLieu == 'CSDL thanh tra') {
                        $id = $item['metadata']->NguonThamChieu->MaThamChieu;
                        $check = QuyetDinhThanhTra::where('id', $id)->first();
                    }
                } else if (isset($item['MaDinhDanh']) && $item['MaDinhDanh']) {
                    $check = QuyetDinhThanhTra::where('ma_dinh_danh', $item['MaDinhDanh'])->first();
                }
                if ($check) {
                    $id = $check->id;
                    $check->update([
                        'ten' => $item['TenGoi'] ?? '',
                        'nam_ke_hoach' => $item['NamKeHoach'] ?? null,
                        'ma_dinh_danh' => $item['MaDinhDanh'],
                        'so_quyet_dinh' => $soQd,
                        'ngay_ra_quyet_dinh' => $ngayQd,
                        'co_quan_quyet_dinh' => $coQuanID,
                        'ten_co_quan_chu_tri' => $checkCoQuan ? null : $coQuan,
                        'hinh_thuc_thanh_tra' => $hinhThucID,
                        'trang_thai_dong_bo_ve' => 'da_dong_bo',
                        'co_quan_thuc_hien' => $coQuanID,
                        'nguoi_cap_nhat' => 2
                    ]);
                    TinhThanhQuyetDinhThanhLap::where('quyet_dinh_thanh_lap_id', $id)->delete();
                    foreach ($maTinh as $ma) {
                        $tinh = TinhThanh::where('ma', $ma)->first();
                        if ($tinh) {
                            TinhThanhQuyetDinhThanhLap::create([
                                'tinh_thanh_id' => $tinh->id,
                                'quyet_dinh_thanh_lap_id' => $id
                            ]);
                        }
                    }
                } else if ($daDongBoLanDau && isset($item['MaDinhDanh']) && $item['MaDinhDanh']) {
                    $qd = QuyetDinhThanhTra::create([
                        'ten' => $item['TenGoi'],
                        'nam_ke_hoach' => $item['NamKeHoach'] ?? null,
                        'ma_dinh_danh' => $item['MaDinhDanh'],
                        'so_quyet_dinh' => $soQd,
                        'ngay_ra_quyet_dinh' => $ngayQd,
                        'co_quan_quyet_dinh' => $coQuanID,
                        'ten_co_quan_chu_tri' => $checkCoQuan ? null : $coQuan,
                        'hinh_thuc_thanh_tra' => $hinhThucID,
                        'trang_thai_dong_bo_ve' => 'da_dong_bo',
                        'co_quan_thuc_hien' => $coQuanID,
                        'nguoi_cap_nhat' => 2,
                        'nguoi_tao' => 2
                    ]);
                    foreach ($maTinh as $ma) {
                        $tinh = TinhThanh::where('ma', $ma)->first();
                        if ($tinh) {
                            TinhThanhQuyetDinhThanhLap::create([
                                'tinh_thanh_id' => $tinh->id,
                                'quyet_dinh_thanh_lap_id' => $qd->id
                            ]);
                        }
                    }
                }
            }
            $this->dongBoThoiGian('quyet_dinh_thanh_lap');
            DB::commit();
            $this->logDongBo('Đồng bộ quyết định thành lập đoàn Thanh tra', 'T_DoanThanhTraKiemTra', $data);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function dongBoKetQuaThanhTra()
    {
        $data = $this->getAllData('T_KetLuanThanhTraKiemTra');
        try {
            DB::beginTransaction();
            foreach ($data as $key => $item) {
                $item = (array) $item;
                $soQd = $item['SoHieuVanBan'] ?? null;
                $ngayBanHanh = $item['NgayBanHanh'] ? Carbon::createFromFormat('d/m/Y', $item['NgayBanHanh']) :  null;
                $maDinhDanh = $item['MaDinhDanh'] ?? null;
                if ($soQd && $ngayBanHanh && $maDinhDanh) {
                    $kltt =  KetQuaThanhTra::where('so_quyet_dinh', $soQd)->whereDate('ngay_thanh_tra', $ngayBanHanh)->first();
                    if ($kltt) {
                        $kltt->update(['ma_dinh_danh' => $maDinhDanh, 'trang_thai_dong_bo' => 'da_dong_bo']);
                    }
                }
            }
            $this->dongBoThoiGian('ket_luan_thanh_tra');
            DB::commit();
            $this->logDongBo('Đồng bộ kết luận Thanh tra', 'T_KetLuanThanhTraKiemTra', $data);
            return 'Done';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    public function dongBoQuyetDinhXuPhat()
    {
        $data = $this->getAllData('T_XuPhatViPhamHanhChinh');
        try {
            DB::beginTransaction();
            foreach ($data as $key => $item) {
                $item = (array) $item;
                $soQd = $item['SoHieuVanBan'] ?? null;
                $ngayBanHanh = $item['NgayQuyetDinh'] ? Carbon::createFromFormat('d/m/Y', $item['NgayQuyetDinh']) :  null;
                $maDinhDanh = $item['MaDinhDanh'] ?? null;
                $quyetDinhID = null;
                if (isset($item['metadata']) && isset($item['metadata']->NguonThamChieu) && isset($item['metadata']->NguonThamChieu->MaNguonDuLieu) && $item['metadata']->NguonThamChieu->MaNguonDuLieu == 'CSDL thanh tra') {
                    $quyetDinhID = $item['metadata']->NguonThamChieu->MaThamChieu;
                }

                if ($soQd && $ngayBanHanh && $maDinhDanh) {
                    $kltt =  QuyetDinhXuPhat::where('so_quyet_dinh', $soQd)->whereDate('thoi_gian_ban_hanh', $ngayBanHanh);
                    if ($quyetDinhID) {
                        $kltt->orWhere('id', $quyetDinhID);
                    }
                    $kltt =  $kltt->first();
                    if ($kltt) {
                        $kltt->update(['ma_dinh_danh' => $maDinhDanh, 'trang_thai_dong_bo' => 'da_dong_bo']);
                    }
                }
            }
            $this->dongBoThoiGian('quyet_dinh_xu_phat');
            DB::commit();
            $this->logDongBo('Đồng bộ quyết định xử phạt', 'T_XuPhatViPhamHanhChinh', $data);
            return 'Done';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    public function getCoQuan()
    {
        $data = $this->getAllData('T_CoQuanDonVi');
        return $data;
    }

    public function dongBoDiemTramQTMT()
    {

        set_time_limit(0);

        // Lấy thời gian đồng bộ gần nhất
        $thoiGianDongBo = ThoiGianDongBo::where('danh_muc_dong_bo', 'diem_tram_qtmt')->first();
        $from = $thoiGianDongBo ? $thoiGianDongBo->updated_at : null;

        // Lấy dữ liệu từ API quốc gia
        $data = $this->getAllData('T_DiemTramQTMT');

        // Chỉ lấy dữ liệu có trạng thái "02" (Chính thức)
        $data = array_filter($data, function ($value) {
            $item = (array) $value;
            return isset($item['TrangThaiDuLieu']->MaMuc) && $item['TrangThaiDuLieu']->MaMuc === '02';
        });

        DB::beginTransaction();

        // reset trạng thái
        DB::table('diem_tram_qtmt')->update(['trang_thai_dong_bo' => 'chua_dong_bo']);

        foreach ($data as $item) {
            $item = (array) $item;
            if (!isset($item['MaDinhDanh']) || !isset($item['TenGoi'])) {
                continue; // bỏ qua bản ghi không đủ dữ liệu
            }

            // Cập nhật hoặc tạo mới điểm trạm
            $diem = DiemTramQTMT::updateOrCreate(
                ['ma_dinh_danh' => $item['MaDinhDanh']],
                [
                    'ten_goi' => $item['TenGoi'] ?? null,
                    'dia_chi_chi_tiet' => $item['DiaChi']->DiaChiChiTiet ?? null,
                    'phuong_xa_ma' => $item['DiaChi']->PhuongXa->MaMuc ?? null,
                    'phuong_xa_ten' => $item['DiaChi']->PhuongXa->TenMuc ?? null,
                    'quan_huyen_ma' => $item['DiaChi']->QuanHuyen->MaMuc ?? null,
                    'quan_huyen_ten' => $item['DiaChi']->QuanHuyen->TenMuc ?? null,
                    'tinh_thanh_ma' => $item['DiaChi']->TinhThanh->MaMuc ?? null,
                    'tinh_thanh_ten' => $item['DiaChi']->TinhThanh->TenMuc ?? null,
                    'kinh_do' => $item['ViTriDiaLy']->KinhDo ?? null,
                    'vi_do' => $item['ViTriDiaLy']->ViDo ?? null,
                    'kenh_song_ma' => $item['KenhSong']->MaMuc ?? null,
                    'kenh_song_ten' => $item['KenhSong']->TenMuc ?? null,
                    'luu_vuc_song_ma' => $item['LuuVucSong']->MaMuc ?? null,
                    'luu_vuc_song_ten' => $item['LuuVucSong']->TenMuc ?? null,
                    'loai_diem_qtmt_ma' => $item['LoaiDiemTramQTMT']->MaMuc ?? null,
                    'loai_diem_qtmt_ten' => $item['LoaiDiemTramQTMT']->TenMuc ?? null,
                    'trang_thai_ma' => $item['TrangThaiDuLieu']->MaMuc ?? null,
                    'trang_thai_ten' => $item['TrangThaiDuLieu']->TenMuc ?? null,
                    'trang_thai_dong_bo' => 'da_dong_bo',
                    'modified_at' => $this->safeParseDate($item['modifiedAt'] ?? null),
                ]
            );

            // Xóa và ghi lại danh sách thông số môi trường (PhamViQTMT)
            $diem->phamVi()->delete();

            if (!empty($item['PhamViQTMT'])) {
                foreach ($item['PhamViQTMT'] as $pv) {
                    $pv = (array) $pv;
                    $diem->phamVi()->create([
                        'thong_so_ma' => $pv['ThongSoMoiTruong']->MaMuc ?? null,
                        'thong_so_ten' => $pv['ThongSoMoiTruong']->TenMuc ?? null,
                        'quy_chuan_ma' => $pv['QuyChuanMoiTruong']->MaMuc ?? null,
                        'quy_chuan_ten' => $pv['QuyChuanMoiTruong']->TenMuc ?? null,
                    ]);
                }
            }
        }

        // cập nhật lại thời gian đồng bộ
        $this->dongBoThoiGian('diem_tram_qtmt');

        DB::commit();

        /* ===================== 🔹 GHI LOG ĐỒNG BỘ 🔹 ===================== */

        // Chuẩn hóa dữ liệu trả về
        $cleanData = json_decode(json_encode($data), true);

        // Dữ liệu yêu cầu (request)
        $requestData = [
            'endpoint' => 'T_DiemTramQTMT',
            'from' => $from,
            'record_count' => count($cleanData),
            'triggered_by' => Auth::user()->name ?? 'Hệ thống',
            'executed_at' => now()->format('Y-m-d H:i:s'),
        ];

        DongBoMtqgLog::log(
            'Đồng bộ điểm trạm QTMT',
            'Đã đồng bộ thành công ' . count($cleanData) . ' điểm trạm QTMT từ CSDL quốc gia',
            $requestData,
            $cleanData
        );

        return response([
            'message' => 'Đồng bộ điểm trạm quan trắc thành công',
            'record_count' => count($cleanData),
        ], 200);
    }

    private function streamData(string $type, ?Carbon $from = null): \Generator
    {
        $totalPages = $this->getTotalPage($type, $from);
        for ($i = 0; $i < $totalPages; $i++) {
            $pageData = (array) $this->getData($i, $type, $from);
            if (!$pageData) continue;
            yield [
                'page'        => $i,
                'total_pages' => $totalPages,
                'data'        => $pageData,
            ];
        }
    }



    private function strToBool($v): bool
    {
        if (is_bool($v)) return $v;
        $v = strtolower((string)$v);
        return in_array($v, ['1', 'true', 'yes', 'on'], true);
    }

    // public function dongBoKetQuaQTMT(Request $request)
    // {
    //     set_time_limit(0);

    //     $tg   = ThoiGianDongBo::where('danh_muc_dong_bo', 'ket_qua_qtmt')->first();

    //     // ❌ $request->boolean('full') → lỗi
    //     // ✅ parse thủ công
    //     $full = $this->strToBool($request->query('full', false));
    //     $from = $full ? null : ($tg->updated_at ?? null);

    //     $maxPages = (int) $request->query('max_pages', 20);
    //     $maxItems = (int) $request->query('max_items', 200);
    //     $type = 'T_KetQuaQTMT';

    //     // Thăm dò trang 0 (có dữ liệu không)
    //     $probe = (array) $this->getData(0, $type, $from);
    //     if (empty($probe)) {
    //         // fallback: bỏ from để luôn xem được mẫu dữ liệu
    //         $from  = null;
    //         $probe = (array) $this->getData(0, $type, $from);
    //     }

    //     $collected = [];
    //     $collectedCount = 0;
    //     $pagesFetched = 0;

    //     // nhặt mẫu từ trang 0
    //     if (!empty($probe)) {
    //         $pagesFetched++;
    //         foreach ($probe as $it) {
    //             if ($collectedCount >= $maxItems) break;
    //             $arr = (array) $it;
    //             $collected[] = [
    //                 'MaDinhDanh'      => $arr['MaDinhDanh'] ?? null,
    //                 'TenGoi'          => $arr['TenGoi'] ?? null,
    //                 'TrangThaiDuLieu' => isset($arr['TrangThaiDuLieu']->MaMuc) ? $arr['TrangThaiDuLieu']->MaMuc : null,
    //                 'DiemTramQTMT'    => isset($arr['DiemTramQTMT']->MaMuc) ? $arr['DiemTramQTMT']->MaMuc : null,
    //                 'ThongSo'         => isset($arr['ThongSoMoiTruong']->MaMuc) ? $arr['ThongSoMoiTruong']->MaMuc : null,
    //                 'ThoiGian'        => $arr['ThoiGianQuanTrac'] ?? ($arr['ThoiGian'] ?? null),
    //                 'GiaTri'          => $arr['GiaTri'] ?? null,
    //                 'modifiedAt'      => $arr['modifiedAt'] ?? null,
    //             ];
    //             $collectedCount++;
    //         }
    //     }

    //     // stream các trang tiếp theo
    //     foreach ($this->streamData($type, $from) as $chunk) {
    //         if ($chunk['page'] === 0) continue;          // đã xử lý ở trên
    //         if ($pagesFetched >= $maxPages) break;

    //         $pagesFetched++;
    //         foreach ((array)$chunk['data'] as $it) {
    //             if ($collectedCount >= $maxItems) break 2;
    //             $arr = (array)$it;
    //             $collected[] = [
    //                 'MaDinhDanh'      => $arr['MaDinhDanh'] ?? null,
    //                 'TenGoi'          => $arr['TenGoi'] ?? null,
    //                 'TrangThaiDuLieu' => isset($arr['TrangThaiDuLieu']->MaMuc) ? $arr['TrangThaiDuLieu']->MaMuc : null,
    //                 'DiemTramQTMT'    => isset($arr['DiemTramQTMT']->MaMuc) ? $arr['DiemTramQTMT']->MaMuc : null,
    //                 'ThongSo'         => isset($arr['ThongSoMoiTruong']->MaMuc) ? $arr['ThongSoMoiTruong']->MaMuc : null,
    //                 'ThoiGian'        => $arr['ThoiGianQuanTrac'] ?? ($arr['ThoiGian'] ?? null),
    //                 'GiaTri'          => $arr['GiaTri'] ?? null,
    //                 'modifiedAt'      => $arr['modifiedAt'] ?? null,
    //             ];
    //             $collectedCount++;
    //         }
    //     }
    //     dd($collected);
    //     return response()->json([
    //         'message'        => 'Preview T_KetQuaQTMT (stream)',
    //         'from'           => $from ? $from->toIso8601String() : null,
    //         'pages_fetched'  => $pagesFetched,
    //         'items_returned' => count($collected),
    //         'hint'           => 'Dùng ?full=1 để bỏ from khi debug; ?max_pages=&max_items= để giới hạn.',
    //         'data'           => $collected,
    //     ], 200);
    // }
    private function toArrayRecursive($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'toArrayRecursive'], $data);
        } elseif (is_object($data)) {
            return $this->toArrayRecursive((array) $data);
        }
        return $data;
    }
    public function dongBoKetQuaQTMT(Request $request)
    {
        set_time_limit(0);
        DB::connection()->disableQueryLog();

        $tg   = ThoiGianDongBo::where('danh_muc_dong_bo', 'ket_qua_qtmt')->first();
        $full = $this->strToBool($request->query('full', false));
        $from = $full ? null : ($tg->updated_at ?? null);

        $maxPages = (int) $request->query('max_pages', 1122);
        $type = 'T_KetQuaQTMT';
        $pagesFetched = 0;
        $totalSavedRows = 0;

        // Danh sách giá trị cần loại bỏ
        $invalidValues = ['KPH', 'ND', '-', 'Không phát hiện', 'Khong phat hien', ''];

        $processPage = function (array $page) use (&$totalSavedRows, $invalidValues) {
            $rows = [];

            foreach ($page as $item) {
                // Đảm bảo kiểu array
                $item = $this->toArrayRecursive($item);

                // ✅ Kiểm tra trạng thái chính thức
                $trangThai = (array) ($item['TrangThaiDuLieu'] ?? []);
                // if (($trangThai['MaMuc'] ?? null) !== '02') continue;

                // ✅ Xử lý SoLieuQTMT
                $soLieus = $item['SoLieuQTMT'] ?? [];
                if (is_object($soLieus)) $soLieus = [$soLieus];
                if (!is_array($soLieus) || empty($soLieus)) continue;

                // ✅ Thông tin chung
                $meta = [
                    'ma_dinh_danh'      => $item['MaDinhDanh'] ?? null,
                    'ma_tram'           => $item['ViTriQTMT']['MaDinhDanh'] ?? null,
                    'ten_tram'          => $item['ViTriQTMT']['TenGoi'] ?? null,
                    'loai_hinh_qtmt'    => $item['LoaiHinhQTMT']['TenMuc'] ?? null,
                    'loai_so_lieu_qtmt' => $item['LoaiSoLieuQTMT']['TenMuc'] ?? null,
                    'nam_quan_trac'     => $item['NamQuanTrac'] ?? null,
                    'thang_quan_trac'   => $item['ThangQuanTrac'] ?? null,
                    'trang_thai'        => $trangThai['TenMuc'] ?? null,
                    'nguon_tham_chieu'  => $item['metadata']['NguonThamChieu']['MaNguonDuLieu'] ?? null,
                    'modified_at'       => $item['modifiedAt'] ?? null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];

                foreach ($soLieus as $sl) {
                    $sl = (array) $sl;

                    $value = isset($sl['GiaTriQuanTrac']) ? trim((string)$sl['GiaTriQuanTrac']) : '';

                    // Bỏ giá trị không hợp lệ
                    if (in_array(mb_strtoupper($value), array_map('mb_strtoupper', $invalidValues), true)) {
                        continue;
                    }

                    $rows[] = array_merge($meta, [
                        'thong_so_ma'          => $sl['ThongSoMoiTruong']['MaMuc'] ?? null,
                        'thong_so_ten'         => $sl['ThongSoMoiTruong']['TenMuc'] ?? null,
                        'quy_chuan_ma'         => $sl['QuyChuanMoiTruong']['MaMuc'] ?? null,
                        'quy_chuan_ten'        => $sl['QuyChuanMoiTruong']['TenMuc'] ?? null,
                        'don_vi_ma'            => $sl['DonViDo']['MaMuc'] ?? null,
                        'don_vi_ten'           => $sl['DonViDo']['TenMuc'] ?? null,
                        'gia_tri_quan_trac'    => $value ?: null,
                        'gia_tri_gioi_han_max' => (isset($sl['GiaTriGioiHanMax']) && is_numeric($sl['GiaTriGioiHanMax']))
                            ? (float)$sl['GiaTriGioiHanMax'] : null,
                    ]);
                }
            }

            if (empty($rows)) return;

            foreach (array_chunk($rows, 300) as $batch) {
                DB::beginTransaction();
                try {
                    foreach ($batch as $row) {
                        if (!$row['ma_dinh_danh'] || !$row['thong_so_ma']) {
                            \Log::warning('Bỏ qua dòng vì thiếu khóa chính', ['row' => $row]);
                            continue;
                        }
                        KetQuaQtmt::updateOrCreate(
                            [
                                'ma_dinh_danh' => $row['ma_dinh_danh'],
                                'thong_so_ma'  => $row['thong_so_ma'],
                            ],
                            $row
                        );
                    }
                    DB::commit();
                    $totalSavedRows += count($batch);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    \Log::error('Lỗi lưu batch KQ QTMT', ['message' => $e->getMessage()]);
                }
            }
            // dd($rows);
        };

        // Trang đầu tiên
        $probe = (array) $this->getData(0, $type, $from);
        if (empty($probe)) {
            $from  = null;
            $probe = (array) $this->getData(0, $type, $from);
        }

        if (!empty($probe)) {
            $processPage($probe);
            $pagesFetched++;
            $this->dongBoThoiGian('ket_qua_qtmt');
        }

        // Stream các trang sau
        foreach ($this->streamData($type, $from) as $chunk) {
            if ($chunk['page'] === 0) continue;
            if ($pagesFetched >= $maxPages) break;

            $pagesFetched++;
            $processPage((array)$chunk['data']);
            $this->dongBoThoiGian('ket_qua_qtmt');
        }

        DongBoMtqgLog::log(
            'Đồng bộ KQ QTMT',
            "Đã lưu {$totalSavedRows} bản ghi (Chính thức) từ {$pagesFetched} trang."
        );

        return response()->json([
            'message'       => 'Đồng bộ dữ liệu KQ QTMT thành công',
            'pages_fetched' => $pagesFetched,
            'records_saved' => $totalSavedRows,
            'from'          => $from ? $from->toIso8601String() : null,
        ]);
    }



    private function safeParseDate($val)
    {
        try {
            if (empty($val)) {
                return null;
            }

            // Nếu là số (timestamp dạng ms)
            if (is_numeric($val)) {
                $ts = intval($val);
                if ($ts === 0) {
                    return null;
                }
                return \Carbon\Carbon::createFromTimestampMs($ts);
            }

            // Nếu là chuỗi ISO 8601 (vd: 2023-10-25T13:49:29Z)
            return \Carbon\Carbon::parse($val);
        } catch (\Exception $e) {
            return null; // dữ liệu sai format thì bỏ qua
        }
    }
}
