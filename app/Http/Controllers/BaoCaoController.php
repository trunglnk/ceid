<?php

namespace App\Http\Controllers;

use App\CoSoSanXuat;
use App\CoSoSanXuatLoaiHinhONhiem;
use App\CoSoSanXuatLoaiNganhNghe;
use App\KetQuaThanhTraChung;
use App\TinhThanhLuuVucSong;
use Illuminate\Support\Facades\Log;
use App\Traits\ExecuteExcel;
use App\Traits\GetDataCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\LoaiThuTucHanhChinh;
use App\TinhThanh;

class BaoCaoController extends Controller
{
    use GetDataCache, ExecuteExcel;

    public function __construct()
    {
        $this->middleware(['auth', 'permission']);
    }


    function baocaotonghop(Request $request)
    {
        $query = \App\Organization::query()->has('coSoSanXuats');
        $search_start_date = $request->get('search_start_date');
        $search_end_date = $request->get('search_end_date');
        $perpage = $request->input('perpage', 100);
        $order_column = $request->input('order_column', 'ten');
        $order_direction = $request->input('order_direction', 'asc');
        if (isset($search_start_date) && $search_start_date) {
            $search_start_date = Carbon::createFromFormat(config('app.format_date'), $search_start_date)->startOfDay();
        }
        if (isset($search_end_date)) {
            $search_end_date = Carbon::createFromFormat(config('app.format_date'), $search_end_date)->endOfDay();
        } else {
            $search_end_date = Carbon::now()->endOfYear();
        }
        $info = $request->all();
        $tinhThanhs = $this->getDataByName(\App\TinhThanh::class);
        $query->whereHas('ketQuaThanhTras', function ($query) use ($search_end_date) {
            $query->whereHas('quyetDinhThanhTra', function ($query) use ($search_end_date) {
                $query->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
            });
        });
        if ($request->get('search_loai_van_ban')) {
            $search_loai_van_ban = $request->get('search_loai_van_ban');
            $array = explode(' ', $search_loai_van_ban);
            foreach ($array as $item) {
                $item = json_decode($item);
                $item = (array)$item;
                $key = array_keys($item)[0];
                $query->whereHas('coSoSanXuats', function ($query) use ($key, $item) {
                    $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($key, $item) {
                        $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($key, $item) {
                            $query->whereHas('loaiThuTuc', function ($query) use ($key) {
                                $query->where('id', $key);
                            });
                            if ($item[$key]) {
                                $query->whereIn('co_quan_phe_duyet_id', explode(',', $item[$key]));
                            }
                        });
                    });
                });
            }
        };
        if ($search_start_date) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($search_start_date) {
                $query->whereHas('quyetDinhThanhTra', function ($query) use ($search_start_date) {
                    $query->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
                });
            });
        }
        $this->getQueryOrganization($info, $query, $tinhThanhs);
        $cloneOrganization = clone $query;
        $organizations_vi_pham = clone  $cloneOrganization->whereHas('ketQuaThanhTras', function ($query) {
            $query->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('vi_pham', true);
            });
        });
        $organizations_dinh_chi = clone  $cloneOrganization->whereHas('ketQuaThanhTras', function ($query) {
            $query->whereHas('quyetDinhXuPhats', function ($query) {
                $query->whereHas('xuPhatBoSung', function ($query) {
                    $query->where('dinh_chi', true);
                });
            });
        });

        $query->orderBy($order_column, $order_direction);

        $query->with([
            'coSoSanXuats.khuCongNghiep.tinhThanh',
            'coSoSanXuats.chiTietCongSuat.loaiHinh',
        ]);
        $query_organization = clone $query;
        $organizations = $query->get();

        $query->with(['ketQuaThanhTras' => function ($query) use ($request) {
            $this->getQueryKetQuaThanhTra($request->all(), $query);
        }]);

        $data['tong_so_co_so'] = $organizations->count();
        $data['tong_so_co_so_vi_pham'] = $organizations_vi_pham->count();
        $data['tong_so_co_so_bi_dinh_chi'] = $organizations_dinh_chi->count();
        $query_organization = clone $query;
        $query_organization->with([
            'ketQuaThanhTras.quyetDinhThanhTra',
            'ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.coQuanToChuc',
            'ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc',
            'ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.loaiHinhCoSo',
            'ketQuaThanhTras.quyetDinhXuPhats.xuPhatChinh',
            'ketQuaThanhTras.quyetDinhXuPhats.xuPhatBoSung.chiTietXuPhatBoSungs',
            'ketQuaThanhTras.quyetDinhXuPhats.bienPhapKhacPhucHauQua.chiTietBienPhapKhacPhucHauQuas',
            'ketQuaThanhTras.quyetDinhXuPhats.ketQuaThanhTra',
            'ketQuaThanhTras.ketQuaKhacPhucHauQuas',
            'ketQuaThanhTras.nhomHanhViViPhams'
        ]);

        $data['organizations'] = $query_organization->paginate($perpage);
        // report theo khu cong nghiep
        $khucongnghieps = collect();
        $organizations->each(function ($item, $key) use ($khucongnghieps) {
            $item->coSoSanXuats->each(function ($item, $key) use ($khucongnghieps) {
                if (isset($item->khuCongNghiep->ten)) {
                    $khucongnghieps->push($item->khuCongNghiep);
                }
            });
        });
        $data['khu_cong_nghiep'] = $khucongnghieps
            ->groupBy('ten')
            ->sortBy('ten')
            ->transform(function ($item, $key) {
                return $item->count('id');
            })->sortBy('ten')->toArray();

        // query theo doan thanh tra
        $queryQuyetDinhThanhTra = \App\QuyetDinhThanhTra::query();
        if ($search_start_date) {
            $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
        }
        $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
        $queryQuyetDinhThanhTra->whereHas('ketQuaThanhTras', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryQuyetDinhThanhTra($queryQuyetDinhThanhTra);
        $quyetDinhThanhTras = $queryQuyetDinhThanhTra->with('cheDoThanhTra')->get();
        $total_ke_hoach = $quyetDinhThanhTras->filter(function ($item) {
            return $item->cheDoThanhTra->ma == 'ke_hoach';
        })->count();
        $data['ty_le_thanh_tra_ke_hoach'] = ($quyetDinhThanhTras->count() > 0) ? round($total_ke_hoach * 100 / $quyetDinhThanhTras->count(), 2) : 0;

        // ket qua thanh tra
        $queryKetQuaThanhTra = \App\KetQuaThanhTra::query();
        $queryKetQuaThanhTra->where(function ($query) use ($quyetDinhThanhTras, $organizations) {
            $query->whereIn('quyet_dinh_thanh_tra_id', $quyetDinhThanhTras->pluck('id'));
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });

        $ketQuaThanhTras =  $queryKetQuaThanhTra->with([
            'quyetDinhThanhTra',
            'nhomHanhViViPhams',
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.coQuanToChuc',
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.loaiHinhCoSo',
            'quyetDinhXuPhats.xuPhatChinh',
            'quyetDinhXuPhats.xuPhatBoSung.chiTietXuPhatBoSungs',
            'quyetDinhXuPhats.bienPhapKhacPhucHauQua.chiTietBienPhapKhacPhucHauQuas',
            'quyetDinhXuPhats.ketQuaThanhTra',
            'ketQuaKhacPhucHauQuas',
            'organization',
            'organization.coSoSanXuats:id,organization_id'
        ])->get();

        $data['danh_sach_ket_qua_thanh_tra'] = $ketQuaThanhTras;

        // khai bao bien chua ket qua bao cao
        $ketQuaThanhTraThuTucHanhChinhs = collect();
        $khacphuchauquas = collect();
        $quyetdinhxuphats = collect();
        $chiTietXuPhatBosung = collect();
        $chiTietBienPhapKhacPhucHauQua = collect();
        $danhSachKetQuaKhacPhucHauQua = collect();
        $danhsachLoaiHinhCoSo = collect();
        // report theo loai co quan phe duyet ĐTM, ĐABVMT, KHBVMT
        // $loaitt = $this->getDataByName(\App\LoaiThuTucHanhChinh::class);
        $loaitt = LoaiThuTucHanhChinh::query();
        $dtm = LoaiThuTucHanhChinh::where('phan_loai', 'C_LoaiVanBanDTM')->first();

        $dabvmt = LoaiThuTucHanhChinh::where('ma', '04')->where('phan_loai', 'C_LoaiVanBanDTM')->first();
        $gxnctbvmt = LoaiThuTucHanhChinh::where('ma', '01')->where('phan_loai', 'C_LoaiGiayPhepMoiTruong')->first();

        $ketQuaThanhTras->each(function ($item, $key) use (
            $ketQuaThanhTraThuTucHanhChinhs,
            $khacphuchauquas,
            $quyetdinhxuphats,
            $chiTietXuPhatBosung,
            $chiTietBienPhapKhacPhucHauQua,
            $danhSachKetQuaKhacPhucHauQua,
            $danhsachLoaiHinhCoSo
        ) {
            $item->ketQuaThanhTraChungs->each(function ($item, $key) use ($ketQuaThanhTraThuTucHanhChinhs, $danhsachLoaiHinhCoSo) {
                $item->ketQuaThanhTraThuTucHanhChinhs->each(function ($value, $key) use ($ketQuaThanhTraThuTucHanhChinhs) {
                    $ketQuaThanhTraThuTucHanhChinhs->push($value);
                });
                $item->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($danhsachLoaiHinhCoSo) {
                    $danhsachLoaiHinhCoSo->push($value->loaiHinhCoSo);
                });
            });

            $item->ketQuaKhacPhucHauQuas->each(function ($value, $key) use ($danhSachKetQuaKhacPhucHauQua) {
                $danhSachKetQuaKhacPhucHauQua->push($value);
            });
            $xuPhatChinhs = collect();
            $item->quyetDinhXuPhats->each(function ($value, $key) use ($quyetdinhxuphats, $chiTietXuPhatBosung, $chiTietBienPhapKhacPhucHauQua, $xuPhatChinhs) {
                $quyetdinhxuphats->push($value);
                $value->xuPhatBoSung->each(function ($value, $key) use ($chiTietXuPhatBosung) {
                    $value->chiTietXuPhatBoSungs->each(function ($value, $key) use ($chiTietXuPhatBosung) {
                        $chiTietXuPhatBosung->push($value);
                    });
                });
                $value->xuPhatChinh->each(function ($value, $key) use ($xuPhatChinhs) {
                    $xuPhatChinhs->push($value);
                });
                $value->bienPhapKhacPhucHauQua->each(function ($value, $key) use ($chiTietBienPhapKhacPhucHauQua) {
                    $value->chiTietBienPhapKhacPhucHauQuas->each(function ($value, $key) use ($chiTietBienPhapKhacPhucHauQua) {
                        $chiTietBienPhapKhacPhucHauQua->push($value);
                    });
                });
            });
            $item->so_tien_xu_phat_chinh = $xuPhatChinhs->sum('so_tien_xu_phat_chinh');
        });

        $quyetDinhThanhTras->each(function ($item, $key) use ($quyetdinhxuphats) {
            $quyetdinhxuphats->each(function ($quyetDinhXuPhat, $key) use ($item) {
                if (!empty($quyetDinhXuPhat->ketQuaThanhTra)) {
                    if ($quyetDinhXuPhat->ketQuaThanhTra->quyet_dinh_thanh_tra_id == $item->id) {
                        if (empty($item->tong_so_tien_phat)) {
                            $item->tong_so_tien_phat = 0;
                        }
                        $quyetDinhXuPhat->xuPhatChinh->each(function ($xuPhatChinh, $key) use ($item) {
                            $item->tong_so_tien_phat += $xuPhatChinh->so_tien_xu_phat_chinh;
                        });
                    }
                }
            });
        });
        $data['tong_so_tien_phat'] = $quyetDinhThanhTras->sum('tong_so_tien_phat');
        $data['doan_thanh_tra'] = $quyetDinhThanhTras->sortBy('ngay_ra_quyet_dinh')->toArray();

        $ketQuaThanhTraThuTucHanhChinhs = $ketQuaThanhTraThuTucHanhChinhs->sortBy('coQuanToChuc.order');
        if (!empty($search_co_quan_phe_duyet_dtm)) {
            $data['co_quan_phe_duyet']['dtm'] = $ketQuaThanhTraThuTucHanhChinhs->where('loai_thu_tuc_hanh_chinh_id', $dtm->id)
                ->whereIn('co_quan_phe_duyet_id', explode(',', $search_co_quan_phe_duyet_dtm))
                ->groupBy('coQuanToChuc.ten')->map(function ($item) {
                    return $item->count('id');
                })->toArray();
        } else {
            $data['co_quan_phe_duyet']['dtm'] = $ketQuaThanhTraThuTucHanhChinhs->where('loai_thu_tuc_hanh_chinh_id', $dtm->id)
                ->groupBy('coQuanToChuc.ten')->map(function ($item) {
                    return $item->count('id');
                })->toArray();
        }


        if (!empty($search_co_quan_phe_duyet_dabvmt)) {
            $data['co_quan_phe_duyet']['dabvmt'] = $ketQuaThanhTraThuTucHanhChinhs
                ->where('loai_thu_tuc_hanh_chinh_id', $dabvmt->id)
                ->whereIn('co_quan_phe_duyet_id', explode(',', $search_co_quan_phe_duyet_dabvmt))
                ->groupBy('coQuanToChuc.ten')->map(function ($item) {
                    return $item->count('id');
                })->toArray();
        } else {
            $data['co_quan_phe_duyet']['dabvmt'] = $ketQuaThanhTraThuTucHanhChinhs
                ->where('loai_thu_tuc_hanh_chinh_id', $dabvmt->id)
                ->groupBy('coQuanToChuc.ten')->map(function ($item) {
                    return $item->count('id');
                })->toArray();
        }
        if (!empty($search_co_quan_phe_duyet_gxnctbvmt)) {
            $data['co_quan_phe_duyet']['gxnctbvmt'] = $ketQuaThanhTraThuTucHanhChinhs
                ->where('loai_thu_tuc_hanh_chinh_id', $gxnctbvmt->id)
                ->whereIn('co_quan_phe_duyet_id', explode(',', $search_co_quan_phe_duyet_gxnctbvmt))
                ->groupBy('coQuanToChuc.ten')->map(function ($item) {
                    return $item->count('id');
                })->toArray();
        } else {
            $data['co_quan_phe_duyet']['gxnctbvmt'] = $ketQuaThanhTraThuTucHanhChinhs
                ->where('loai_thu_tuc_hanh_chinh_id', $gxnctbvmt->id)
                ->groupBy('coQuanToChuc.ten')->map(function ($item) {
                    return $item->count('id');
                })->toArray();
        }

        // report cac bien phap khac phuc hau qua
        $cacbpkhvp = $this->getDataByName(\App\CacBienPhapKhacPhucHauQua::class);
        $cacbpkhvp->each(function ($item, $key) use ($chiTietBienPhapKhacPhucHauQua, $data) {
            $countByBienPhapKhacPhuc = $chiTietBienPhapKhacPhucHauQua->search(function ($item, $key) {
                return $item->cac_bien_phap_khac_phuc_hau_qua_id == $item->id;
            });
            if ($countByBienPhapKhacPhuc > 0) {
                $data['khac_phuc_vi_pham'][$item->ten] = $countByBienPhapKhacPhuc;
            }
        });

        $data['ket_qua_thuc_hien_ket_luan_thanh_tra'] = [
            'da_khac_phuc' => $danhSachKetQuaKhacPhucHauQua->where('da_khac_phuc', true)->count(),
            'chua_khac_phuc' => $danhSachKetQuaKhacPhucHauQua->where('da_khac_phuc', false)->count(),
            'da_nop_phat' => $danhSachKetQuaKhacPhucHauQua->where('da_nop_phat', true)->count(),
            'da_bao_cao' => $danhSachKetQuaKhacPhucHauQua->where('da_bao_cao', true)->count()
        ];
        $data['ket_qua_thuc_hien_ket_luan_thanh_tra'] =  array_merge($data['ket_qua_thuc_hien_ket_luan_thanh_tra'], [
            'tong_bao_cao' => $data['ket_qua_thuc_hien_ket_luan_thanh_tra']['da_khac_phuc'] + $data['ket_qua_thuc_hien_ket_luan_thanh_tra']['da_bao_cao'] + $data['ket_qua_thuc_hien_ket_luan_thanh_tra']['da_nop_phat']
        ]);
        // report cac bien phap xu phat bo xung
        $cacbpxpbx = $this->getDataByName(\App\LoaiXuPhatBoSung::class);
        $cacbpxpbx->each(function ($item, $key) use ($chiTietXuPhatBosung, $data) {
            $count = $chiTietXuPhatBosung->search(function ($item, $key) {
                return $item->loai_xu_phat_bo_sung_id == $item->id;
            });
            if ($count > 0) {
                $data['xu_phat_bo_xung'][$item->ten] = $count;
            }
        });

        $data['loai_hinh_co_so'] = $danhsachLoaiHinhCoSo->groupBy('ten')->map(function ($item, $key) {
            return collect($item)->count();
        })->sortBy('ten');

        if ($request->ajax()) {
            return response()->json([
                'result' => $data,
            ], 200, []);
        }
        return view('baocao.tonghop', ['data' => collect($data)]);
    }

    function xuatExcelBaoCao(Request $request)
    {
        $query = \App\Organization::query();
        $search_start_date = $request->get('search_start_date');
        $search_end_date = $request->get('search_end_date');
        if (isset($search_start_date)) {
            $search_start_date = Carbon::createFromFormat(config('app.format_date'), $search_start_date)->startOfDay();
        } else {
            $search_start_date = Carbon::now()->addYears(-2)->startOfDay();
        }
        if (isset($search_end_date)) {
            $search_end_date = Carbon::createFromFormat(config('app.format_date'), $search_end_date)->endOfDay();
        } else {
            $search_end_date = Carbon::now()->endOfDay();
        }
        $info = $request->all();
        $tinhThanhs = $this->getDataByName(\App\TinhThanh::class);
        $query->whereHas('ketQuaThanhTras', function ($query) use ($search_end_date, $search_start_date) {
            $query->whereHas('quyetDinhThanhTra', function ($query) use ($search_end_date, $search_start_date) {
                $query->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
                $query->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
            });
        });
        $this->getQueryOrganization($info, $query, $tinhThanhs);
        $organizations = $query->get();

        // query theo doan thanh tra
        $queryQuyetDinhThanhTra = \App\QuyetDinhThanhTra::query();
        $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
        $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
        $queryQuyetDinhThanhTra->whereHas('ketQuaThanhTras', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryQuyetDinhThanhTra($queryQuyetDinhThanhTra);
        $quyetDinhThanhTras = $queryQuyetDinhThanhTra->get();

        // ket qua thanh tra
        $queryKetQuaThanhTra = \App\KetQuaThanhTra::query();
        $queryKetQuaThanhTra->where(function ($query) use ($quyetDinhThanhTras, $organizations) {
            $query->whereIn('quyet_dinh_thanh_tra_id', $quyetDinhThanhTras->pluck('id'));
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryKetQuaThanhTra($info, $queryKetQuaThanhTra);

        $ketQuaThanhTras = $queryKetQuaThanhTra->with([
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.coQuanToChuc',
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.loaiHinhCoSo',
            'ketQuaThanhTraChungs.ketQuaThanhTraNuocThais',
            'quyetDinhXuPhats.xuPhatChinh',
            'quyetDinhXuPhats.xuPhatBoSung.chiTietXuPhatBoSungs',
            'quyetDinhXuPhats.bienPhapKhacPhucHauQua.chiTietBienPhapKhacPhucHauQuas',
            'quyetDinhXuPhats.ketQuaThanhTra',
            'ketQuaKhacPhucHauQuas',
            'nhomHanhViViPhams',
            'quyetDinhThanhTra'
        ])->get();

        $ketQuaThanhTrasSorted = $ketQuaThanhTras->sortBy(function ($value, $key) {
            return $value->organization_id;
        });
        // export excel
        $excelFile = public_path() . '/report/report.xlsx';

        \Excel::load($excelFile, function ($doc) use ($ketQuaThanhTrasSorted) {
            $doc->sheet('KẾT QUẢ THANH TRA', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 4), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 4), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 4), $item->organization->dia_chi_lien_he);
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 4), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));
                    $danhSachThuTucHanhChinhCapBoPheDuyet = collect();
                    $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet = collect();
                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($danhSachThuTucHanhChinhCapBoPheDuyet, $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet) {
                        $value->ketQuaThanhTraThuTucHanhChinhs->each(function ($value, $key) use ($danhSachThuTucHanhChinhCapBoPheDuyet, $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet) {
                            $fullString = $value->coQuanToChuc->ten . "(" . $value->loaiThuTuc->ten . ")";
                            if ($value->coQuanToChuc->cap_bo) {
                                $danhSachThuTucHanhChinhCapBoPheDuyet->push($fullString);
                            } else {
                                $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet->push($fullString);
                            }
                        });
                    });
                    $this->setValue($sheet, 'E' . strval($key + 4), $danhSachThuTucHanhChinhCapBoPheDuyet->unique('id')->implode("\n"));
                    $this->setValue($sheet, 'F' . strval($key + 4), $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet->unique('id')->implode("\n"));
                    $this->setValue($sheet, 'G' . strval($key + 4), $item->quyetDinhThanhTra->so_quyet_dinh . "(" . $item->quyetDinhThanhTra->ngay_ra_quyet_dinh . ")");
                    $this->setValue($sheet, 'H' . strval($key + 4), $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")");

                    $danhSachQuyetDinhXuPhat = collect();
                    $danhSachXuPhatChinh = collect();
                    $danhSachXuPhatBoXung = collect();
                    $danhSachBienPhapKhacPhucHauQua = collect();
                    $danhSachViPhamTonTai = collect();
                    $item->quyetDinhXuPhats->each(function ($value, $key) use ($danhSachViPhamTonTai, $danhSachQuyetDinhXuPhat, $danhSachXuPhatChinh, $danhSachXuPhatBoXung, $danhSachBienPhapKhacPhucHauQua) {
                        $danhSachQuyetDinhXuPhat->push($value->so_quyet_dinh . "(" . $value->thoi_gian_ban_hanh . ")");
                        $danhSachViPhamTonTai->push($value->ghi_chu);
                        $value->xuPhatChinh->each(function ($value, $key) use ($danhSachXuPhatChinh) {
                            $danhSachXuPhatChinh->push($value);
                        });
                        $value->xuPhatBoSung->each(function ($value, $key) use ($danhSachXuPhatBoXung) {
                            $danhSachXuPhatBoXung->push($value);
                        });
                        $value->bienPhapKhacPhucHauQua->each(function ($value, $key) use ($danhSachBienPhapKhacPhucHauQua) {
                            $danhSachBienPhapKhacPhucHauQua->push($value);
                        });
                    });
                    $this->setValue($sheet, 'I' . strval($key + 4), $danhSachQuyetDinhXuPhat->implode("\n"));
                    $this->setValue($sheet, 'J' . strval($key + 4), $danhSachXuPhatChinh->sum('so_tien_xu_phat_chinh'));
                    $this->setValue($sheet, 'K' . strval($key + 4), $danhSachXuPhatBoXung->implode('noi_dung_xu_phat_bo_sung', "\n"));
                    $this->setValue($sheet, 'L' . strval($key + 4), $danhSachBienPhapKhacPhucHauQua->implode('noi_dung_bien_phap_khac_phuc_hau_qua', "\n"));

                    $this->setValue($sheet, 'M' . strval($key + 4), $item->mo_ta);
                    $this->setValue($sheet, 'N' . strval($key + 4), $danhSachXuPhatChinh->implode('noi_dung_vi_pham', "\n"));
                    $this->setValue($sheet, 'O' . strval($key + 4), $danhSachViPhamTonTai->implode("\n"));
                });
            });
            $doc->sheet('Nhóm hành vi vi phạm', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 5), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 5), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 5), $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"));
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 5), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));
                    $co_dtm_dean_khbvmt = '-';
                    $co_giay_phep_xa_thai = '-';
                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use (&$co_dtm_dean_khbvmt, &$co_giay_phep_xa_thai) {
                        $count_dtm_dean_khbvmt = $value->ketQuaThanhTraThuTucHanhChinhs->filter(function ($value, $key) {
                            return ($value->loaiThuTuc->ma == 'dtm' || $value->loaiThuTuc->ma == "dabvmt" || $value->loaiThuTuc->ma == "pabvmt");
                        })->count();
                        if ($count_dtm_dean_khbvmt > 0) {
                            $co_dtm_dean_khbvmt = '1';
                        }
                        $count_giay_phep_xa_thai = $value->ketQuaThanhTraThuTucHanhChinhs->filter(function ($value, $key) {
                            return ($value->loaiThuTuc->ma == "gpxt");
                        })->count();
                        if ($count_giay_phep_xa_thai > 0) {
                            $co_giay_phep_xa_thai = '1';
                        }
                    });

                    $this->setValue($sheet, 'E' . strval($key + 5), $co_dtm_dean_khbvmt);

                    $count_dtmdakhbvmt_thuc_hien_khong_dung_noi_dung = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->dtmdakhbvmt_thuc_hien_khong_dung_noi_dung == true);
                    })->count();
                    if ($count_dtmdakhbvmt_thuc_hien_khong_dung_noi_dung > 0) {
                        $this->setValue($sheet, 'F' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'F' . strval($key + 5), '-');
                    }
                    $count_dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung == true);
                    })->count();
                    if ($count_dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung > 0) {
                        $this->setValue($sheet, 'G' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'G' . strval($key + 5), '-');
                    }

                    $count_co_ket_hoach_quan_ly_moi_truong = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->co_ket_hoach_quan_ly_moi_truong == true);
                    })->count();
                    if ($count_co_ket_hoach_quan_ly_moi_truong > 0) {
                        $this->setValue($sheet, 'H' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'H' . strval($key + 5), '-');
                    }

                    $count_congtrinhBVMT_khong_xay_lap = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->khong_xay_lap == true);
                    })->count();
                    if ($count_congtrinhBVMT_khong_xay_lap > 0) {
                        $this->setValue($sheet, 'I' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'I' . strval($key + 5), '-');
                    }

                    $count_congtrinhBVMT_xay_lap_khong_dung = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->xay_lap_khong_dung == true);
                    })->count();
                    if ($count_congtrinhBVMT_xay_lap_khong_dung > 0) {
                        $this->setValue($sheet, 'J' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'J' . strval($key + 5), '-');
                    }

                    $count_congtrinhBVMT_khong_co_giay_xac_nhan_hoan_thanh = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->khong_co_giay_xac_nhan_hoan_thanh == true);
                    })->count();
                    if ($count_congtrinhBVMT_khong_co_giay_xac_nhan_hoan_thanh > 0) {
                        $this->setValue($sheet, 'K' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'K' . strval($key + 5), '-');
                    }
                    $count_congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->van_hanh_khong_dung_khong_thuong_xuyen == true);
                    })->count();
                    if ($count_congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen > 0) {
                        $this->setValue($sheet, 'L' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'L' . strval($key + 5), '-');
                    }

                    $count_gsmt_thuc_hien = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->gsmt_thuc_hien == true);
                    })->count();
                    if ($count_gsmt_thuc_hien > 0) {
                        $this->setValue($sheet, 'M' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'M' . strval($key + 5), '-');
                    }

                    $count_gsmt_khong_thuc_hien = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->gsmt_khong_thuc_hien == true);
                    })->count();
                    if ($count_gsmt_khong_thuc_hien > 0) {
                        $this->setValue($sheet, 'N' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'N' . strval($key + 5), '-');
                    }

                    $count_gsmt_thuc_hien_khong_dung_khong_du = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->gsmt_thuc_hien_khong_dung_khong_du == true);
                    })->count();
                    if ($count_gsmt_thuc_hien_khong_dung_khong_du > 0) {
                        $this->setValue($sheet, 'O' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'O' . strval($key + 5), '-');
                    }

                    $this->setValue($sheet, 'P' . strval($key + 5), $co_giay_phep_xa_thai);

                    $count_khong_thuc_hien_giay_xac_nhan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->khong_thuc_hien_giay_xac_nhan == true);
                    })->count();
                    if ($count_khong_thuc_hien_giay_xac_nhan > 0) {
                        $this->setValue($sheet, 'Q' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'Q' . strval($key + 5), '-');
                    }

                    $count_thuc_hien_sai_giay_xac_nhan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->thuc_hien_sai_giay_xac_nhan == true);
                    })->count();
                    if ($count_thuc_hien_sai_giay_xac_nhan > 0) {
                        $this->setValue($sheet, 'R' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'R' . strval($key + 5), '-');
                    }

                    $count_nuoc_thai_vuot = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->nuoc_thai_vuot == true);
                    })->count();
                    if ($count_nuoc_thai_vuot > 0) {
                        $this->setValue($sheet, 'S' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'S' . strval($key + 5), '-');
                    }

                    $count_co_he_thong_thu_gom_nuoc_thai_rieng_biet = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->co_he_thong_thu_gom_nuoc_thai_rieng_biet == true);
                    })->count();
                    if ($count_co_he_thong_thu_gom_nuoc_thai_rieng_biet > 0) {
                        $this->setValue($sheet, 'T' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'T' . strval($key + 5), '-');
                    }

                    if ($item->nhomHanhViViPhams->count() > 0) {
                        $firstNhomHanhViViPham = $item->nhomHanhViViPhams[0];
                        if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'] == 0) {
                            if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'] != 0) {
                                $this->setValue($sheet, 'U' . strval($key + 5), $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi']);
                            }
                        } else {
                            if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'] == 0) {
                                $this->setValue($sheet, 'U' . strval($key + 5), $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu']);
                            } else {
                                $this->setValue($sheet, 'U' . strval($key + 5), $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'] . '-' . $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi']);
                            }
                        }
                    }

                    $count_co_bien_phap_xu_ly_khi_thai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->co_bien_phap_xu_ly_khi_thai == true);
                    })->count();
                    if ($count_co_bien_phap_xu_ly_khi_thai > 0) {
                        $this->setValue($sheet, 'V' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'V' . strval($key + 5), '-');
                    }

                    if ($item->nhomHanhViViPhams->count() > 0) {
                        $firstNhomHanhViViPham = $item->nhomHanhViViPhams[0];
                        if ($firstNhomHanhViViPham['khi_thai_vuot_qcvn_tu'] == 0) {
                            if ($firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi'] != 0) {
                                $this->setValue($sheet, 'W' . strval($key + 5), $firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi']);
                            }
                        } else {
                            if ($firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi'] == 0) {
                                $this->setValue($sheet, 'W' . strval($key + 5), $firstNhomHanhViViPham['khi_thai_vuot_qcvn_tu']);
                            } else {
                                $this->setValue($sheet, 'W' . strval($key + 5), $firstNhomHanhViViPham['khi_thai_vuot_qcvn_tu'] . '-' . $firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi']);
                            }
                        }
                    }

                    $count_ctrsh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->trsh_co_thu_gom == true);
                    })->count();
                    if ($count_ctrsh_co_thu_gom > 0) {
                        $this->setValue($sheet, 'X' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'X' . strval($key + 5), '-');
                    }
                    $count_ctrsh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrsh_co_phan_loai == true);
                    })->count();
                    if ($count_ctrsh_co_phan_loai > 0) {
                        $this->setValue($sheet, 'Y' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'Y' . strval($key + 5), '-');
                    }
                    $count_ctrsh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrsh_co_luu_tru == true);
                    })->count();
                    if ($count_ctrsh_co_luu_tru > 0) {
                        $this->setValue($sheet, 'Z' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'Z' . strval($key + 5), '-');
                    }
                    $count_ctrsh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrsh_co_chuyen_giao == true);
                    })->count();
                    if ($count_ctrsh_co_chuyen_giao > 0) {
                        $this->setValue($sheet, 'AA' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AA' . strval($key + 5), '-');
                    }

                    $count_ctrcn_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_thu_gom == true);
                    })->count();
                    if ($count_ctrcn_co_thu_gom > 0) {
                        $this->setValue($sheet, 'AB' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AB' . strval($key + 5), '-');
                    }

                    $count_ctrcn_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_phan_loai == true);
                    })->count();
                    if ($count_ctrcn_co_phan_loai > 0) {
                        $this->setValue($sheet, 'AC' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AC' . strval($key + 5), '-');
                    }

                    $count_ctrcn_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_luu_tru == true);
                    })->count();
                    if ($count_ctrcn_co_luu_tru > 0) {
                        $this->setValue($sheet, 'AD' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AD' . strval($key + 5), '-');
                    }

                    $count_ctrcn_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_chuyen_giao == true);
                    })->count();
                    if ($count_ctrcn_co_chuyen_giao > 0) {
                        $this->setValue($sheet, 'AE' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AE' . strval($key + 5), '-');
                    }

                    // ctnh
                    $count_ctrnh_vi_pham_chung_tu = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_vi_pham_chung_tu == true);
                    })->count();
                    if ($count_ctrnh_vi_pham_chung_tu > 0) {
                        $this->setValue($sheet, 'AF' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AF' . strval($key + 5), '-');
                    }
                    $count_ctrnh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_thu_gom == true);
                    })->count();
                    if ($count_ctrnh_co_thu_gom > 0) {
                        $this->setValue($sheet, 'AG' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AG' . strval($key + 5), '-');
                    }
                    $count_ctrnh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_phan_loai == true);
                    })->count();
                    if ($count_ctrnh_co_phan_loai > 0) {
                        $this->setValue($sheet, 'AH' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AH' . strval($key + 5), '-');
                    }
                    $count_ctrnh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_luu_tru == true);
                    })->count();
                    if ($count_ctrnh_co_luu_tru > 0) {
                        $this->setValue($sheet, 'AI' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AI' . strval($key + 5), '-');
                    }
                    $count_ctrnh_co_de_lan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_de_lan == true);
                    })->count();
                    if ($count_ctrnh_co_de_lan > 0) {
                        $this->setValue($sheet, 'AJ' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AJ' . strval($key + 5), '-');
                    }

                    $count_ctrnh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_chuyen_giao == true);
                    })->count();
                    if ($count_ctrnh_co_chuyen_giao > 0) {
                        $this->setValue($sheet, 'AK' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AK' . strval($key + 5), '-');
                    }

                    $count_ctrnh_co_chon_lap = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_chon_lap == true);
                    })->count();
                    if ($count_ctrnh_co_chon_lap > 0) {
                        $this->setValue($sheet, 'AL' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AL' . strval($key + 5), '-');
                    }

                    $count_ctrnh_co_do_thai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_do_thai == true);
                    })->count();
                    if ($count_ctrnh_co_do_thai > 0) {
                        $this->setValue($sheet, 'AM' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AM' . strval($key + 5), '-');
                    }

                    $count_ctrnh_co_giay_phep = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_giay_phep == true);
                    })->count();
                    if ($count_ctrnh_co_giay_phep > 0) {
                        $this->setValue($sheet, 'AN' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AN' . strval($key + 5), '-');
                    }

                    $count_vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong == true);
                    })->count();
                    if ($count_vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong > 0) {
                        $this->setValue($sheet, 'AO' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AO' . strval($key + 5), '-');
                    }

                    $count_vi_pham_cac_quy_dinh_bao_ve_moi_truong = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->vi_pham_cac_quy_dinh_bao_ve_moi_truong == true);
                    })->count();
                    if ($count_vi_pham_cac_quy_dinh_bao_ve_moi_truong > 0) {
                        $this->setValue($sheet, 'AP' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AP' . strval($key + 5), '-');
                    }

                    $count_co_hanh_vi_can_tro_ve_bvmt = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->co_hanh_vi_can_tro_ve_bvmt == true);
                    })->count();
                    if ($count_co_hanh_vi_can_tro_ve_bvmt > 0) {
                        $this->setValue($sheet, 'AQ' . strval($key + 5), '1');
                    } else {
                        $this->setValue($sheet, 'AQ' . strval($key + 5), '-');
                    }

                    $co_dinh_chi = '-';
                    $item->quyetDinhXuPhats->each(function ($value, $key) use (&$co_dinh_chi) {
                        $count = $value->xuPhatBoSung->filter(function ($value, $key) {
                            return ($value->dinh_chi == true);
                        })->count();
                        if ($count > 0) {
                            $co_dinh_chi = '1';
                        }
                    });
                    $this->setValue($sheet, 'AR' . strval($key + 5), $co_dinh_chi);

                    $count_bao_cao_khac_phuc_hau_qua_vi_pham = $item->ketQuaKhacPhucHauQuas->count();
                    $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_khac_phuc = $item->ketQuaKhacPhucHauQuas->filter(function ($value, $key) {
                        return ($value->da_khac_phuc == true);
                    })->count();
                    if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > 0) {
                        if ($count_bao_cao_khac_phuc_hau_qua_vi_pham_da_khac_phuc > 0) {
                            $this->setValue($sheet, 'AS' . strval($key + 5), '1');
                        } else {
                            $this->setValue($sheet, 'AS' . strval($key + 5), '-');
                        }
                        if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_khac_phuc) {
                            $this->setValue($sheet, 'AT' . strval($key + 5), '1');
                        } else {
                            $this->setValue($sheet, 'AT' . strval($key + 5), '-');
                        }
                    } else {
                        $this->setValue($sheet, 'AS' . strval($key + 5), '-');
                        $this->setValue($sheet, 'AT' . strval($key + 5), '-');
                    }

                    $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao = 0;
                    $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao = $item->ketQuaKhacPhucHauQuas->filter(function ($value, $key) {
                        return ($value->da_bao_cao == true);
                    })->count();
                    if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > 0) {
                        if ($count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao > 0) {
                            $this->setValue($sheet, 'AU' . strval($key + 5), '1');
                        } else {
                            $this->setValue($sheet, 'AU' . strval($key + 5), '-');
                        }
                        if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao) {
                            $this->setValue($sheet, 'AV' . strval($key + 5), '1');
                        } else {
                            $this->setValue($sheet, 'AV' . strval($key + 5), '-');
                        }
                    } else {
                        $this->setValue($sheet, 'AU' . strval($key + 5), '-');
                        $this->setValue($sheet, 'AV' . strval($key + 5), '-');
                    }
                });
            });
            $doc->sheet('Thông tin chung', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 4), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 4), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 4), $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"));
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                    $ketQuaThanhTraNuocThais = collect();
                    $ketQuaThanhTraKhiThais = collect();

                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs, $ketQuaThanhTraNuocThais, $ketQuaThanhTraKhiThais) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                        $value->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($ketQuaThanhTraNuocThais) {
                            $ketQuaThanhTraNuocThais->push($value);
                        });
                        $value->ketQuaThanhTraKhiThais->each(function ($value, $key) use ($ketQuaThanhTraKhiThais) {
                            $ketQuaThanhTraKhiThais->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 4), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));
                    $this->setValue($sheet, 'E' . strval($key + 4), $ketQuaThanhTraNuocThais->pluck('luu_luong')->implode("\n"));
                    $this->setValue($sheet, 'F' . strval($key + 4), $ketQuaThanhTraNuocThais->pluck('cong_suat_he_thong_xu_ly')->implode("\n"));
                    $this->setValue($sheet, 'G' . strval($key + 4), $ketQuaThanhTraNuocThais->pluck('nguon_tiep_nhan')->implode("\n"));
                    $this->setValue($sheet, 'H' . strval($key + 4), $ketQuaThanhTraKhiThais->pluck('luu_luong')->implode("\n"));
                    $this->setValue($sheet, 'I' . strval($key + 4), $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")");

                    $danhSachQuyetDinhXuPhat = collect();
                    $danhSachViPhamTonTai = collect();
                    $danhSachXuPhatChinh = collect();
                    $item->quyetDinhXuPhats->each(function ($value, $key) use ($danhSachQuyetDinhXuPhat, $danhSachViPhamTonTai, $danhSachXuPhatChinh) {
                        $danhSachQuyetDinhXuPhat->push($value->so_quyet_dinh . "(" . $value->thoi_gian_ban_hanh . ")");
                        $danhSachViPhamTonTai->push($value->ghi_chu);
                        $value->xuPhatChinh->each(function ($value, $key) use ($danhSachXuPhatChinh) {
                            $danhSachXuPhatChinh->push($value);
                        });
                    });
                    $this->setValue($sheet, 'J' . strval($key + 4), $danhSachQuyetDinhXuPhat->implode("\n"));
                    $this->setValue($sheet, 'K' . strval($key + 4), $danhSachXuPhatChinh->implode('noi_dung_vi_pham', "\n"));
                    $this->setValue($sheet, 'L' . strval($key + 4), $danhSachViPhamTonTai->implode("\n"));

                    $ketQuaKhacPhucHauQuaViPham = collect();
                    $item->ketQuaKhacPhucHauQuas->each(function ($value, $key) use ($ketQuaKhacPhucHauQuaViPham) {
                        $ketQuaKhacPhucHauQuaViPham->push($value);
                    });
                    if (count($ketQuaKhacPhucHauQuaViPham->where('da_nop_phat', true)) > 0) {
                        $this->setValue($sheet, 'M' . strval($key + 4), 'Đã nộp phạt');
                    } else if (count($ketQuaKhacPhucHauQuaViPham->where('nop_mot_phan', true)) > 0) {
                        $this->setValue($sheet, 'M' . strval($key + 4), 'Nộp một phần');
                    }

                    $this->setValue($sheet, 'N' . strval($key + 4), $ketQuaKhacPhucHauQuaViPham->implode('noi_dung_chua_khac_phuc', "\n"));

                    if (count($ketQuaKhacPhucHauQuaViPham->where('nop_mot_phan', true)) > 0) {
                        $this->setValue($sheet, 'O' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'O' . strval($key + 4), '-');
                    }

                    if (count($ketQuaKhacPhucHauQuaViPham->where('da_nop_phat', true)) > 0) {
                        $this->setValue($sheet, 'P' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'P' . strval($key + 4), '-');
                    }
                });
            });
            $doc->sheet('Theo dõi thực hiện KLTT', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 4), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 4), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 4), $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"));
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 4), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));
                    $this->setValue($sheet, 'E' . strval($key + 4), $item->quyetDinhThanhTra->so_quyet_dinh . "(" . $item->quyetDinhThanhTra->ngay_ra_quyet_dinh . ")");
                    $this->setValue($sheet, 'F' . strval($key + 4), $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")");

                    $danhSachQuyetDinhXuPhat = collect();
                    $danhSachHanhViViPham = collect();
                    $danhSachNoiDungViPham = collect();
                    $danhSachViPhamTonTai = collect();
                    $danhSachXuPhatChinh = collect();
                    $danhSachXuPhatBoXung = collect();
                    $danhSachBienPhapKhacPhucHauQua = collect();
                    $item->quyetDinhXuPhats->each(function ($value, $key) use ($danhSachXuPhatBoXung, $danhSachBienPhapKhacPhucHauQua, $danhSachViPhamTonTai, $danhSachQuyetDinhXuPhat, $danhSachHanhViViPham, $danhSachNoiDungViPham, $danhSachXuPhatChinh) {
                        $danhSachQuyetDinhXuPhat->push($value->so_quyet_dinh . "(" . $value->thoi_gian_ban_hanh . ")");
                        $danhSachViPhamTonTai->push($value->ghi_chu);
                        $value->xuPhatChinh->each(function ($value, $key) use ($danhSachXuPhatChinh, $danhSachHanhViViPham, $danhSachNoiDungViPham) {
                            $value->hanhViViPhams->each(function ($value, $key) use ($danhSachHanhViViPham, $danhSachNoiDungViPham) {
                                $danhSachNoiDungViPham->push($value->noi_dung_vi_pham);
                                $tenHanhVi = $value->dieu->ten;
                                if (!empty($value->khoan->ten)) {
                                    $tenHanhVi = $tenHanhVi . ", Khoản: " . $value->khoan->ten;
                                    if (!empty($value->muc->ten)) {
                                        $tenHanhVi = $tenHanhVi . ",Mục: " . $value->muc->ma . " - " . $value->muc->mo_ta;
                                    }
                                }
                                $danhSachHanhViViPham->push($tenHanhVi);
                            });
                            $danhSachXuPhatChinh->push($value);
                        });
                        $value->xuPhatBoSung->each(function ($value, $key) use ($danhSachXuPhatBoXung) {
                            $danhSachXuPhatBoXung->push($value);
                        });
                        $value->bienPhapKhacPhucHauQua->each(function ($value, $key) use ($danhSachBienPhapKhacPhucHauQua) {
                            $danhSachBienPhapKhacPhucHauQua->push($value);
                        });
                    });

                    $this->setValue($sheet, 'G' . strval($key + 4), $danhSachQuyetDinhXuPhat->implode("\n"));
                    $this->setValue($sheet, 'H' . strval($key + 4), $danhSachHanhViViPham->implode("\n"));
                    $this->setValue($sheet, 'I' . strval($key + 4), $danhSachXuPhatChinh->sum('so_tien_xu_phat_chinh'));
                    $this->setValue($sheet, 'J' . strval($key + 4), $danhSachXuPhatBoXung->implode('noi_dung_xu_phat_bo_sung', "\n"));
                    $this->setValue($sheet, 'K' . strval($key + 4), $danhSachBienPhapKhacPhucHauQua->implode('noi_dung_bien_phap_khac_phuc_hau_qua', "\n"));

                    $ketQuaKhacPhucHauQuaViPham = collect();
                    $ketQuaKhacPhucHauQuaViPhamDaBaoCao = collect();
                    $item->ketQuaKhacPhucHauQuas->each(function ($value, $key) use ($ketQuaKhacPhucHauQuaViPham, $ketQuaKhacPhucHauQuaViPhamDaBaoCao) {
                        $ketQuaKhacPhucHauQuaViPham->push($value);
                        if ($value->da_bao_cao) {
                            $ketQuaKhacPhucHauQuaViPhamDaBaoCao->push($value->so_hieu_bao_cao . "(" . $value->ngay_ban_hanh_bao_cao . ")\n" . $value->tinh_trang_bao_cao);
                        }
                    });
                    if (count($ketQuaKhacPhucHauQuaViPham->where('da_nop_phat', true)) > 0) {
                        $this->setValue($sheet, 'L' . strval($key + 4), 'Đã nộp phạt');
                    } else if (count($ketQuaKhacPhucHauQuaViPham->where('nop_mot_phan', true)) > 0) {
                        $this->setValue($sheet, 'L' . strval($key + 4), 'Nộp một phần');
                    }
                    if (count($ketQuaKhacPhucHauQuaViPhamDaBaoCao) > 0) {
                        $this->setValue($sheet, 'M' . strval($key + 4), $ketQuaKhacPhucHauQuaViPhamDaBaoCao->implode("\n"));
                    }
                    $this->setValue($sheet, 'N' . strval($key + 4), $ketQuaKhacPhucHauQuaViPham->implode('noi_dung_chua_khac_phuc', "\n"));
                    $this->setValue($sheet, 'O' . strval($key + 4), $ketQuaKhacPhucHauQuaViPham->implode('noi_dung_da_khac_phuc', "\n"));
                });
            });
            $doc->sheet('Tổng hợp các nhóm vi phạm', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 4), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 4), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 4), $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"));
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                    $ketQuaThanhTraNuocThais = collect();
                    $ketQuaThanhTraKhiThais = collect();

                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs, $ketQuaThanhTraNuocThais, $ketQuaThanhTraKhiThais) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                        $value->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($ketQuaThanhTraNuocThais) {
                            $ketQuaThanhTraNuocThais->push($value);
                        });
                        $value->ketQuaThanhTraKhiThais->each(function ($value, $key) use ($ketQuaThanhTraKhiThais) {
                            $ketQuaThanhTraKhiThais->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 4), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));
                    $this->setValue($sheet, 'E' . strval($key + 4), $ketQuaThanhTraNuocThais->pluck('luu_luong')->implode("\n"));
                    $this->setValue($sheet, 'F' . strval($key + 4), $ketQuaThanhTraKhiThais->pluck('luu_luong')->implode("\n"));

                    if ($item->nhomHanhViViPhams->count() > 0) {
                        $nhomHanhViViPham = $item->nhomHanhViViPhams[0];
                        $dmt_khbvmt_da = collect();
                        if ($nhomHanhViViPham->dtmdakhbvmt_thuc_hien_khong_dung_noi_dung) {
                            $dmt_khbvmt_da->push('Thực hiện không đúng nội dung');
                        }
                        if ($nhomHanhViViPham->dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung) {
                            $dmt_khbvmt_da->push('Không thực hiện một trong các nội dung');
                        }
                        $this->setValue($sheet, 'G' . strval($key + 4), $dmt_khbvmt_da->implode("\n"));

                        if ($nhomHanhViViPham->khong_co_giay_xac_nhan_hoan_thanh) {
                            $this->setValue($sheet, 'H' . strval($key + 4), 'Không có giấy xác nhận hoàn thành');
                        }

                        $gsmt = collect();
                        if ($nhomHanhViViPham->gsmt_khong_thuc_hien) {
                            $gsmt->push('Không thực hiện');
                        }
                        if ($nhomHanhViViPham->gsmt_thuc_hien_khong_dung_khong_du) {
                            $gsmt->push('Thực hiện không đúng, không đủ');
                        }
                        $this->setValue($sheet, 'I' . strval($key + 4), $gsmt->implode("\n"));

                        if ($nhomHanhViViPham->nuoc_thai_vuot == true) {
                            $this->setValue($sheet, 'K' . strval($key + 4), '1');
                        } else {
                            $this->setValue($sheet, 'K' . strval($key + 4), '-');
                        }

                        if ($nhomHanhViViPham->khi_thai_vuot_qcvn_toi > 0) {
                            $this->setValue($sheet, 'L' . strval($key + 4), '1');
                        } else {
                            $this->setValue($sheet, 'L' . strval($key + 4), '-');
                        }

                        if ($nhomHanhViViPham->khong_xay_lap == true) {
                            $this->setValue($sheet, 'M' . strval($key + 4), '1');
                        } else {
                            $this->setValue($sheet, 'M' . strval($key + 4), '-');
                        }

                        if ($nhomHanhViViPham->ctrnh_vi_pham_chung_tu == true) {
                            $this->setValue($sheet, 'N' . strval($key + 4), '1');
                        } else {
                            $this->setValue($sheet, 'N' . strval($key + 4), '-');
                        }

                        if ($nhomHanhViViPham->ctrsh_co_thu_gom == true || $nhomHanhViViPham->ctrsh_co_phan_loai == true || $nhomHanhViViPham->ctrsh_co_luu_tru == true || $nhomHanhViViPham->ctrsh_co_chuyen_giao == true) {
                            $this->setValue($sheet, 'O' . strval($key + 4), '-');
                        } else {
                            $this->setValue($sheet, 'O' . strval($key + 4), '1');
                        }
                    }
                });
            });
            $doc->sheet('Xả thải', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 3), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 3), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 3), $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"));
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                    $ketQuaThanhTraNuocThais = collect();
                    $ketQuaThanhTraKhiThais = collect();

                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs, $ketQuaThanhTraNuocThais, $ketQuaThanhTraKhiThais) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                        $value->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($ketQuaThanhTraNuocThais) {
                            $ketQuaThanhTraNuocThais->push($value);
                        });
                        $value->ketQuaThanhTraKhiThais->each(function ($value, $key) use ($ketQuaThanhTraKhiThais) {
                            $ketQuaThanhTraKhiThais->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 3), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));
                    $this->setValue($sheet, 'E' . strval($key + 3), $ketQuaThanhTraNuocThais->pluck('luu_luong')->implode("\n"));
                    $this->setValue($sheet, 'F' . strval($key + 3), $ketQuaThanhTraNuocThais->pluck('cong_suat_he_thong_xu_ly')->implode("\n"));
                    $this->setValue($sheet, 'G' . strval($key + 3), $ketQuaThanhTraNuocThais->pluck('nguon_tiep_nhan')->implode("\n"));
                    $this->setValue($sheet, 'H' . strval($key + 3), $ketQuaThanhTraKhiThais->pluck('luu_luong')->implode("\n"));

                    $this->setValue($sheet, 'I' . strval($key + 3), $item->quyetDinhThanhTra->so_quyet_dinh . "(" . $item->quyetDinhThanhTra->ngay_ra_quyet_dinh . ")");
                    $this->setValue($sheet, 'J' . strval($key + 3), $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")");

                    $this->setValue($sheet, 'L' . strval($key + 3), $ketQuaThanhTraNuocThais->pluck('thong_so_nuoc_thai_vuot_chuan')->implode("\n"));
                    if ($item->nhomHanhViViPhams->count() > 0) {
                        $firstNhomHanhViViPham = $item->nhomHanhViViPhams[0];
                        if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'] == 0) {
                            if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'] != 0) {
                                $this->setValue($sheet, 'M' . strval($key + 3), $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi']);
                            }
                        } else {
                            if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'] == 0) {
                                $this->setValue($sheet, 'M' . strval($key + 3), $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu']);
                            } else {
                                $this->setValue($sheet, 'M' . strval($key + 3), $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'] . '-' . $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi']);
                            }
                        }
                    }
                });
            });
            $doc->sheet('CTR', function ($sheet) use ($ketQuaThanhTrasSorted) {
                $ketQuaThanhTrasSorted->each(function ($item, $key) use ($sheet) {
                    $this->setValue($sheet, 'A' . strval($key + 4), $key + 1);
                    $this->setValue($sheet, 'B' . strval($key + 4), $item->organization->ten);
                    $this->setValue($sheet, 'C' . strval($key + 4), $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"));
                    $ketQuaThanhTraLoaiHinhHoatDongs = collect();

                    $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                            $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                        });
                    });
                    $this->setValue($sheet, 'D' . strval($key + 4), $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"));

                    $count_ctrsh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->trsh_co_thu_gom == true);
                    })->count();
                    if ($count_ctrsh_co_thu_gom > 0) {
                        $this->setValue($sheet, 'E' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'E' . strval($key + 4), '-');
                    }
                    $count_ctrsh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrsh_co_phan_loai == true);
                    })->count();
                    if ($count_ctrsh_co_phan_loai > 0) {
                        $this->setValue($sheet, 'F' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'F' . strval($key + 4), '-');
                    }
                    $count_ctrsh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrsh_co_luu_tru == true);
                    })->count();
                    if ($count_ctrsh_co_luu_tru > 0) {
                        $this->setValue($sheet, 'G' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'G' . strval($key + 4), '-');
                    }
                    $count_ctrsh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrsh_co_chuyen_giao == true);
                    })->count();
                    if ($count_ctrsh_co_chuyen_giao > 0) {
                        $this->setValue($sheet, 'H' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'H' . strval($key + 4), '-');
                    }

                    $count_ctrcn_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_thu_gom == true);
                    })->count();
                    if ($count_ctrcn_co_thu_gom > 0) {
                        $this->setValue($sheet, 'I' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'I' . strval($key + 4), '-');
                    }

                    $count_ctrcn_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_phan_loai == true);
                    })->count();
                    if ($count_ctrcn_co_phan_loai > 0) {
                        $this->setValue($sheet, 'J' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'J' . strval($key + 4), '-');
                    }

                    $count_ctrcn_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_luu_tru == true);
                    })->count();
                    if ($count_ctrcn_co_luu_tru > 0) {
                        $this->setValue($sheet, 'K' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'K' . strval($key + 4), '-');
                    }

                    $count_ctrcn_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrcn_co_chuyen_giao == true);
                    })->count();
                    if ($count_ctrcn_co_chuyen_giao > 0) {
                        $this->setValue($sheet, 'L' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'L' . strval($key + 4), '-');
                    }

                    // ctnh
                    $count_ctrnh_vi_pham_chung_tu = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_vi_pham_chung_tu == true);
                    })->count();
                    if ($count_ctrnh_vi_pham_chung_tu > 0) {
                        $this->setValue($sheet, 'M' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'M' . strval($key + 4), '-');
                    }
                    $count_ctrnh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_thu_gom == true);
                    })->count();
                    if ($count_ctrnh_co_thu_gom > 0) {
                        $this->setValue($sheet, 'N' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'N' . strval($key + 4), '-');
                    }
                    $count_ctrnh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_phan_loai == true);
                    })->count();
                    if ($count_ctrnh_co_phan_loai > 0) {
                        $this->setValue($sheet, 'O' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'O' . strval($key + 4), '-');
                    }
                    $count_ctrnh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_luu_tru == true);
                    })->count();
                    if ($count_ctrnh_co_luu_tru > 0) {
                        $this->setValue($sheet, 'P' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'P' . strval($key + 4), '-');
                    }
                    $count_ctrnh_co_de_lan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_de_lan == true);
                    })->count();
                    if ($count_ctrnh_co_de_lan > 0) {
                        $this->setValue($sheet, 'Q' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'Q' . strval($key + 4), '-');
                    }

                    $count_ctrnh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_chuyen_giao == true);
                    })->count();
                    if ($count_ctrnh_co_chuyen_giao > 0) {
                        $this->setValue($sheet, 'R' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'R' . strval($key + 4), '-');
                    }

                    $count_ctrnh_co_chon_lap = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_chon_lap == true);
                    })->count();
                    if ($count_ctrnh_co_chon_lap > 0) {
                        $this->setValue($sheet, 'S' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'S' . strval($key + 4), '-');
                    }

                    $count_ctrnh_co_do_thai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_do_thai == true);
                    })->count();
                    if ($count_ctrnh_co_do_thai > 0) {
                        $this->setValue($sheet, 'T' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'T' . strval($key + 4), '-');
                    }

                    $count_ctrnh_co_giay_phep = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                        return ($value->ctrnh_co_giay_phep == true);
                    })->count();
                    if ($count_ctrnh_co_giay_phep > 0) {
                        $this->setValue($sheet, 'U' . strval($key + 4), '1');
                    } else {
                        $this->setValue($sheet, 'U' . strval($key + 4), '-');
                    }
                });
            });
        })->download('xlsx');
    }

    private function filterKetQuaKhacPhuc($info, $data)
    {
        $theoDoiTT = clone $data;
        if (!empty($info['ketquakhacphuchauqua_da_nop_phat']) && $info['ketquakhacphuchauqua_da_nop_phat'] == true) {
            $theoDoiTT =  $data->filter(function ($item) {
                $check = true;
                if (count($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas) > 0) {
                    foreach ($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas as $value) {
                        $check = $check && $value->da_nop_phat;
                    }
                    return $check;
                }
                return false;
            });
        }
        if (!empty($info['ketquakhacphuchauqua_nop_mot_phan']) && $info['ketquakhacphuchauqua_nop_mot_phan'] == true) {
            $theoDoiTT =  $theoDoiTT->filter(function ($item) {
                $check = true;
                if (count($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas) > 0) {
                    foreach ($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas as $value) {
                        $check = $check && $value->nop_mot_phan;
                    }
                    return $check;
                }
                return false;
            });
        }
        if (!empty($info['ketquakhacphuchauqua_da_khac_phuc']) && $info['ketquakhacphuchauqua_da_khac_phuc'] == true) {
            $theoDoiTT =  $theoDoiTT->filter(function ($item) {
                $check = true;
                if (count($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas) > 0) {
                    foreach ($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas as $value) {
                        $check = $check && $value->noi_dung_da_khac_phuc;
                    }
                    return $check;
                }
                return false;
            });
        }
        if (!empty($info['ketquakhacphuchauqua_da_bao_cao']) && $info['ketquakhacphuchauqua_da_bao_cao'] == true) {
            $theoDoiTT =  $theoDoiTT->filter(function ($item) {
                $check = true;
                if (count($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas) > 0) {
                    foreach ($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas as $value) {
                        $check = $check && $value->da_bao_cao;
                    }
                    return $check;
                }
                return false;
            });
        }
        return $theoDoiTT;
    }
    private function getQueryOrganization($info, &$query, $tinhThanhs)
    {
        // tra cuu nang cao
        // if (!empty($info['search_loai_van_ban'])) {
        //     $search_loai_van_ban = $info['search_loai_van_ban'];
        //     $array = explode(' ', $search_loai_van_ban);
        //     foreach ($array as $item) {
        //         $item = json_decode($item);
        //         $item = (array)$item;
        //         $key = array_keys($item)[0];
        //         $query->whereHas('coSoSanXuats', function ($query) use ($key, $item) {
        //             $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($key, $item) {
        //                 $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($key, $item) {
        //                     $query->whereHas('loaiThuTuc', function ($query) use ($key) {
        //                         $query->where('id', $key);
        //                     });
        //                     if ($item[$key]) {
        //                         $query->whereIn('co_quan_phe_duyet_id', explode(',', $item[$key]));
        //                     }
        //                 });
        //             });
        //         });
        //     }
        // };
        if (!empty($info['search_mien'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info, $tinhThanhs) {
                $query->whereIn('tinh_thanh_id', $tinhThanhs->whereIn('mien_id', explode(',', $info['search_mien']))->pluck('id'));
            });
        }
        if (!empty($info['search_ten'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->where('ten', 'iLike', '%' . $info['search_ten'] . '%');
            });
        }

        if (!empty($info['search_tinh'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereIn('tinh_thanh_id', explode(',', $info['search_tinh']));
            });
        }

        if (!empty($info['search_luu_vuc_song'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info, $tinhThanhs) {
                $tinhThanhIds = TinhThanhLuuVucSong::whereIn('luu_vuc_song_id',  explode(',', $info['search_luu_vuc_song']))->pluck('tinh_thanh_id');
                $query->whereIn('tinh_thanh_id', $tinhThanhIds);
            });
        }

        if (!empty($info['search_vungkinhte'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereIn('vung_kinh_te_trong_diem_id', explode(',', $info['search_vungkinhte']));
            });
        }
        // co quan phe duyet
        if (!empty($info['search_co_quan_phe_duyet_dtm'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                    $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($info) {
                        $query->whereHas('loaiThuTuc', function ($query) {
                            $query->where('ma', 'dtm');
                        });
                        $query->whereIn('co_quan_phe_duyet_id', explode(',', $info['search_co_quan_phe_duyet_dtm']));
                    });
                });
            });
        }
        if (!empty($info['search_co_quan_phe_duyet_dabvmt'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                    $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($info) {
                        $query->whereHas('loaiThuTuc', function ($query) {
                            $query->where('ma', 'dabvmt');
                        });
                        $query->whereIn('co_quan_phe_duyet_id', explode(',', $info['search_co_quan_phe_duyet_dabvmt']));
                    });
                });
            });
        }

        if (!empty($info['search_co_quan_phe_duyet_gxnctbvmt'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                    $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($info) {
                        $query->whereHas('loaiThuTuc', function ($query) {
                            $query->where('ma', 'gxnctbvmt');
                        });
                        $query->whereIn('co_quan_phe_duyet_id', explode(',', $info['search_co_quan_phe_duyet_gxnctbvmt']));
                    });
                });
            });
        }

        if (!empty($info['search_dieu'])) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($info) {
                $query->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                    $query->whereHas('xuPhatChinh', function ($query) use ($info) {
                        $query->whereHas('hanhViViPhams', function ($query) use ($info) {
                            $query->where('dieu_id', $info['search_dieu']);
                        });
                    });
                });
            });
        }

        if (!empty($info['search_khoan'])) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($info) {
                $query->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                    $query->whereHas('xuPhatChinh', function ($query) use ($info) {
                        $query->whereHas('hanhViViPhams', function ($query) use ($info) {
                            $query->where('khoan_id', $info['search_khoan']);
                        });
                    });
                });
            });
        }
        if (!empty($info['search_muc'])) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($info) {
                $query->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                    $query->whereHas('xuPhatChinh', function ($query) use ($info) {
                        $query->whereHas('hanhViViPhams', function ($query) use ($info) {
                            $query->where('muc_id', $info['search_muc']);
                        });
                    });
                });
            });
        }

        // Quyết định thành lập đoàn
        if (!empty($info['search_doan_thanh_tra'])) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($info) {
                $query->whereIn('quyet_dinh_thanh_tra_id', explode(',', $info['search_doan_thanh_tra']));
            });
        }

        // Loại hình hoạt động
        if (!empty($info['search_loai_hinh_co_so'])) {
            // $query->whereHas('coSoSanXuats', function ($query) use ($info) {
            //     $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
            //         $query->whereHas('ketQuaThanhTraChungLoaiHinhHoatDongs', function ($query) use ($info) {
            //             $query->whereIn('loai_hinh_co_so_id', explode(',', $info['search_loai_hinh_co_so']));
            //         });
            //     });
            // });
            $coSoLoaiNganhNgheIds = CoSoSanXuatLoaiNganhNghe::whereIn('loai_nganh_nghe_id',  explode(',', $info['search_loai_hinh_co_so']))->pluck('co_so_id')->toArray();
            $query->whereHas('coSoSanXuats', function ($query) use ($coSoLoaiNganhNgheIds) {
                $query->whereIn('id',  $coSoLoaiNganhNgheIds);
            });
        }
        if (!empty($info['search_loai_hinh_o_nhiem'])) {
            $coSoLoaiHinhSanXuatIds = CoSoSanXuatLoaiHinhONhiem::whereIn('loai_hinh_o_nhiem_id',  explode(',', $info['search_loai_hinh_o_nhiem']))->pluck('co_so_san_xuat_id')->toArray();
            $query->whereHas('coSoSanXuats', function ($query) use ($coSoLoaiHinhSanXuatIds) {
                $query->whereIn('id',  $coSoLoaiHinhSanXuatIds);
            });
        }

        // khu cong nghiep
        if (!empty($info['search_khu_cong_nghiep'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereIn('loai_khu_cong_nghiep', explode(',', $info['search_khu_cong_nghiep']));
            });
        }

        if (!empty($info['search_bien_phap_xu_phat_bo_xung'])) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($info) {
                $query->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                    $query->whereHas('xuPhatBoSung', function ($query) use ($info) {
                        $query->whereHas('chiTietXuPhatBoSungs', function ($query) use ($info) {
                            $query->whereIn('loai_xu_phat_bo_sung_id', explode(',', $info['search_bien_phap_xu_phat_bo_xung']));
                        });
                    });
                });
            });
        }
        // bien phap xu phat bo sung
        if (!empty($info['bienphapxuphatboxung_dinh_chi']) && $info['bienphapxuphatboxung_dinh_chi'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('quyetDinhXuPhats', function ($query) {
                    $query->whereHas('xuPhatBoSung', function ($query) {
                        $query->where('dinh_chi', true);
                    });
                });
            });
        }

        // ket qua khac phuc hau qua vi pham
        if (!empty($info['ketquakhacphuchauqua_nop_mot_phan']) && $info['ketquakhacphuchauqua_nop_mot_phan'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                    $query->where('nop_mot_phan', true);
                });
            });
        }

        if (!empty($info['ketquakhacphuchauqua_da_nop_phat']) && $info['ketquakhacphuchauqua_da_nop_phat'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                    $query->where('da_nop_phat', true);
                });
            });
        }

        if (!empty($info['ketquakhacphuchauqua_da_khac_phuc']) && $info['ketquakhacphuchauqua_da_khac_phuc'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                    $query->where('da_khac_phuc', true);
                });
            });
        }

        if (!empty($info['ketquakhacphuchauqua_da_bao_cao']) && $info['ketquakhacphuchauqua_da_bao_cao'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                    $query->where('da_bao_cao', true);
                });
            });
        }
        // vi pham
        if (!empty($info['vi_pham']) && $info['vi_pham'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('vi_pham', true);
                });
            });
        }

        // nhom vi pham thu tuc hanh chinh
        if (!empty($info['vi_pham_thu_tuc_hanh_chinh']) && $info['vi_pham_thu_tuc_hanh_chinh'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('vi_pham_thu_tuc_hanh_chinh', true);
                });
            });
        }

        if (!empty($info['dtm_dean_khbvmt_filter']) && $info['dtm_dean_khbvmt_filter'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) {
                    $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) {
                        $query->whereHas('loaiThuTuc', function ($query) {
                            $query->whereIn('ma', ['dtm', 'dabvmt', 'pabvmt']);
                        });
                    });
                });
            });
        }

        if (!empty($info['dtmdakhbvmt_thuc_hien_khong_dung_noi_dung']) && $info['dtmdakhbvmt_thuc_hien_khong_dung_noi_dung'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('dtmdakhbvmt_thuc_hien_khong_dung_noi_dung', true);
                });
            });
        }
        if (!empty($info['dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung']) && $info['dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung', true);
                });
            });
        }

        if (!empty($info['khong_co_giay_phep_xa_thai']) && $info['khong_co_giay_phep_xa_thai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('khong_co_giay_phep_xa_thai', true);
                });
            });
        }
        // khong co ke hoach quan ly moi truong
        if (!empty($info['co_ket_hoach_quan_ly_moi_truong']) && $info['co_ket_hoach_quan_ly_moi_truong'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('co_ket_hoach_quan_ly_moi_truong', true);
                });
            });
        }
        // giay xac nhan hoan thanh cong trinh bao ve moi truong
        if (!empty($info['khong_thuc_hien_giay_xac_nhan']) && $info['khong_thuc_hien_giay_xac_nhan'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('khong_thuc_hien_giay_xac_nhan', true);
                });
            });
        }

        if (!empty($info['thuc_hien_sai_giay_xac_nhan']) && $info['thuc_hien_sai_giay_xac_nhan'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('thuc_hien_sai_giay_xac_nhan', true);
                });
            });
        }
        // cong trinh bao ve moi truong
        if (!empty($info['congtrinhBVMT_khong_xay_lap']) && $info['congtrinhBVMT_khong_xay_lap'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('khong_xay_lap', true);
                });
            });
        }
        if (!empty($info['congtrinhBVMT_xay_lap_khong_dung']) && $info['congtrinhBVMT_xay_lap_khong_dung'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('xay_lap_khong_dung', true);
                });
            });
        }
        if (!empty($info['congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen']) && $info['congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('van_hanh_khong_dung_khong_thuong_xuyen', true);
                });
            });
        }
        if (!empty($info['khong_co_giay_xac_nhan_hoan_thanh']) && $info['khong_co_giay_xac_nhan_hoan_thanh'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('khong_co_giay_xac_nhan_hoan_thanh', true);
                });
            });
        }

        // giam sat moi truong
        if (!empty($info['gsmt_thuc_hien']) && $info['gsmt_thuc_hien'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('gsmt_thuc_hien', true);
                });
            });
        }
        if (!empty($info['gsmt_khong_thuc_hien']) && $info['gsmt_khong_thuc_hien'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('gsmt_khong_thuc_hien', true);
                });
            });
        }
        if (!empty($info['gsmt_thuc_hien_khong_dung_khong_du']) && $info['gsmt_thuc_hien_khong_dung_khong_du'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('gsmt_thuc_hien_khong_dung_khong_du', true);
                });
            });
        }
        // nhom vi pham
        // tong hop vi pham xa thai
        if (!empty($info['vi_pham_nhom_hanh_vi_xa_thai']) && $info['vi_pham_nhom_hanh_vi_xa_thai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('vi_pham_xa_thai', true);
                });
            });
        }
        // nuoc thai

        if (!empty($info['co_he_thong_thu_gom_nuoc_thai_rieng_biet']) && $info['co_he_thong_thu_gom_nuoc_thai_rieng_biet'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('co_he_thong_thu_gom_nuoc_thai_rieng_biet', true);
                });
            });
        }
        if (!empty($info['nuoc_thai_vuot']) && $info['nuoc_thai_vuot'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('nuoc_thai_vuot', true);
                });
            });
        }

        if (isset($info['search_nuoc_thai'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                    $query->whereHas('ketQuaThanhTraNuocThais', function ($query) use ($info) {
                        $query->where('luu_luong', '>=', $info['search_nuoc_thai']);
                        if (isset($info['search_nuoc_thai_max'])) {
                            $query->where('luu_luong', '<=', $info['search_nuoc_thai_max']);
                        }
                    });
                });
            });
        }

        if (!empty($info['nuoc_thai_vi_pham_xa_thai'])) {
            foreach ($info['nuoc_thai_vi_pham_xa_thai'] as $item) {
                $query->whereHas('ketQuaThanhTras', function ($query) use ($item) {
                    $query->whereHas('nhomHanhViViPhams', function ($query) use ($item) {
                        $query->whereHas('viPhamXaThaiNuocThais', function ($query) use ($item) {
                            if (!empty($item['thong_so_id'])) {
                                $query->where('thong_so_id', $item['thong_so_id']);
                            }
                            if (!empty($item['so_lan_vuot_tu'])) {
                                $query->where('so_lan_vuot', '>=', $item['so_lan_vuot_tu']);
                            }
                            if (!empty($item['so_lan_vuot_den'])) {
                                $query->where('so_lan_vuot', '<=', $item['so_lan_vuot_den']);
                            }
                        });
                    });
                });
            }
        }
        // khi thai
        if (!empty($info['co_bien_phap_xu_ly_khi_thai']) && $info['co_bien_phap_xu_ly_khi_thai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('co_bien_phap_xu_ly_khi_thai', true);
                });
            });
        }


        if (!empty($info['khi_thai_vuot']) && $info['khi_thai_vuot'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('khi_thai_vuot', true);
                });
            });
        }


        if (!empty($info['khi_thai_vi_pham_xa_thai'])) {
            foreach ($info['khi_thai_vi_pham_xa_thai'] as $item) {

                $query->whereHas('ketQuaThanhTras', function ($query) use ($item) {
                    $query->whereHas('nhomHanhViViPhams', function ($query) use ($item) {
                        $query->whereHas('viPhamXaThaiKhiThais', function ($query) use ($item) {
                            if (!empty($item['thong_so_id'])) {
                                $query->where('thong_so_id', $item['thong_so_id']);
                            }
                            if (!empty($item['so_lan_vuot_tu'])) {
                                $query->where('so_lan_vuot', '>=', $item['so_lan_vuot_tu']);
                            }
                            if (!empty($item['so_lan_vuot_den'])) {
                                $query->where('so_lan_vuot', '<=', $item['so_lan_vuot_den']);
                            }
                        });
                    });
                });
            }
        }

        if (isset($info['search_khi_thai'])) {
            $query->whereHas('coSoSanXuats', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                    $query->whereHas('ketQuaThanhTraKhiThais', function ($query) use ($info) {
                        $query->where('luu_luong', '>=', $info['search_khi_thai']);
                        if (isset($info['search_khi_thai_max'])) {
                            $query->where('luu_luong', '<=', $info['search_khi_thai_max']);
                        }
                    });
                });
            });
        }

        // chat thai ran sinh hoat
        if (!empty($info['ctrsh_co_thu_gom']) && $info['ctrsh_co_thu_gom'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrsh_co_thu_gom', true);
                });
            });
        }

        if (!empty($info['ctrsh_co_phan_loai']) && $info['ctrsh_co_phan_loai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrsh_co_phan_loai', true);
                });
            });
        }

        if (!empty($info['ctrsh_co_luu_tru']) && $info['ctrsh_co_luu_tru'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrsh_co_luu_tru', true);
                });
            });
        }
        if (!empty($info['ctrsh_co_chuyen_giao']) && $info['ctrsh_co_chuyen_giao'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrsh_co_chuyen_giao', true);
                });
            });
        }
        // chat thai ran cong nghiep
        if (!empty($info['ctrcn_co_thu_gom']) && $info['ctrcn_co_thu_gom'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrcn_co_thu_gom', true);
                });
            });
        }
        if (!empty($info['ctrcn_co_phan_loai']) && $info['ctrcn_co_phan_loai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrcn_co_phan_loai', true);
                });
            });
        }
        if (!empty($info['ctrcn_co_luu_tru']) && $info['ctrcn_co_luu_tru'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrcn_co_luu_tru', true);
                });
            });
        }
        if (!empty($info['ctrcn_co_chuyen_giao']) && $info['ctrcn_co_chuyen_giao'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrcn_co_chuyen_giao', true);
                });
            });
        }

        // ctnh
        if (!empty($info['ctrnh_vi_pham_chung_tu']) && $info['ctrnh_vi_pham_chung_tu'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_vi_pham_chung_tu', true);
                });
            });
        }
        if (!empty($info['ctrnh_co_thu_gom']) && $info['ctrnh_co_thu_gom'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_thu_gom', true);
                });
            });
        }
        if (!empty($info['ctrnh_co_phan_loai']) && $info['ctrnh_co_phan_loai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_phan_loai', true);
                });
            });
        }
        if (!empty($info['ctrnh_co_luu_tru']) && $info['ctrnh_co_luu_tru'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_luu_tru', true);
                });
            });
        }
        if (!empty($info['ctrnh_co_de_lan']) && $info['ctrnh_co_de_lan'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_de_lan', true);
                });
            });
        }

        if (!empty($info['ctrnh_co_chuyen_giao']) && $info['ctrnh_co_chuyen_giao'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_chuyen_giao', true);
                });
            });
        }

        if (!empty($info['ctrnh_co_chon_lap']) && $info['ctrnh_co_chon_lap'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_chon_lap', true);
                });
            });
        }

        if (!empty($info['ctrnh_co_do_thai']) && $info['ctrnh_co_do_thai'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_do_thai', true);
                });
            });
        }

        if (!empty($info['ctrnh_co_giay_phep']) && $info['ctrnh_co_giay_phep'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('ctrnh_co_giay_phep', true);
                });
            });
        }

        // vi pham khac
        if (!empty($info['vi_pham_nhom_hanh_vi_khac']) && $info['vi_pham_nhom_hanh_vi_khac'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('nhom_hanh_vi_khac', true);
                });
            });
        }

        if (!empty($info['vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong']) && $info['vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong', true);
                });
            });
        }
        if (!empty($info['vi_pham_cac_quy_dinh_bao_ve_moi_truong']) && $info['vi_pham_cac_quy_dinh_bao_ve_moi_truong'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('vi_pham_cac_quy_dinh_bao_ve_moi_truong', true);
                });
            });
        }
        if (!empty($info['co_hanh_vi_can_tro_ve_bvmt']) && $info['co_hanh_vi_can_tro_ve_bvmt'] == true) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('nhomHanhViViPhams', function ($query) {
                    $query->where('co_hanh_vi_can_tro_ve_bvmt', true);
                });
            });
        }




        if (!empty($info['search_bien_phap_khac_phuc_vi_pham'])) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($info) {
                $query->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                    $query->whereHas('bienPhapKhacPhucHauQua', function ($query) use ($info) {
                        $query->whereHas('chiTietBienPhapKhacPhucHauQuas', function ($query) use ($info) {
                            $query->whereIn('cac_bien_phap_khac_phuc_hau_qua_id', explode(',', $info['search_bien_phap_khac_phuc_vi_pham']));
                        });
                    });
                });
            });
        }

        // hinh thuc thanh tra
        if (!empty($info['search_dot_xuat']) && !empty($info['search_ke_hoach']) && $info['search_dot_xuat'] && $info['search_ke_hoach'] == 'false') {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('quyetDinhThanhTra', function ($query) {
                    $query->whereHas('cheDoThanhTra', function ($query) {
                        $query->where('ma', 'dot_xuat');
                    });
                });
            });
        }

        if (!empty($info['search_dot_xuat']) && !empty($info['search_ke_hoach']) && $info['search_dot_xuat'] == 'false' && $info['search_ke_hoach']) {
            $query->whereHas('ketQuaThanhTras', function ($query) {
                $query->whereHas('quyetDinhThanhTra', function ($query) {
                    $query->whereHas('cheDoThanhTra', function ($query) {
                        $query->where('ma', 'ke_hoach');
                    });
                });
            });
        }

        return $query;
    }

    private function getQueryQuyetDinhThanhTra(&$query)
    {
        if (!empty($info['search_dot_xuat']) && !empty($info['search_ke_hoach']) && $info['search_dot_xuat'] && $info['search_ke_hoach'] == 'false') {
            $query->whereHas('cheDoThanhTra', function ($query) {
                $query->where('ma', 'dot_xuat');
            });
        }

        if (!empty($info['search_dot_xuat']) && !empty($info['search_ke_hoach']) && $info['search_dot_xuat'] == 'false' && $info['search_ke_hoach']) {
            $query->whereHas('cheDoThanhTra', function ($query) {
                $query->where('ma', 'ke_hoach');
            });
        }

        if (!empty($info['search_doan_thanh_tra'])) {
            $query->whereIn('id', explode(',', $info['search_doan_thanh_tra']));
        }
    }

    private function getQueryKetQuaThanhTra($info, &$queryKetQuaThanhTra)
    {
        // tra cuu nang cao
        if (!empty($info['search_dieu'])) {
            $queryKetQuaThanhTra->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                $query->whereHas('xuPhatChinh', function ($query) use ($info) {
                    $query->whereHas('hanhViViPhams', function ($query) use ($info) {
                        $query->where('dieu_id', $info['search_dieu']);
                    });
                });
            });
        }
        if (!empty($info['search_khoan'])) {
            $queryKetQuaThanhTra->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                $query->whereHas('xuPhatChinh', function ($query) use ($info) {
                    $query->whereHas('hanhViViPhams', function ($query) use ($info) {
                        $query->where('khoan_id', $info['search_khoan']);
                    });
                });
            });
        }

        if (!empty($info['search_muc'])) {
            $queryKetQuaThanhTra->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                $query->whereHas('xuPhatChinh', function ($query) use ($info) {
                    $query->whereHas('hanhViViPhams', function ($query) use ($info) {
                        $query->where('muc_id', $info['search_muc']);
                    });
                });
            });
        }

        if (!empty($info['search_bien_phap_xu_phat_bo_xung'])) {
            $queryKetQuaThanhTra->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                $query->whereHas('xuPhatBoSung', function ($query) use ($info) {
                    $query->whereHas('chiTietXuPhatBoSungs', function ($query) use ($info) {
                        $query->whereIn('loai_xu_phat_bo_sung_id', explode(',', $info['search_bien_phap_xu_phat_bo_xung']));
                    });
                });
            });
        }

        if (!empty($info['search_bien_phap_khac_phuc_vi_pham'])) {
            $queryKetQuaThanhTra->whereHas('quyetDinhXuPhats', function ($query) use ($info) {
                $query->whereHas('bienPhapKhacPhucHauQua', function ($query) use ($info) {
                    $query->whereHas('chiTietBienPhapKhacPhucHauQuas', function ($query) use ($info) {
                        $query->whereIn('cac_bien_phap_khac_phuc_hau_qua_id', explode(',', $info['search_bien_phap_khac_phuc_vi_pham']));
                    });
                });
            });
        }
        // tra cuu co ban
        if (!empty($info['search_co_quan_phe_duyet_dtm'])) {
            $queryKetQuaThanhTra->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($info) {
                    $query->whereHas('loaiThuTuc', function ($query) {
                        $query->where('ma', 'dtm');
                    });
                    $query->whereIn('co_quan_phe_duyet_id', explode(',', $info['search_co_quan_phe_duyet_dtm']));
                });
            });
        }

        if (!empty($info['search_co_quan_phe_duyet_dabvmt'])) {
            $queryKetQuaThanhTra->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($info) {
                    $query->whereHas('loaiThuTuc', function ($query) {
                        $query->where('ma', 'dabvmt');
                    });
                    $query->whereIn('co_quan_phe_duyet_id', explode(',', $info['search_co_quan_phe_duyet_dabvmt']));
                });
            });
        }

        if (!empty($info['search_co_quan_phe_duyet_gxnctbvmt'])) {
            $queryKetQuaThanhTra->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) use ($info) {
                    $query->whereHas('loaiThuTuc', function ($query) {
                        $query->where('ma', 'gxnctbvmt');
                    });
                    $query->whereIn('co_quan_phe_duyet_id', explode(',', $info['search_co_quan_phe_duyet_gxnctbvmt']));
                });
            });
        }

        // DTM, đề án bảo vệ môi trường, kế hoạch bảo vệ môi trường
        if (!empty($info['vi_pham_thu_tuc_hanh_chinh']) && $info['vi_pham_thu_tuc_hanh_chinh'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('vi_pham_thu_tuc_hanh_chinh', true);
            });
        }
        if (!empty($info['dtm_dean_khbvmt_filter']) && $info['dtm_dean_khbvmt_filter'] == true) {
            $queryKetQuaThanhTra->whereHas('ketQuaThanhTraChungs', function ($query) {
                $query->whereHas('ketQuaThanhTraThuTucHanhChinhs', function ($query) {
                    $query->whereHas('loaiThuTuc', function ($query) {
                        $query->whereIn('ma', ['dtm', 'dabvmt', 'pabvmt']);
                    });
                });
            });
        }

        if (!empty($info['dtmdakhbvmt_thuc_hien_khong_dung_noi_dung']) && $info['dtmdakhbvmt_thuc_hien_khong_dung_noi_dung'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('dtmdakhbvmt_thuc_hien_khong_dung_noi_dung', true);
            });
        }
        if (!empty($info['dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung']) && $info['dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung', true);
            });
        }

        // ke hoach quan ly moi truong
        if (!empty($info['co_ket_hoach_quan_ly_moi_truong']) && $info['co_ket_hoach_quan_ly_moi_truong'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('co_ket_hoach_quan_ly_moi_truong', true);
            });
        }
        // cong trinh bao ve moi truong
        if (!empty($info['congtrinhBVMT_khong_xay_lap']) && $info['congtrinhBVMT_khong_xay_lap'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('khong_xay_lap', true);
            });
        }
        if (!empty($info['congtrinhBVMT_xay_lap_khong_dung']) && $info['congtrinhBVMT_xay_lap_khong_dung'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('xay_lap_khong_dung', true);
            });
        }
        if (!empty($info['congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen']) && $info['congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('van_hanh_khong_dung_khong_thuong_xuyen', true);
            });
        }
        if (!empty($info['khong_co_giay_xac_nhan_hoan_thanh']) && $info['khong_co_giay_xac_nhan_hoan_thanh'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('khong_co_giay_xac_nhan_hoan_thanh', true);
            });
        }
        // giam sat moi truong
        if (!empty($info['gsmt_khong_thuc_hien']) && $info['gsmt_khong_thuc_hien'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('gsmt_khong_thuc_hien', true);
            });
        }
        if (!empty($info['gsmt_thuc_hien']) && $info['gsmt_thuc_hien'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('gsmt_thuc_hien', true);
            });
        }
        if (!empty($info['gsmt_thuc_hien_khong_dung_khong_du']) && $info['gsmt_thuc_hien_khong_dung_khong_du'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('gsmt_thuc_hien_khong_dung_khong_du', true);
            });
        }
        // giay phep xa thai
        if (!empty($info['khong_co_giay_phep_xa_thai']) && $info['khong_co_giay_phep_xa_thai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('khong_co_giay_phep_xa_thai', true);
            });
        }
        // giay xac nhan hoan thanh cong trinh bao ve moi truong
        if (!empty($info['khong_thuc_hien_giay_xac_nhan']) && $info['khong_thuc_hien_giay_xac_nhan'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('khong_thuc_hien_giay_xac_nhan', true);
            });
        }
        if (!empty($info['thuc_hien_sai_giay_xac_nhan']) && $info['thuc_hien_sai_giay_xac_nhan'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('thuc_hien_sai_giay_xac_nhan', true);
            });
        }

        // nhom vi pham xa thai
        // tong hop vi pham xa thai
        if (!empty($info['vi_pham_nhom_hanh_vi_xa_thai']) && $info['vi_pham_nhom_hanh_vi_xa_thai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('vi_pham_xa_thai', true);
            });
        }
        // nuoc thai
        if (!empty($info['nuoc_thai_vuot']) && $info['nuoc_thai_vuot'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('nuoc_thai_vuot', true);
            });
        }
        if (!empty($info['co_he_thong_thu_gom_nuoc_thai_rieng_biet']) && $info['co_he_thong_thu_gom_nuoc_thai_rieng_biet'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('co_he_thong_thu_gom_nuoc_thai_rieng_biet', true);
            });
        }

        if (!empty($info['nuoc_thai_vuot_qcvn_tu']) && is_numeric($info['nuoc_thai_vuot_qcvn_tu'])) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('nuoc_thai_vuot_qcvn_tu', '>=', $info['nuoc_thai_vuot_qcvn_tu']);
            });
        }
        if (!empty($info['nuoc_thai_vuot_qcvn_toi']) && is_numeric($info['nuoc_thai_vuot_qcvn_toi'])) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('nuoc_thai_vuot_qcvn_toi', '<=', $info['nuoc_thai_vuot_qcvn_toi']);
            });
        }
        if (isset($info['search_nuoc_thai'])) {
            $queryKetQuaThanhTra->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraNuocThais', function ($query) use ($info) {
                    $query->where('luu_luong', '>=', $info['search_nuoc_thai']);
                    if (isset($info['search_nuoc_thai_max'])) {
                        $query->where('luu_luong', '<=', $info['search_nuoc_thai_max']);
                    }
                });
            });
        }

        // khi thai
        if (!empty($info['co_bien_phap_xu_ly_khi_thai']) && $info['co_bien_phap_xu_ly_khi_thai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('co_bien_phap_xu_ly_khi_thai', true);
            });
        }

        if (!empty($info['khi_thai_vuot_qcvn_tu']) && is_numeric($info['khi_thai_vuot_qcvn_tu'])) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('khi_thai_vuot_qcvn_tu', '>=', $info['khi_thai_vuot_qcvn_tu']);
            });
        }
        if (!empty($info['khi_thai_vuot_qcvn_toi']) && is_numeric($info['khi_thai_vuot_qcvn_toi'])) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('khi_thai_vuot_qcvn_toi', '<=', $info['khi_thai_vuot_qcvn_toi']);
            });
        }
        if (isset($info['search_khi_thai'])) {
            $queryKetQuaThanhTra->whereHas('ketQuaThanhTraChungs', function ($query) use ($info) {
                $query->whereHas('ketQuaThanhTraKhiThais', function ($query) use ($info) {
                    $query->where('luu_luong', '>=', $info['search_khi_thai']);
                    if (isset($info['search_khi_thai_max'])) {
                        $query->where('luu_luong', '<=', $info['search_khi_thai_max']);
                    }
                });
            });
        }
        // chat thai ran sinh hoat
        if (!empty($info['ctrsh_co_thu_gom']) && $info['ctrsh_co_thu_gom'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrsh_co_thu_gom', true);
            });
        }
        if (!empty($info['ctrsh_co_phan_loai']) && $info['ctrsh_co_phan_loai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrsh_co_phan_loai', true);
            });
        }
        if (!empty($info['ctrsh_co_luu_tru']) && $info['ctrsh_co_luu_tru'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrsh_co_luu_tru', true);
            });
        }
        if (!empty($info['ctrsh_co_chuyen_giao']) && $info['ctrsh_co_chuyen_giao'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrsh_co_chuyen_giao', true);
            });
        }
        // chat thai ran cong nghiep
        if (!empty($info['ctrcn_co_thu_gom']) && $info['ctrcn_co_thu_gom'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrcn_co_thu_gom', true);
            });
        }
        if (!empty($info['ctrcn_co_phan_loai']) && $info['ctrcn_co_phan_loai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrcn_co_phan_loai', true);
            });
        }
        if (!empty($info['ctrcn_co_luu_tru']) && $info['ctrcn_co_luu_tru'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrcn_co_luu_tru', true);
            });
        }
        if (!empty($info['ctrcn_co_chuyen_giao']) && $info['ctrcn_co_chuyen_giao'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) use ($info) {
                $query->where('ctrcn_co_chuyen_giao', true);
            });
        }

        // chat thai ran nguy hai
        if (!empty($info['ctrnh_vi_pham_chung_tu']) && $info['ctrnh_vi_pham_chung_tu'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_vi_pham_chung_tu', true);
            });
        }
        if (!empty($info['ctrnh_co_thu_gom']) && $info['ctrnh_co_thu_gom'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_thu_gom', true);
            });
        }
        if (!empty($info['ctrnh_co_phan_loai']) && $info['ctrnh_co_phan_loai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_phan_loai', true);
            });
        }
        if (!empty($info['ctrnh_co_luu_tru']) && $info['ctrnh_co_luu_tru'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_luu_tru', true);
            });
        }
        if (!empty($info['ctrnh_co_de_lan']) && $info['ctrnh_co_de_lan'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_de_lan', true);
            });
        }

        if (!empty($info['ctrnh_co_chuyen_giao']) && $info['ctrnh_co_chuyen_giao'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_chuyen_giao', true);
            });
        }

        if (!empty($info['ctrnh_co_chon_lap']) && $info['ctrnh_co_chon_lap'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_chon_lap', true);
            });
        }

        if (!empty($info['ctrnh_co_do_thai']) && $info['ctrnh_co_do_thai'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_do_thai', true);
            });
        }

        if (!empty($info['ctrnh_co_giay_phep']) && $info['ctrnh_co_giay_phep'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('ctrnh_co_giay_phep', true);
            });
        }
        // bien phap xu phat bo sung
        if (!empty($info['bienphapxuphatboxung_dinh_chi']) && $info['bienphapxuphatboxung_dinh_chi'] == true) {
            $queryKetQuaThanhTra->whereHas('quyetDinhXuPhats', function ($query) {
                $query->whereHas('xuPhatBoSung', function ($query) {
                    $query->where('dinh_chi', true);
                });
            });
        }
        // ket qua khac phuc hau qua vi pham
        if (!empty($info['ketquakhacphuchauqua_nop_mot_phan']) && $info['ketquakhacphuchauqua_nop_mot_phan'] == true) {
            $queryKetQuaThanhTra->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                $query->where('nop_mot_phan', true);
            });
        }

        if (!empty($info['ketquakhacphuchauqua_da_nop_phat']) && $info['ketquakhacphuchauqua_da_nop_phat'] == true) {
            $queryKetQuaThanhTra->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                $query->where('da_nop_phat', true);
            });
        }

        if (!empty($info['ketquakhacphuchauqua_da_khac_phuc']) && $info['ketquakhacphuchauqua_da_khac_phuc'] == true) {
            $queryKetQuaThanhTra->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                $query->where('da_khac_phuc', true);
            });
        }

        if (!empty($info['ketquakhacphuchauqua_da_bao_cao']) && $info['ketquakhacphuchauqua_da_bao_cao'] == true) {
            $queryKetQuaThanhTra->whereHas('ketQuaKhacPhucHauQuas', function ($query) {
                $query->where('da_bao_cao', true);
            });
        }
        // vi pham
        if (!empty($info['vi_pham']) && $info['vi_pham'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('vi_pham', true);
            });
        }
        // vi pham khac
        if (!empty($info['vi_pham_nhom_hanh_vi_khac']) && $info['vi_pham_nhom_hanh_vi_khac'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('nhom_hanh_vi_khac', true);
            });
        }
        if (!empty($info['vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong']) && $info['vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong', true);
            });
        }
        if (!empty($info['vi_pham_cac_quy_dinh_bao_ve_moi_truong']) && $info['vi_pham_cac_quy_dinh_bao_ve_moi_truong'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('vi_pham_cac_quy_dinh_bao_ve_moi_truong', true);
            });
        }
        if (!empty($info['co_hanh_vi_can_tro_ve_bvmt']) && $info['co_hanh_vi_can_tro_ve_bvmt'] == true) {
            $queryKetQuaThanhTra->whereHas('nhomHanhViViPhams', function ($query) {
                $query->where('co_hanh_vi_can_tro_ve_bvmt', true);
            });
        }
    }
    public function showPreview(Request $request)
    {
        return view('baocao.xuatbaocao');
    }
    public function _getData($request, $isLimit = false)
    {
        $dataReturn = [];
        $query = \App\Organization::query();;
        $search_start_date = $request->get('search_start_date');
        $search_end_date = $request->get('search_end_date');
        if (isset($search_start_date)) {
            $search_start_date = Carbon::createFromFormat(config('app.format_date'), $search_start_date)->startOfDay();
        } else {
            $search_start_date = Carbon::now()->addYears(-2)->startOfDay();
        }
        if (isset($search_end_date)) {
            $search_end_date = Carbon::createFromFormat(config('app.format_date'), $search_end_date)->endOfDay();
        } else {
            $search_end_date = Carbon::now()->endOfDay();
        }
        $info = $request->all();
        $tinhThanhs = $this->getDataByName(\App\TinhThanh::class);
        $query->whereHas('ketQuaThanhTras', function ($query) use ($search_end_date, $search_start_date) {
            $query->whereHas('quyetDinhThanhTra', function ($query) use ($search_end_date, $search_start_date) {
                $query->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
                $query->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
            });
        });
        $this->getQueryOrganization($info, $query, $tinhThanhs);
        $organizations = $query->select('id')->get();

        // query theo doan thanh tra
        $queryQuyetDinhThanhTra = \App\QuyetDinhThanhTra::query();
        $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
        $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
        $queryQuyetDinhThanhTra->whereHas('ketQuaThanhTras', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryQuyetDinhThanhTra($queryQuyetDinhThanhTra);
        $quyetDinhThanhTras = $queryQuyetDinhThanhTra->get();

        // ket qua thanh tra
        $queryKetQuaThanhTra = \App\KetQuaThanhTra::query();
        $queryKetQuaThanhTra->where(function ($query) use ($quyetDinhThanhTras, $organizations) {
            $query->whereIn('quyet_dinh_thanh_tra_id', $quyetDinhThanhTras->pluck('id'));
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryKetQuaThanhTra($info, $queryKetQuaThanhTra);

        $queryKetQuaThanhTra = $queryKetQuaThanhTra->with([
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.coQuanToChuc',
            'ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs.loaiHinhCoSo',
            'ketQuaThanhTraChungs.ketQuaThanhTraNuocThais',
            'quyetDinhXuPhats.xuPhatChinh',
            'quyetDinhXuPhats.xuPhatBoSung.chiTietXuPhatBoSungs',
            'quyetDinhXuPhats.bienPhapKhacPhucHauQua.chiTietBienPhapKhacPhucHauQuas',
            'quyetDinhXuPhats.ketQuaThanhTra',
            'ketQuaKhacPhucHauQuas',
            'nhomHanhViViPhams',
            'nhomHanhViViPhams.chiTietViPhamXaThais',
            'nhomHanhViViPhams.chiTietViPhamXaThais.thongSo',
            'quyetDinhThanhTra',
        ]);
        if ($isLimit) {
            $queryKetQuaThanhTra->take(20);
        }
        $ketQuaThanhTrasSorted = $queryKetQuaThanhTra->orderBy('organization_id')->get();

        $organizationIds = [];
        foreach ($ketQuaThanhTrasSorted as $it) {
            $organizationIds[] = $it->organization_id;
        }
        $coSoSanXuat = CoSoSanXuat::whereIn('organization_id', $organizationIds)->with([
            'organization',
            'organization.chuDauTu',
            'organization.chuDauTu.tinhThanh',
            'organization.chuDauTu.quanHuyen',
            'loaiHinhONhiem',
            'tinhThanh:id,ten',
            'quanHuyen:id,ten',
            'luuVucSong',
            'loaiCoSo'
        ])->get();
        $dataReturn['DoiTuongThanhTraCoBan'] = $coSoSanXuat->map(function ($item, $key) {
            return [
                'A' => $key + 1,
                'B' => $item->id,
                'C' => $item->ten,
                'D' => $item->dia_chi_hoat_dong,
                'E' => $item->xa_phuong,
                'F' => $item->quanHuyen ? $item->quanHuyen->ten : '',
                'G' => $item->tinhThanh ? $item->tinhThanh->ten : '',
                'H' => $item->organization->chuDauTu ? $item->organization->chuDauTu->id : '',
                'I' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ten : '',
                'J' => $item->organization->chuDauTu ? $item->organization->chuDauTu->dia_chi : '',
                'K' => $item->organization->chuDauTu ? $item->organization->chuDauTu->xa_phuong : '',
                'L' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->quanHuyen ? $item->organization->chuDauTu->quanHuyen->ten : '') : '',
                'M' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->tinhThanh ? $item->organization->chuDauTu->tinhThanh->ten : '') : '',
                'N' => '',
                'O' => $item->loaiCoSo->ten_co_so,
                // 'P' => $item->loaiHinhONhiem ? $item->loaiHinhONhiem->ten :'',
                'P' => '',
                'Q' => '',
                'R' => '',
                'S' => $item->luuVucSong ? $item->luuVucSong->ten : '',
            ];
        });
        if ($request->get('maus_ket_qua_thanh_tra')) {
            $dataReturn['KetQuaThanhTra'] = $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                });
                $danhSachThuTucHanhChinhCapBoPheDuyet = collect();
                $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet = collect();
                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($danhSachThuTucHanhChinhCapBoPheDuyet, $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet) {
                    $value->ketQuaThanhTraThuTucHanhChinhs->each(function ($value, $key) use ($danhSachThuTucHanhChinhCapBoPheDuyet, $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet) {
                        $fullString = $value->coQuanToChuc->ten . "(" . $value->loaiThuTuc->ten . ")";
                        if ($value->coQuanToChuc->cap_bo) {
                            $danhSachThuTucHanhChinhCapBoPheDuyet->push($fullString);
                        } else {
                            $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet->push($fullString);
                        }
                    });
                });
                $danhSachQuyetDinhXuPhat = collect();
                $danhSachXuPhatChinh = collect();
                $danhSachXuPhatBoXung = collect();
                $danhSachBienPhapKhacPhucHauQua = collect();
                $danhSachViPhamTonTai = collect();
                $item->quyetDinhXuPhats->each(function ($value, $key) use ($danhSachViPhamTonTai, $danhSachQuyetDinhXuPhat, $danhSachXuPhatChinh, $danhSachXuPhatBoXung, $danhSachBienPhapKhacPhucHauQua) {
                    $danhSachQuyetDinhXuPhat->push($value->so_quyet_dinh . "(" . $value->thoi_gian_ban_hanh . ")");
                    $danhSachViPhamTonTai->push($value->ghi_chu);
                    $value->xuPhatChinh->each(function ($value, $key) use ($danhSachXuPhatChinh) {
                        $danhSachXuPhatChinh->push($value);
                    });
                    $value->xuPhatBoSung->each(function ($value, $key) use ($danhSachXuPhatBoXung) {
                        $danhSachXuPhatBoXung->push($value);
                    });
                    $value->bienPhapKhacPhucHauQua->each(function ($value, $key) use ($danhSachBienPhapKhacPhucHauQua) {
                        $danhSachBienPhapKhacPhucHauQua->push($value);
                    });
                });
                return [
                    'A' => $key + 1,
                    'B' => $item->organization->ten,
                    'C' => $item->organization->dia_chi_lien_he,
                    'D' => $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"),
                    'E' => $danhSachThuTucHanhChinhCapBoPheDuyet->unique('id')->implode("\n"),
                    'F' => $danhSachThuTucHanhChinhCapDiaPhuongPheDuyet->unique('id')->implode("\n"),
                    'G' => $item->quyetDinhThanhTra->so_quyet_dinh . "(" . $item->quyetDinhThanhTra->ngay_ra_quyet_dinh . ")",
                    'H' => $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")",
                    'I' => $danhSachQuyetDinhXuPhat->implode("\n"),
                    'J' => $danhSachXuPhatChinh->sum('so_tien_xu_phat_chinh'),
                    'K' => $danhSachXuPhatBoXung->implode('noi_dung_xu_phat_bo_sung', "\n"),
                    'L' => $danhSachBienPhapKhacPhucHauQua->implode('noi_dung_bien_phap_khac_phuc_hau_qua', "\n"),
                    'M' => $item->mo_ta,
                    'N' => $danhSachXuPhatChinh->implode('noi_dung_vi_pham', "\n"),
                    'O' => $danhSachViPhamTonTai->implode("\n")
                ];
            });
        }

        if ($request->get('maus_thong_tin_chung')) {
            $dataReturn['ThongTinChung'] = $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                $ketQuaThanhTraNuocThais = collect();
                $ketQuaThanhTraKhiThais = collect();

                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs, $ketQuaThanhTraNuocThais, $ketQuaThanhTraKhiThais) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                    $value->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($ketQuaThanhTraNuocThais) {
                        $ketQuaThanhTraNuocThais->push($value);
                    });
                    $value->ketQuaThanhTraKhiThais->each(function ($value, $key) use ($ketQuaThanhTraKhiThais) {
                        $ketQuaThanhTraKhiThais->push($value);
                    });
                });

                $danhSachQuyetDinhXuPhat = collect();
                $danhSachViPhamTonTai = collect();
                $danhSachXuPhatChinh = collect();
                $item->quyetDinhXuPhats->each(function ($value, $key) use ($danhSachQuyetDinhXuPhat, $danhSachViPhamTonTai, $danhSachXuPhatChinh) {
                    $danhSachQuyetDinhXuPhat->push($value->so_quyet_dinh . "(" . $value->thoi_gian_ban_hanh . ")");
                    $danhSachViPhamTonTai->push($value->ghi_chu);
                    $value->xuPhatChinh->each(function ($value, $key) use ($danhSachXuPhatChinh) {
                        $danhSachXuPhatChinh->push($value);
                    });
                });
                $ketQuaKhacPhucHauQuaViPham = collect();
                $item->ketQuaKhacPhucHauQuas->each(function ($value, $key) use ($ketQuaKhacPhucHauQuaViPham) {
                    $ketQuaKhacPhucHauQuaViPham->push($value);
                });
                $dataM = '';
                if (count($ketQuaKhacPhucHauQuaViPham->where('da_nop_phat', true)) > 0) {
                    $dataM = 'Đã nộp phạt';
                } else if (count($ketQuaKhacPhucHauQuaViPham->where('nop_mot_phan', true)) > 0) {
                    $dataM = 'Nộp một phần';
                }
                $dataO = '';
                if (count($ketQuaKhacPhucHauQuaViPham->where('nop_mot_phan', true)) > 0) {
                    $dataO = '1';
                } else {
                    $dataO = '-';
                }

                $dataP = '';
                if (count($ketQuaKhacPhucHauQuaViPham->where('da_nop_phat', true)) > 0) {
                    $dataP = '1';
                } else {
                    $dataP = '-';
                }
                return [
                    'A' => $key + 1,
                    'B' => $item->organization->ten,
                    'C' => $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"),
                    'D' => $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"),
                    'E' => $ketQuaThanhTraNuocThais->pluck('luu_luong')->implode("\n"),
                    'F' => $ketQuaThanhTraNuocThais->pluck('cong_suat_he_thong_xu_ly')->implode("\n"),
                    'G' => $ketQuaThanhTraNuocThais->pluck('nguon_tiep_nhan')->implode("\n"),
                    'H' => $ketQuaThanhTraKhiThais->pluck('luu_luong')->implode("\n"),
                    'I' => $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")",
                    'J' => $danhSachQuyetDinhXuPhat->implode("\n"),
                    'K' => $danhSachXuPhatChinh->implode('noi_dung_vi_pham', "\n"),
                    'L' => $danhSachViPhamTonTai->implode("\n"),
                    'M' => $dataM,
                    'N' => $ketQuaKhacPhucHauQuaViPham->implode('noi_dung_chua_khac_phuc', "\n"),
                    'O' => $dataO,
                    'P' => $dataP
                ];
            });
        }

        if ($request->get('maus_xa_thai')) {
            $dataReturn['XaThai'] =  $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                $ketQuaThanhTraNuocThais = collect();
                $ketQuaThanhTraKhiThais = collect();

                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs, $ketQuaThanhTraNuocThais, $ketQuaThanhTraKhiThais) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                    $value->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($ketQuaThanhTraNuocThais) {
                        $ketQuaThanhTraNuocThais->push($value);
                    });
                    $value->ketQuaThanhTraKhiThais->each(function ($value, $key) use ($ketQuaThanhTraKhiThais) {
                        $ketQuaThanhTraKhiThais->push($value);
                    });
                });

                $dataM = collect();
                $thongSoVuot = collect();
                $item->nhomHanhViViPhams->each(function ($item) use ($thongSoVuot, $dataM) {
                    $item->chiTietViPhamXaThais->each(function ($chitiet) use ($thongSoVuot, $dataM) {
                        if (isset($chitiet->thongSo)) {
                            $thongSoVuot->push($chitiet->thongSo->ten);
                        } else {
                            $thongSoVuot->push('');
                        }
                        $dataM->push($chitiet->ket_qua ?? 0);
                    });
                });
                return [
                    'A' => $key + 1,
                    'B' => $item->organization->ten,
                    'C' => $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"),
                    'D' => $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"),
                    'E' => $ketQuaThanhTraNuocThais->pluck('luu_luong')->implode("\n"),
                    'F' => $ketQuaThanhTraNuocThais->pluck('cong_suat_he_thong_xu_ly')->implode("\n"),
                    'G' => $ketQuaThanhTraNuocThais->pluck('nguon_tiep_nhan')->implode("\n"),
                    'H' => $ketQuaThanhTraKhiThais->pluck('luu_luong')->implode("\n"),
                    'I' => $item->quyetDinhThanhTra->so_quyet_dinh . "(" . $item->quyetDinhThanhTra->ngay_ra_quyet_dinh . ")",
                    'J' => $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")",
                    'K' => $ketQuaThanhTraNuocThais->pluck('thong_so_nuoc_thai_vuot_chuan')->implode("\n"),
                    'L' => $thongSoVuot->implode("\n"),
                    'M' => $dataM->implode("\n")
                ];
            });
        }

        if ($request->get('maus_tong_hop_cac_nhom_vi_pham')) {
            $dataReturn['TongHopCacNhomViPham'] =  $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                $ketQuaThanhTraNuocThais = collect();
                $ketQuaThanhTraKhiThais = collect();

                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs, $ketQuaThanhTraNuocThais, $ketQuaThanhTraKhiThais) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                    $value->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($ketQuaThanhTraNuocThais) {
                        $ketQuaThanhTraNuocThais->push($value);
                    });
                    $value->ketQuaThanhTraKhiThais->each(function ($value, $key) use ($ketQuaThanhTraKhiThais) {
                        $ketQuaThanhTraKhiThais->push($value);
                    });
                });

                $dataReturn = [
                    'A' => $key + 1,
                    'B' => $item->organization->ten,
                    'C' => $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n"),
                    'D' => $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n"),
                    'E' => $ketQuaThanhTraNuocThais->pluck('luu_luong')->implode("\n"),
                    'F' => $ketQuaThanhTraKhiThais->pluck('luu_luong')->implode("\n"),
                ];
                if ($item->nhomHanhViViPhams->count() > 0) {
                    $nhomHanhViViPham = $item->nhomHanhViViPhams[0];
                    $dmt_khbvmt_da = collect();
                    if ($nhomHanhViViPham->dtmdakhbvmt_thuc_hien_khong_dung_noi_dung) {
                        $dmt_khbvmt_da->push('Thực hiện không đúng nội dung');
                    }
                    if ($nhomHanhViViPham->dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung) {
                        $dmt_khbvmt_da->push('Không thực hiện một trong các nội dung');
                    }
                    $dataReturn['G'] = $dmt_khbvmt_da->implode("\n");

                    if ($nhomHanhViViPham->khong_co_giay_xac_nhan_hoan_thanh) {
                        $dataReturn['H'] =  'Không có giấy xác nhận hoàn thành';
                    }

                    $gsmt = collect();
                    if ($nhomHanhViViPham->gsmt_khong_thuc_hien) {
                        $gsmt->push('Không thực hiện');
                    }
                    if ($nhomHanhViViPham->gsmt_thuc_hien_khong_dung_khong_du) {
                        $gsmt->push('Thực hiện không đúng, không đủ');
                    }

                    $dataReturn['I'] =  $gsmt->implode("\n");

                    $congtrinh_bvmt = collect();
                    if ($nhomHanhViViPham->khong_xay_lap) {
                        $congtrinh_bvmt->push('Không xây lắp');
                    }
                    if ($nhomHanhViViPham->xay_lap_khong_dung) {
                        $congtrinh_bvmt->push('Xây lắp không đúng');
                    }
                    if ($nhomHanhViViPham->van_hanh_khong_dung_khong_thuong_xuyen) {
                        $congtrinh_bvmt->push('Vận hành ko đúng/không thường xuyên');
                    }
                    if ($nhomHanhViViPham->khong_co_giay_xac_nhan_hoan_thanh) {
                        $congtrinh_bvmt->push('Không có giấy xác nhận hoàn thành');
                    }
                    $dataReturn['J'] = $congtrinh_bvmt->implode("\n");

                    if ($nhomHanhViViPham->nuoc_thai_vuot == true) {
                        $dataReturn['K'] = '1';
                    } else {
                        $dataReturn['K'] = '-';
                    }

                    if ($nhomHanhViViPham->khi_thai_vuot_qcvn_toi > 0) {
                        $dataReturn['L'] =  '1';
                    } else {
                        $dataReturn['L'] =  '-';
                    }

                    if ($nhomHanhViViPham->khong_xay_lap == true) {
                        $dataReturn['M'] =  '1';
                    } else {
                        $dataReturn['M'] =  '-';
                    }

                    if ($nhomHanhViViPham->ctrnh_vi_pham_chung_tu == true) {
                        $dataReturn['N'] = '1';
                    } else {
                        $dataReturn['N'] = '-';
                    }

                    if ($nhomHanhViViPham->ctrsh_co_thu_gom == true || $nhomHanhViViPham->ctrsh_co_phan_loai == true || $nhomHanhViViPham->ctrsh_co_luu_tru == true || $nhomHanhViViPham->ctrsh_co_chuyen_giao == true) {
                        $dataReturn['O'] = '-';
                    } else {
                        $dataReturn['O'] = '1';
                    }
                }
                return $dataReturn;
            });
        }

        if ($request->get('maus_chat_thai_ran')) {
            $dataReturn['ChatThaiRan'] =  $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $dataReturn = [];
                $dataReturn['A'] = $key + 1;
                $dataReturn['B'] = $item->organization->ten;
                $dataReturn['C'] = $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n");
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();

                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                });
                $dataReturn['D'] = $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n");

                $count_ctrsh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->trsh_co_thu_gom;
                })->count();
                if ($count_ctrsh_co_thu_gom > 0) {
                    $dataReturn['E'] = '1';
                } else {
                    $dataReturn['E'] = '-';
                }
                $count_ctrsh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrsh_co_phan_loai;
                })->count();
                if ($count_ctrsh_co_phan_loai > 0) {
                    $dataReturn['F'] = '1';
                } else {
                    $dataReturn['F'] = '-';
                }
                $count_ctrsh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrsh_co_luu_tru;
                })->count();
                if ($count_ctrsh_co_luu_tru > 0) {
                    $dataReturn['G'] = '1';
                } else {
                    $dataReturn['G'] = '-';
                }
                $count_ctrsh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrsh_co_chuyen_giao;
                })->count();
                if ($count_ctrsh_co_chuyen_giao > 0) {
                    $dataReturn['H'] = '1';
                } else {
                    $dataReturn['H'] = '-';
                }

                $count_ctrcn_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_thu_gom;
                })->count();
                if ($count_ctrcn_co_thu_gom > 0) {
                    $dataReturn['I'] = '1';
                } else {
                    $dataReturn['I'] = '-';
                }

                $count_ctrcn_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_phan_loai;
                })->count();
                if ($count_ctrcn_co_phan_loai > 0) {
                    $dataReturn['J'] = '1';
                } else {
                    $dataReturn['J'] = '-';
                }

                $count_ctrcn_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_luu_tru;
                })->count();
                if ($count_ctrcn_co_luu_tru > 0) {
                    $dataReturn['K'] = '1';
                } else {
                    $dataReturn['K'] = '-';
                }

                $count_ctrcn_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_chuyen_giao;
                })->count();
                if ($count_ctrcn_co_chuyen_giao > 0) {
                    $dataReturn['L'] = '1';
                } else {
                    $dataReturn['L'] = '-';
                }

                // ctnh
                $count_ctrnh_vi_pham_chung_tu = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_vi_pham_chung_tu;
                })->count();
                if ($count_ctrnh_vi_pham_chung_tu > 0) {
                    $dataReturn['M'] = '1';
                } else {
                    $dataReturn['M'] = '-';
                }
                $count_ctrnh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_thu_gom;
                })->count();
                if ($count_ctrnh_co_thu_gom > 0) {
                    $dataReturn['N'] = '1';
                } else {
                    $dataReturn['N'] = '-';
                }
                $count_ctrnh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_phan_loai;
                })->count();
                if ($count_ctrnh_co_phan_loai > 0) {
                    $dataReturn['O'] = '1';
                } else {
                    $dataReturn['O'] = '-';
                }
                $count_ctrnh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_luu_tru;
                })->count();
                if ($count_ctrnh_co_luu_tru > 0) {
                    $dataReturn['P'] = '1';
                } else {
                    $dataReturn['P'] = '-';
                }
                $count_ctrnh_co_de_lan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_de_lan;
                })->count();
                if ($count_ctrnh_co_de_lan > 0) {
                    $dataReturn['Q'] = '1';
                } else {
                    $dataReturn['Q'] = '-';
                }

                $count_ctrnh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_chuyen_giao;
                })->count();
                if ($count_ctrnh_co_chuyen_giao > 0) {
                    $dataReturn['R'] = '1';
                } else {
                    $dataReturn['R'] = '-';
                }

                $count_ctrnh_co_chon_lap = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_chon_lap;
                })->count();
                if ($count_ctrnh_co_chon_lap > 0) {
                    $dataReturn['S'] = '1';
                } else {
                    $dataReturn['S'] = '-';
                }

                $count_ctrnh_co_do_thai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_do_thai;
                })->count();
                if ($count_ctrnh_co_do_thai > 0) {
                    $dataReturn['T'] = '1';
                } else {
                    $dataReturn['T'] = '-';
                }

                $count_ctrnh_co_giay_phep = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_giay_phep;
                })->count();
                if ($count_ctrnh_co_giay_phep > 0) {
                    $dataReturn['U'] = '1';
                } else {
                    $dataReturn['U'] = '-';
                }
                return $dataReturn;
            });
        }
        if ($request->get('maus_theo_doi_thuc_hien_kltt')) {
            $dataReturn['TheoDoiThucHienKLTT'] =  $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $dataReturn = [];
                $dataReturn['A'] = $key + 1;
                $dataReturn['B'] = $item->organization->ten;
                $dataReturn['C'] = $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n");
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                });
                $dataReturn['D'] = $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n");
                $dataReturn['E'] = $item->quyetDinhThanhTra->so_quyet_dinh . "(" . $item->quyetDinhThanhTra->ngay_ra_quyet_dinh . ")";
                $dataReturn['F'] = $item->so_quyet_dinh . "(" . $item->ngay_thanh_tra . ")";

                $danhSachQuyetDinhXuPhat = collect();
                $danhSachHanhViViPham = collect();
                $danhSachNoiDungViPham = collect();
                $danhSachViPhamTonTai = collect();
                $danhSachXuPhatChinh = collect();
                $danhSachXuPhatBoXung = collect();
                $danhSachBienPhapKhacPhucHauQua = collect();
                $item->quyetDinhXuPhats->each(function ($value) use ($danhSachXuPhatBoXung, $danhSachBienPhapKhacPhucHauQua, $danhSachViPhamTonTai, $danhSachQuyetDinhXuPhat, $danhSachHanhViViPham, $danhSachNoiDungViPham, $danhSachXuPhatChinh) {
                    $danhSachQuyetDinhXuPhat->push($value->so_quyet_dinh . "(" . $value->thoi_gian_ban_hanh . ")");
                    $danhSachViPhamTonTai->push($value->ghi_chu);
                    $value->xuPhatChinh->each(function ($xuPhatChinh) use ($danhSachXuPhatChinh, $danhSachHanhViViPham, $danhSachNoiDungViPham) {
                        $danhSachNoiDungViPham->push($xuPhatChinh->noi_dung_vi_pham);
                        $danhSachXuPhatChinh->push($xuPhatChinh);
                        $xuPhatChinh->hanhViViPhams->each(function ($value) use ($danhSachHanhViViPham) {
                            $tenHanhVi = $value->dieu->ten;
                            if (!empty($value->khoan->ten)) {
                                $tenHanhVi = $tenHanhVi . ", Khoản: " . $value->khoan->ten;
                                if (!empty($value->muc->ten)) {
                                    $tenHanhVi = $tenHanhVi . ",Mục: " . $value->muc->ma . " - " . $value->muc->mo_ta;
                                }
                            }
                            $danhSachHanhViViPham->push($tenHanhVi);
                        });
                    });
                    $value->xuPhatBoSung->each(function ($value) use ($danhSachXuPhatBoXung) {
                        $danhSachXuPhatBoXung->push($value);
                    });
                    $value->bienPhapKhacPhucHauQua->each(function ($value) use ($danhSachBienPhapKhacPhucHauQua) {
                        $danhSachBienPhapKhacPhucHauQua->push($value);
                    });
                });

                $dataReturn['G'] = $danhSachQuyetDinhXuPhat->implode("\n");
                $dataReturn['H'] = $danhSachNoiDungViPham->implode("\n") . "\n" . $danhSachHanhViViPham->implode("\n");
                $dataReturn['I'] = $danhSachXuPhatChinh->sum('so_tien_xu_phat_chinh');
                $dataReturn['J'] = $danhSachXuPhatBoXung->implode('noi_dung_xu_phat_bo_sung', "\n");
                $dataReturn['K'] = $danhSachBienPhapKhacPhucHauQua->implode('noi_dung_bien_phap_khac_phuc_hau_qua', "\n");

                $ketQuaKhacPhucHauQuaViPham = collect();
                $ketQuaKhacPhucHauQuaViPhamDaBaoCao = collect();
                $item->ketQuaKhacPhucHauQuas->each(function ($value, $key) use ($ketQuaKhacPhucHauQuaViPham, $ketQuaKhacPhucHauQuaViPhamDaBaoCao) {
                    $ketQuaKhacPhucHauQuaViPham->push($value);
                    if ($value->da_bao_cao) {
                        $ketQuaKhacPhucHauQuaViPhamDaBaoCao->push($value->so_hieu_bao_cao . "(" . $value->ngay_ban_hanh_bao_cao . ")\n" . $value->tinh_trang_bao_cao);
                    }
                });
                if (count($ketQuaKhacPhucHauQuaViPham->where('da_nop_phat', true)) > 0) {
                    $dataReturn['L'] = 'Đã nộp phạt';
                } else if (count($ketQuaKhacPhucHauQuaViPham->where('nop_mot_phan', true)) > 0) {
                    $dataReturn['L'] = 'Nộp một phần';
                }
                if (count($ketQuaKhacPhucHauQuaViPhamDaBaoCao) > 0) {
                    $dataReturn['M'] = $ketQuaKhacPhucHauQuaViPhamDaBaoCao->implode("\n");
                }
                $dataReturn['N'] = $ketQuaKhacPhucHauQuaViPham->implode('noi_dung_chua_khac_phuc', "\n");
                $dataReturn['O'] = $ketQuaKhacPhucHauQuaViPham->implode('noi_dung_da_khac_phuc', "\n");
                return $dataReturn;
            });
        }
        if ($request->get('maus_nhom_hanh_vi_vi_pham')) {
            $dataReturn['NhomHanhViViPham'] =   $ketQuaThanhTrasSorted->map(function ($item, $key) {
                $dataReturn = [];
                $dataReturn['A'] = $key + 1;
                $dataReturn['B'] = $item->organization->ten;
                $dataReturn['C'] = $item->ketQuaThanhTraChungs->pluck('dia_chi_hoat_dong')->implode("\n");
                $ketQuaThanhTraLoaiHinhHoatDongs = collect();
                $item->ketQuaThanhTraChungs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                    $value->ketQuaThanhTraChungLoaiHinhHoatDongs->each(function ($value, $key) use ($ketQuaThanhTraLoaiHinhHoatDongs) {
                        $ketQuaThanhTraLoaiHinhHoatDongs->push($value);
                    });
                });
                $dataReturn['D'] = $ketQuaThanhTraLoaiHinhHoatDongs->pluck('loaiHinhCoSo')->unique('id')->implode('ten', "\n");
                $co_dtm_dean_khbvmt = '-';
                $co_giay_phep_xa_thai = '-';
                $item->ketQuaThanhTraChungs->each(function ($value, $key) use (&$co_dtm_dean_khbvmt, &$co_giay_phep_xa_thai) {
                    $count_dtm_dean_khbvmt = $value->ketQuaThanhTraThuTucHanhChinhs->filter(function ($value, $key) {
                        return ($value->loaiThuTuc->ma == 'dtm' || $value->loaiThuTuc->ma == "dabvmt" || $value->loaiThuTuc->ma == "pabvmt");
                    })->count();
                    if ($count_dtm_dean_khbvmt > 0) {
                        $co_dtm_dean_khbvmt = '1';
                    }
                    $count_giay_phep_xa_thai = $value->ketQuaThanhTraThuTucHanhChinhs->filter(function ($value, $key) {
                        return ($value->loaiThuTuc->ma == "gpxt");
                    })->count();
                    if ($count_giay_phep_xa_thai > 0) {
                        $co_giay_phep_xa_thai = '1';
                    }
                });

                $dataReturn['E'] = $co_dtm_dean_khbvmt;

                $count_dtmdakhbvmt_thuc_hien_khong_dung_noi_dung = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->dtmdakhbvmt_thuc_hien_khong_dung_noi_dung;
                })->count();
                if ($count_dtmdakhbvmt_thuc_hien_khong_dung_noi_dung > 0) {
                    $dataReturn['F'] = '1';
                } else {
                    $dataReturn['F'] = '-';
                }
                $count_dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung;
                })->count();
                if ($count_dtmdakhbvmt_khong_thuc_hien_mot_trong_cac_noi_dung > 0) {
                    $dataReturn['G'] = '1';
                } else {
                    $dataReturn['G'] = '-';
                }

                $count_co_ket_hoach_quan_ly_moi_truong = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->co_ket_hoach_quan_ly_moi_truong;
                })->count();
                if ($count_co_ket_hoach_quan_ly_moi_truong > 0) {
                    $dataReturn['H'] = '1';
                } else {
                    $dataReturn['H'] = '-';
                }

                $count_congtrinhBVMT_khong_xay_lap = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->khong_xay_lap;
                })->count();
                if ($count_congtrinhBVMT_khong_xay_lap > 0) {
                    $dataReturn['I'] = '1';
                } else {
                    $dataReturn['I'] = '-';
                }

                $count_congtrinhBVMT_xay_lap_khong_dung = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->xay_lap_khong_dung;
                })->count();
                if ($count_congtrinhBVMT_xay_lap_khong_dung > 0) {
                    $dataReturn['J'] = '1';
                } else {
                    $dataReturn['J'] = '-';
                }

                $count_congtrinhBVMT_khong_co_giay_xac_nhan_hoan_thanh = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->khong_co_giay_xac_nhan_hoan_thanh;
                })->count();
                if ($count_congtrinhBVMT_khong_co_giay_xac_nhan_hoan_thanh > 0) {
                    $dataReturn['K'] = '1';
                } else {
                    $dataReturn['K'] = '-';
                }
                $count_congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->van_hanh_khong_dung_khong_thuong_xuyen;
                })->count();
                if ($count_congtrinhBVMT_van_hanh_khong_dung_khong_thuong_xuyen > 0) {
                    $dataReturn['L'] = '1';
                } else {
                    $dataReturn['L'] = '-';
                }

                $count_gsmt_thuc_hien = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->gsmt_thuc_hien;
                })->count();
                if ($count_gsmt_thuc_hien > 0) {
                    $dataReturn['M'] = '1';
                } else {
                    $dataReturn['M'] = '-';
                }

                $count_gsmt_khong_thuc_hien = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->gsmt_khong_thuc_hien;
                })->count();
                if ($count_gsmt_khong_thuc_hien > 0) {
                    $dataReturn['N'] = '1';
                } else {
                    $dataReturn['N'] = '-';
                }

                $count_gsmt_thuc_hien_khong_dung_khong_du = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->gsmt_thuc_hien_khong_dung_khong_du;
                })->count();
                if ($count_gsmt_thuc_hien_khong_dung_khong_du > 0) {
                    $dataReturn['O'] = '1';
                } else {
                    $dataReturn['O'] = '-';
                }

                $dataReturn['P'] = $co_giay_phep_xa_thai;

                $count_khong_thuc_hien_giay_xac_nhan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->khong_thuc_hien_giay_xac_nhan;
                })->count();
                if ($count_khong_thuc_hien_giay_xac_nhan > 0) {
                    $dataReturn['Q'] = '1';
                } else {
                    $dataReturn['Q'] = '-';
                }

                $count_thuc_hien_sai_giay_xac_nhan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->thuc_hien_sai_giay_xac_nhan;
                })->count();
                if ($count_thuc_hien_sai_giay_xac_nhan > 0) {
                    $dataReturn['R'] = '1';
                } else {
                    $dataReturn['R'] = '-';
                }

                $count_nuoc_thai_vuot = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->nuoc_thai_vuot;
                })->count();
                if ($count_nuoc_thai_vuot > 0) {
                    $dataReturn['S'] = '1';
                } else {
                    $dataReturn['S'] = '-';
                }

                $count_co_he_thong_thu_gom_nuoc_thai_rieng_biet = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->co_he_thong_thu_gom_nuoc_thai_rieng_biet;
                })->count();
                if ($count_co_he_thong_thu_gom_nuoc_thai_rieng_biet > 0) {
                    $dataReturn['T'] = '1';
                } else {
                    $dataReturn['T'] = '-';
                }

                if ($item->nhomHanhViViPhams->count() > 0) {
                    $firstNhomHanhViViPham = $item->nhomHanhViViPhams[0];
                    if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'] == 0) {
                        if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'] != 0) {
                            $dataReturn['U'] = $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'];
                        }
                    } else {
                        if ($firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'] == 0) {
                            $dataReturn['U'] = $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'];
                        } else {
                            $dataReturn['U'] = $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_tu'] . '-' . $firstNhomHanhViViPham['nuoc_thai_vuot_qcvn_toi'];
                        }
                    }
                }

                $count_co_bien_phap_xu_ly_khi_thai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->co_bien_phap_xu_ly_khi_thai;
                })->count();
                if ($count_co_bien_phap_xu_ly_khi_thai > 0) {
                    $dataReturn['V'] = '1';
                } else {
                    $dataReturn['V'] = '-';
                }

                if ($item->nhomHanhViViPhams->count() > 0) {
                    $firstNhomHanhViViPham = $item->nhomHanhViViPhams[0];
                    if ($firstNhomHanhViViPham['khi_thai_vuot_qcvn_tu'] == 0) {
                        if ($firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi'] != 0) {
                            $dataReturn['W'] = $firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi'];
                        }
                    } else {
                        if ($firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi'] == 0) {
                            $dataReturn['W'] = $firstNhomHanhViViPham['khi_thai_vuot_qcvn_tu'];
                        } else {
                            $dataReturn['W'] = $firstNhomHanhViViPham['khi_thai_vuot_qcvn_tu'] . '-' . $firstNhomHanhViViPham['khi_thai_vuot_qcvn_toi'];
                        }
                    }
                }

                $count_ctrsh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->trsh_co_thu_gom;
                })->count();
                if ($count_ctrsh_co_thu_gom > 0) {
                    $dataReturn['X'] = '1';
                } else {
                    $dataReturn['X'] = '-';
                }
                $count_ctrsh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrsh_co_phan_loai;
                })->count();
                if ($count_ctrsh_co_phan_loai > 0) {
                    $dataReturn['Y'] = '1';
                } else {
                    $dataReturn['Y'] = '-';
                }
                $count_ctrsh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrsh_co_luu_tru;
                })->count();
                if ($count_ctrsh_co_luu_tru > 0) {
                    $dataReturn['Z'] = '1';
                } else {
                    $dataReturn['Z'] = '-';
                }
                $count_ctrsh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrsh_co_chuyen_giao;
                })->count();
                if ($count_ctrsh_co_chuyen_giao > 0) {
                    $dataReturn['AA'] = '1';
                } else {
                    $dataReturn['AA'] = '-';
                }

                $count_ctrcn_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_thu_gom;
                })->count();
                if ($count_ctrcn_co_thu_gom > 0) {
                    $dataReturn['AB'] = '1';
                } else {
                    $dataReturn['AB'] = '-';
                }

                $count_ctrcn_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_phan_loai;
                })->count();
                if ($count_ctrcn_co_phan_loai > 0) {
                    $dataReturn['AC'] = '1';
                } else {
                    $dataReturn['AC'] = '-';
                }

                $count_ctrcn_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_luu_tru;
                })->count();
                if ($count_ctrcn_co_luu_tru > 0) {
                    $dataReturn['AD'] = '1';
                } else {
                    $dataReturn['AD'] = '-';
                }

                $count_ctrcn_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrcn_co_chuyen_giao;
                })->count();
                if ($count_ctrcn_co_chuyen_giao > 0) {
                    $dataReturn['AE'] = '1';
                } else {
                    $dataReturn['AE'] = '-';
                }

                // ctnh
                $count_ctrnh_vi_pham_chung_tu = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_vi_pham_chung_tu;
                })->count();
                if ($count_ctrnh_vi_pham_chung_tu > 0) {
                    $dataReturn['AF'] = '1';
                } else {
                    $dataReturn['AF'] = '-';
                }
                $count_ctrnh_co_thu_gom = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_thu_gom;
                })->count();
                if ($count_ctrnh_co_thu_gom > 0) {
                    $dataReturn['AG'] = '1';
                } else {
                    $dataReturn['AG'] = '-';
                }
                $count_ctrnh_co_phan_loai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_phan_loai;
                })->count();
                if ($count_ctrnh_co_phan_loai > 0) {
                    $dataReturn['AH'] = '1';
                } else {
                    $dataReturn['AH'] = '-';
                }
                $count_ctrnh_co_luu_tru = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_luu_tru;
                })->count();
                if ($count_ctrnh_co_luu_tru > 0) {
                    $dataReturn['AI'] = '1';
                } else {
                    $dataReturn['AI'] = '-';
                }
                $count_ctrnh_co_de_lan = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_de_lan;
                })->count();
                if ($count_ctrnh_co_de_lan > 0) {
                    $dataReturn['AJ'] = '1';
                } else {
                    $dataReturn['AJ'] = '-';
                }

                $count_ctrnh_co_chuyen_giao = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_chuyen_giao;
                })->count();
                if ($count_ctrnh_co_chuyen_giao > 0) {
                    $dataReturn['AK'] = '1';
                } else {
                    $dataReturn['AK'] = '-';
                }

                $count_ctrnh_co_chon_lap = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_chon_lap;
                })->count();
                if ($count_ctrnh_co_chon_lap > 0) {
                    $dataReturn['AL'] = '1';
                } else {
                    $dataReturn['AL'] = '-';
                }

                $count_ctrnh_co_do_thai = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_do_thai;
                })->count();
                if ($count_ctrnh_co_do_thai > 0) {
                    $dataReturn['AM'] = '1';
                } else {
                    $dataReturn['AM'] = '-';
                }

                $count_ctrnh_co_giay_phep = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->ctrnh_co_giay_phep;
                })->count();
                if ($count_ctrnh_co_giay_phep > 0) {
                    $dataReturn['AN'] = '1';
                } else {
                    $dataReturn['AN'] = '-';
                }

                $count_vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong;
                })->count();
                if ($count_vi_pham_quy_dinh_ve_thu_thap_su_dung_thong_tin_moi_truong > 0) {
                    $dataReturn['AO'] = '1';
                } else {
                    $dataReturn['AO'] = '-';
                }

                $count_vi_pham_cac_quy_dinh_bao_ve_moi_truong = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->vi_pham_cac_quy_dinh_bao_ve_moi_truong;
                })->count();
                if ($count_vi_pham_cac_quy_dinh_bao_ve_moi_truong > 0) {
                    $dataReturn['AP'] = '1';
                } else {
                    $dataReturn['AP'] = '-';
                }

                $count_co_hanh_vi_can_tro_ve_bvmt = $item->nhomHanhViViPhams->filter(function ($value, $key) {
                    return (bool)$value->co_hanh_vi_can_tro_ve_bvmt;
                })->count();
                if ($count_co_hanh_vi_can_tro_ve_bvmt > 0) {
                    $dataReturn['AQ'] = '1';
                } else {
                    $dataReturn['AQ'] = '-';
                }

                $co_dinh_chi = '-';
                $item->quyetDinhXuPhats->each(function ($value, $key) use (&$co_dinh_chi) {
                    $count = $value->xuPhatBoSung->filter(function ($value, $key) {
                        return (bool)$value->dinh_chi;
                    })->count();
                    if ($count > 0) {
                        $co_dinh_chi = '1';
                    }
                });
                $dataReturn['AR'] = $co_dinh_chi;

                $count_bao_cao_khac_phuc_hau_qua_vi_pham = $item->ketQuaKhacPhucHauQuas->count();
                $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_khac_phuc = $item->ketQuaKhacPhucHauQuas->filter(function ($value, $key) {
                    return (bool)$value->da_khac_phuc;
                })->count();
                if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > 0) {
                    if ($count_bao_cao_khac_phuc_hau_qua_vi_pham_da_khac_phuc > 0) {
                        $dataReturn['AS'] = '1';
                    } else {
                        $dataReturn['AS'] = '-';
                    }
                    if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_khac_phuc) {
                        $dataReturn['AT'] = '1';
                    } else {
                        $dataReturn['AT'] = '-';
                    }
                } else {
                    $dataReturn['AS'] = '-';
                    $dataReturn['AT'] = '-';
                }

                $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao = 0;
                $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao = $item->ketQuaKhacPhucHauQuas->filter(function ($value, $key) {
                    return (bool)$value->da_bao_cao;
                })->count();
                if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > 0) {
                    if ($count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao > 0) {
                        $dataReturn['AU'] = '1';
                    } else {
                        $dataReturn['AU'] = '-';
                    }
                    if ($count_bao_cao_khac_phuc_hau_qua_vi_pham > $count_bao_cao_khac_phuc_hau_qua_vi_pham_da_bao_cao) {
                        $dataReturn['AV'] = '1';
                    } else {
                        $dataReturn['AV'] = '-';
                    }
                } else {
                    $dataReturn['AU'] = '-';
                    $dataReturn['AV'] = '-';
                }
                return $dataReturn;
            });
        }
        return $dataReturn;
    }
    public function getPreview(Request $request)
    {
        return response()->json([
            'message' => __('message.success'),
            // 'data' => $this->_getData($request),
            'data' => $this->_getDataNew($request),
        ], 200, []);
    }
    public function _getDataNew($request, $isLimit = false)
    {
        $dataReturn = [];
        $query = \App\Organization::query();
        $search_start_date = $request->get('search_start_date');
        $search_end_date = $request->get('search_end_date');
        if (isset($search_start_date)) {
            $search_start_date = Carbon::createFromFormat(config('app.format_date'), $search_start_date)->startOfDay();
        }
        if (isset($search_end_date)) {
            $search_end_date = Carbon::createFromFormat(config('app.format_date'), $search_end_date)->endOfDay();
        } else {
            $search_end_date = Carbon::now()->endOfDay();
        }
        $info = $request->all();
        $tinhThanhs = $this->getDataByName(\App\TinhThanh::class);
        $query->whereHas('ketQuaThanhTras', function ($query) use ($search_end_date) {
            $query->whereHas('quyetDinhThanhTra', function ($query) use ($search_end_date) {
                $query->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
            });
        });
        if ($search_start_date) {
            $query->whereHas('ketQuaThanhTras', function ($query) use ($search_start_date) {
                $query->whereHas('quyetDinhThanhTra', function ($query) use ($search_start_date) {
                    $query->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
                });
            });
        }
        $this->getQueryOrganization($info, $query, $tinhThanhs);
        $organizations = $query->select('id')->get();

        // query theo doan thanh tra
        $queryQuyetDinhThanhTra = \App\QuyetDinhThanhTra::query();
        if ($search_start_date) {
            $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '>=', $search_start_date);
        }
        $queryQuyetDinhThanhTra->where('ngay_ra_quyet_dinh', '<=', $search_end_date);
        $queryQuyetDinhThanhTra->whereHas('ketQuaThanhTras', function ($query) use ($organizations) {
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryQuyetDinhThanhTra($queryQuyetDinhThanhTra);
        $quyetDinhThanhTras = $queryQuyetDinhThanhTra->get();

        // ket qua thanh tra
        $queryKetQuaThanhTra = \App\KetQuaThanhTra::query();
        $queryKetQuaThanhTra->where(function ($query) use ($quyetDinhThanhTras, $organizations) {
            $query->whereIn('quyet_dinh_thanh_tra_id', $quyetDinhThanhTras->pluck('id'));
            $query->whereIn('organization_id', $organizations->pluck('id'));
        });
        $this->getQueryKetQuaThanhTra($info, $queryKetQuaThanhTra);

        $queryKetQuaThanhTra = $queryKetQuaThanhTra->with([
            'quyetDinhThanhTra',
            'ketQuaThanhTraChungs',
            'ketQuaThanhTraChungs.khuCongNghiep',
            'quyetDinhXuPhats',
            'quyetDinhXuPhats.xuPhatChinh',
            'quyetDinhXuPhats.xuPhatBoSung',
            'quyetDinhXuPhats.bienPhapKhacPhucHauQua',
            'ketQuaThanhTraChungs.khuCongNghiep.quanHuyen',
            'ketQuaThanhTraChungs.khuCongNghiep.tinhThanh',
            'ketQuaThanhTraChungs.ketQuaThanhTraChungLoaiHinhHoatDongs'
        ]);
        if ($isLimit) {
            $queryKetQuaThanhTra->take(20);
        }
        $ketQuaThanhTrasSorted = $queryKetQuaThanhTra->orderBy('organization_id')->get();
        $organizationIds = [];
        foreach ($ketQuaThanhTrasSorted as $it) {
            $organizationIds[] = $it->organization_id;
        }
        $coSoSanXuat = CoSoSanXuat::whereIn('organization_id', $organizationIds)->with([
            'loaiKhuCongNghiep',
            'phuongXas',
            'quanHuyens',
            'tinhThanhs',
            'organization',
            'organization.chuDauTu',
            'organization.chuDauTu.tinhThanh',
            'organization.chuDauTu.quanHuyen',
            'loaiHinhONhiem',
            'tinhThanh:id,ten',
            'coQuanKinhDoanh',
            'quanHuyen:id,ten',
            'luuVucSong',
            'loaiCoSo',
            'loaiNganhNghe',
            'organization.ketQuaThanhTras',
            'organization.ketQuaThanhTras.quyetDinhThanhTra',
            'organization.ketQuaThanhTras.quyetDinhThanhTra.coQuanQuyetDinh',
            'organization.ketQuaThanhTras.quyetDinhThanhTra.coQuanThucHien',
            'organization.ketQuaThanhTras.nhomHanhViViPhams',
            'organization.ketQuaThanhTras.nhomHanhViViPhams.chiTietViPhamXaThais',
            'organization.ketQuaThanhTras.nhomHanhViViPhams.chiTietViPhamXaThais.thongSo',
            'organization.ketQuaThanhTras.ketQuaThanhTraChungs',
            'organization.ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraNuocThais',
            'organization.ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs',
            'organization.ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.coQuanToChuc',
            'organization.ketQuaThanhTras.ketQuaThanhTraChungs.ketQuaThanhTraThuTucHanhChinhs.loaiThuTuc',
            'organization.ketQuaThanhTras.ketQuaKhacPhucHauQuas',
            'organization.ketQuaThanhTras.quyetDinhXuPhats',
            'organization.ketQuaThanhTras.quyetDinhXuPhats.coQuanQuyetDinhXuPhat',
            'organization.ketQuaThanhTras.quyetDinhXuPhats.xuPhatBoSung',
            'organization.ketQuaThanhTras.quyetDinhXuPhats.xuPhatChinh',
            'organization.ketQuaThanhTras.quyetDinhXuPhats.bienPhapKhacPhucHauQua',
            'ketQuaThanhTraChungs',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanSinhHoats',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiRanCNTT',
            'ketQuaThanhTraChungs.ketQuaThanhTraChatThaiNguyHai',
        ])->get();
        if ($request->get('maus_doi_tuong_thanh_tra_co_ban')) {
            $dataReturn['DoiTuongThanhTraCoBan'] = $coSoSanXuat->map(function ($item, $key) {
                $loaiHinhONhiem = collect();
                if ($item->loaiHinhONhiem && $item->loaiHinhONhiem->count() > 0 && gettype($item->loaiHinhONhiem) === 'object') {
                    foreach ($item->loaiHinhONhiem as $value) {
                        if (gettype($value) === 'object') {
                            $loaiHinhONhiem->push($value->ten);
                        }
                    }
                }
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }
                $namThanhTra = collect();
                if ($item->organization->ketQuaThanhTras && gettype($item->organization->ketQuaThanhTras) === 'object' && $item->organization->ketQuaThanhTras->count() > 0) {
                    foreach ($item->organization->ketQuaThanhTras as $value) {
                        if (gettype($value) === 'object' &&  $value->ngay_thanh_tra) {
                            $namThanhTra->push(Carbon::createFromFormat('d/m/Y', $value->ngay_thanh_tra)->year);
                        }
                    }
                }
                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }

                return [
                    'A' => $key + 1,
                    'B' => $item->ma_dinh_danh,
                    'C' => $item->ten,
                    'D' => $item->dia_chi_hoat_dong,
                    'E' => $phuongXa,
                    'F' => $quanHuyen,
                    'G' => $tinhThanh,
                    'H' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ma_dinh_danh : '',
                    'I' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ten : '',
                    'J' => $item->organization->chuDauTu ? $item->organization->chuDauTu->dia_chi : '',
                    'K' => $item->organization->chuDauTu ? $item->organization->chuDauTu->xa_phuong : '',
                    'L' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->quanHuyen ? $item->organization->chuDauTu->quanHuyen->ten : '') : '',
                    'M' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->tinhThanh ? $item->organization->chuDauTu->tinhThanh->ten : '') : '',
                    'N' => isset($item->loaiKhuCongNghiep) && $item->loaiKhuCongNghiep ?  $item->loaiKhuCongNghiep->ten : '',
                    'O' => $loaiHinhONhiem->implode(", "),
                    'P' => $loaiNganhNghe->implode(", "),
                    'Q' => $namThanhTra->unique()->implode(", "),
                    'R' => $item->luuVucSong ? ($item->luuVucSong->ten ? $item->luuVucSong->ten : '') : '',
                ];
            });
        }
        $thongtinchung = collect();
        foreach ($coSoSanXuat as $item) {
            if ($item->organization->ketQuaThanhTras && gettype($item->organization->ketQuaThanhTras) === 'object' && $item->organization->ketQuaThanhTras->count() > 0) {
                foreach ($item->organization->ketQuaThanhTras as $value) {
                    if (gettype($value) === 'object') {
                        $tt = clone $item;
                        // $tt->organization->ketQuaThanhTras = $value;
                        $tt->ketQuaThanhTras = $value;
                        $thongtinchung->push($tt);
                    }
                }
            }
        }
        if ($request->get('maus_thong_tin_chung')) {
            $dataReturn['ThongTinChung'] = $thongtinchung->map(function ($item, $key) {
                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }
                $loaiHinhONhiem = collect();
                if ($item->loaiHinhONhiem && $item->loaiHinhONhiem->count() > 0 && gettype($item->loaiHinhONhiem) === 'object') {
                    foreach ($item->loaiHinhONhiem as $value) {
                        if (gettype($value) === 'object') {
                            $loaiHinhONhiem->push($value->ten);
                        }
                    }
                }
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }
                return [
                    'A' => $key + 1,
                    'B' => $item->ma_dinh_danh,
                    'C' => $item->ten,
                    'D' => $item->dia_chi_hoat_dong,
                    'E' => $phuongXa,
                    'F' => $quanHuyen,
                    'G' => $tinhThanh,
                    'H' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ma_dinh_danh : '',
                    'I' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ten : '',
                    'J' => $item->organization->chuDauTu ? $item->organization->chuDauTu->dia_chi : '',
                    'K' => $item->organization->chuDauTu ? $item->organization->chuDauTu->xa_phuong : '',
                    'L' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->quanHuyen ? $item->organization->chuDauTu->quanHuyen->ten : '') : '',
                    'M' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->tinhThanh ? $item->organization->chuDauTu->tinhThanh->ten : '') : '',
                    'N' => $item->so_giay_chung_nhan_kinh_doanh,
                    'O' => $item->ngay_cap_giay_chung_nhan_kinh_doanh,
                    'P' => $item->coQuanKinhDoanh ? $item->coQuanKinhDoanh->ten : '',
                    'Q' => isset($item->loaiKhuCongNghiep) && $item->loaiKhuCongNghiep ?  $item->loaiKhuCongNghiep->ten : '',
                    'R' => $loaiHinhONhiem->implode(", "),
                    'S' => $loaiNganhNghe->implode(", "),
                    'T' => $item->dien_tich,
                    'U' => $item->luong_nuoc_su_dung,
                    'V' => $item->nguon_nuoc_su_dung,
                    'W' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->so_quyet_dinh : '') : '',
                    'X' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->ngay_ra_quyet_dinh : '') : '',
                    'Y' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? ($item->ketQuaThanhTras->quyetDinhThanhTra->coQuanQuyetDinh ? $item->ketQuaThanhTras->quyetDinhThanhTra->coQuanQuyetDinh->ten  : '') : '') : '',
                    'Z' => $item->ketQuaThanhTras ? $item->ketQuaThanhTras->so_quyet_dinh : '',
                    'AA' => $item->ketQuaThanhTras ? $item->ketQuaThanhTras->ngay_thanh_tra : '',
                    'AB' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? ($item->ketQuaThanhTras->quyetDinhThanhTra->coQuanThucHien ? $item->ketQuaThanhTras->quyetDinhThanhTra->coQuanThucHien->ten  : '') : '') : '',
                ];
            });
        }

        $xaThai = collect();
        $thongtinchung->map(function ($item, $key) use ($xaThai) {
            if ($item->ketQuaThanhTraChungs && gettype($item->ketQuaThanhTraChungs) === 'object' && $item->ketQuaThanhTraChungs->count() > 0) {
                foreach ($item->ketQuaThanhTraChungs as $value) {
                    if (gettype($value) === 'object' && $value->ketQuaThanhTraNuocThais->count() > 0) {
                        $xt = clone $item;
                        $xt->ketQuaThanhTraChungs = $value;
                        $xaThai->push($xt);
                    }
                }
            }
        });
        if ($request->get('maus_xa_thai')) {
            $dataReturn['XaThai'] = $xaThai->map(function ($item, $key) {
                $luongNuocThai = collect();
                $congSuat = collect();
                $ketQuaThanhTraNuocThai = collect();
                if ($item->ketQuaThanhTraChungs->ketQuaThanhTraNuocThais)
                    $item->ketQuaThanhTraChungs->ketQuaThanhTraNuocThais->each(function ($value, $key) use ($luongNuocThai, $congSuat, $ketQuaThanhTraNuocThai) {
                        $luongNuocThai->push($value->luu_luong);
                        $congSuat->push($value->cong_suat_he_thong_xu_ly);
                        $ketQuaThanhTraNuocThai->push($value);
                    });
                $soLanVuot = collect();
                $thongSoVuot = collect();
                $tongThai = collect();
                $item->ketQuaThanhTras->nhomHanhViViPhams->each(function ($item) use ($thongSoVuot, $soLanVuot, $tongThai) {
                    $item->chiTietViPhamXaThais->each(function ($chitiet) use ($thongSoVuot, $soLanVuot, $tongThai) {
                        if (isset($chitiet->thongSo)) {
                            $thongSoVuot->push($chitiet->thongSo->ten);
                        } else {
                            $thongSoVuot->push('');
                        }
                        $soLanVuot->push($chitiet->so_lan_vuot ?? 0);
                        $tongThai->push($chitiet->ket_qua);
                    });
                });
                $loaiHinhONhiem = collect();
                if ($item->loaiHinhONhiem && $item->loaiHinhONhiem->count() > 0 && gettype($item->loaiHinhONhiem) === 'object') {
                    foreach ($item->loaiHinhONhiem as $value) {
                        if (gettype($value) === 'object') {
                            $loaiHinhONhiem->push($value->ten);
                        }
                    }
                }
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }

                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }
                return [
                    'A' => $key + 1,
                    'B' => $item->ma_dinh_danh,
                    'C' => $item->ten,
                    'D' => $item->dia_chi_hoat_dong,
                    'E' => $phuongXa,
                    'F' => $quanHuyen,
                    'G' => $tinhThanh,
                    'H' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ma_dinh_danh : '',
                    'I' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ten : '',
                    'J' => $item->organization->chuDauTu ? $item->organization->chuDauTu->dia_chi : '',
                    'K' => $item->organization->chuDauTu ? $item->organization->chuDauTu->xa_phuong : '',
                    'L' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->quanHuyen ? $item->organization->chuDauTu->quanHuyen->ten : '') : '',
                    'M' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->tinhThanh ? $item->organization->chuDauTu->tinhThanh->ten : '') : '',
                    'N' => $item->loaiKhuCongNghiep ?  $item->loaiKhuCongNghiep->ten : '',
                    'O' => $loaiHinhONhiem->implode(", "),
                    'P' => $loaiNganhNghe->implode(", "),
                    'Q' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->so_quyet_dinh : '') : '',
                    'R' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->ngay_ra_quyet_dinh : '') : '',
                    'S' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? ($item->ketQuaThanhTras->quyetDinhThanhTra->coQuanQuyetDinh ? $item->ketQuaThanhTras->quyetDinhThanhTra->coQuanQuyetDinh->ten  : '') : '') : '',
                    'T' => $item->ketQuaThanhTras ? $item->ketQuaThanhTras->so_quyet_dinh : '',
                    'U' => $item->ketQuaThanhTras ? $item->ketQuaThanhTras->ngay_thanh_tra : '',
                    'V' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? ($item->ketQuaThanhTras->quyetDinhThanhTra->coQuanThucHien ? $item->ketQuaThanhTras->quyetDinhThanhTra->coQuanThucHien->ten  : '') : '') : '',
                    'W' => $item->luuVucSong ? $item->luuVucSong->ten : '',
                    'X' => $luongNuocThai->sum(),
                    'Y' => $congSuat->sum(),
                    'Z' => $ketQuaThanhTraNuocThai->pluck('nguon_tiep_nhan')->implode("\n"),
                    'AA' => $ketQuaThanhTraNuocThai->pluck('thong_so_nuoc_thai_vuot_chuan')->implode("\n"),
                    'AB' => $thongSoVuot->implode("\n"),
                    'AC' => $soLanVuot->sum(),
                ];
            });
        }
        $thuTucHanhChinh = collect();
        $xaThai->map(function ($item) use ($thuTucHanhChinh, $request) {
            if ($item->ketQuaThanhTraChungs->ketQuaThanhTraThuTucHanhChinhs && gettype($item->ketQuaThanhTraChungs->ketQuaThanhTraThuTucHanhChinhs) === 'object' && $item->ketQuaThanhTraChungs->ketQuaThanhTraThuTucHanhChinhs->count() > 0) {
                $ketQuaTTHC = clone $item->ketQuaThanhTraChungs->ketQuaThanhTraThuTucHanhChinhs;
                if ($request->get('search_loai_van_ban')) {
                    $search_loai_van_ban = $request->get('search_loai_van_ban');
                    $arrayLoaiVanBan = explode(' ', $search_loai_van_ban);
                    $ketQuaTTHC =  $ketQuaTTHC->filter(function ($kq) use ($arrayLoaiVanBan) {
                        $check = false;
                        foreach ($arrayLoaiVanBan as $k => $vanBan) {
                            $vanBan = json_decode($vanBan);
                            $vanBan = (array)$vanBan;
                            $key = array_keys($vanBan)[0];
                            $filter = $kq->loaiThuTuc->id == $key;
                            if ($vanBan[$key]) {
                                $filter = $filter && in_array($kq->co_quan_phe_duyet_id, explode(',', $vanBan[$key]));
                            }
                            $check = $check || $filter;
                        }
                        return $check;
                    });
                }
                foreach ($ketQuaTTHC as $value) {
                    if (gettype($value) == 'object') {
                        $hc = clone $item;
                        $hc->ketQuaThanhTraThuTucHanhChinhs = $value;
                        $thuTucHanhChinh->push($hc);
                        // $thuTucHanhChinh->push($item);
                        // $thuTucHanhChinh[$thuTucHanhChinh->count() - 1]->ketQuaThanhTraChungs->ketQuaThanhTraThuTucHanhChinhs = $value;
                    }
                };
            }
        });
        $thuTucs = $thuTucHanhChinh->filter(function ($item) {
            $loaiThuTuc = $item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai;
            return in_array($loaiThuTuc, ['C_LoaiVanBanDTM', 'C_LoaiGiayPhepMoiTruong']);
        })->values();

        if ($request->get('maus_thu_tuc_hanh_chinh')) {
            $dataReturn['ThuTucHanhChinh'] = $thuTucs->map(function ($item, $key) {
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }
                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }

                return [
                    'A' => $key + 1,
                    'B' => $item->ma_dinh_danh,
                    'C' => $item->ten,
                    'D' => $item->dia_chi_hoat_dong,
                    'E' => $phuongXa,
                    'F' => $quanHuyen,
                    'G' => $tinhThanh,
                    'H' =>  $item->loaiKhuCongNghiep ?  $item->loaiKhuCongNghiep->ten : '',
                    'I' => $loaiNganhNghe->implode(", "),
                    'J' => $item->ketQuaThanhTraThuTucHanhChinhs->coQuanToChuc ? ($item->ketQuaThanhTraThuTucHanhChinhs->coQuanToChuc->cap_bo ? 'x' : '') : '',
                    'K' => $item->ketQuaThanhTraThuTucHanhChinhs->coQuanToChuc ? ($item->ketQuaThanhTraThuTucHanhChinhs->coQuanToChuc->cap_bo ? '' : 'x') : '',
                    'L' => ($item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai !== 'C_LoaiVanBanDTM') ? '' : $item->ketQuaThanhTraThuTucHanhChinhs->so_quyet_dinh_phe_duyet,
                    'M' => ($item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai !== 'C_LoaiVanBanDTM') ? '' : $item->ketQuaThanhTraThuTucHanhChinhs->thoi_gian_phe_duyet,
                    'N' => ($item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai !== 'C_LoaiVanBanDTM') ? '' : $item->ketQuaThanhTraThuTucHanhChinhs->coQuanToChuc->ten,
                    'O' => ($item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai == 'C_LoaiGiayPhepMoiTruong') ? $item->ketQuaThanhTraThuTucHanhChinhs->so_quyet_dinh_phe_duyet : '',
                    'P' => ($item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai == 'C_LoaiGiayPhepMoiTruong') ? $item->ketQuaThanhTraThuTucHanhChinhs->thoi_gian_phe_duyet : '',
                    'Q' => ($item->ketQuaThanhTraThuTucHanhChinhs->loaiThuTuc->phan_loai == 'C_LoaiGiayPhepMoiTruong') ? $item->ketQuaThanhTraThuTucHanhChinhs->coQuanToChuc->ten : '',
                ];
            });
        }
        $theoDoiThanhTra = collect();
        $thongtinchung->map(function ($item) use ($theoDoiThanhTra) {
            if ($item->ketQuaThanhTras->quyetDinhXuPhats && gettype($item->ketQuaThanhTras->quyetDinhXuPhats) == 'object' && $item->ketQuaThanhTras->quyetDinhXuPhats->count() > 0) {
                foreach ($item->ketQuaThanhTras->quyetDinhXuPhats as $value) {
                    if (gettype($value) == 'object') {
                        $td = clone $item;
                        $td->quyetDinhXuPhats = $value;
                        $theoDoiThanhTra->push($td);
                        // $theoDoiThanhTra->push($item);
                        // $theoDoiThanhTra[$theoDoiThanhTra->count() - 1]->ketQuaThanhTras->quyetDinhXuPhats = $value;
                    }
                }
            } else $theoDoiThanhTra->push($item);
        });
        if ($request->get('maus_theo_doi_thanh_tra')) {
            $theoDoiTT = $this->filterKetQuaKhacPhuc($info, $theoDoiThanhTra);
            $dataReturn['TheoDoiThanhTra'] = $theoDoiTT->map(function ($item, $key) {
                $noiDungViPham = collect();
                $tongTienViPham = collect();
                $noiDungSuPhatBoSung = collect();
                $bienPhapKhacPhucHauQua = collect();
                $soTienChungCau = collect();

                if ($item->quyetDinhXuPhats) {
                    $item->quyetDinhXuPhats->xuPhatChinh->each(function ($value) use ($tongTienViPham, $noiDungViPham) {
                        $noiDungViPham->push($value->noi_dung_vi_pham);
                        $tongTienViPham->push($value->so_tien_xu_phat_chinh);
                    });
                    $item->quyetDinhXuPhats->xuPhatBoSung->each(function ($value) use ($noiDungSuPhatBoSung) {
                        $noiDungSuPhatBoSung->push($value->noi_dung_xu_phat_bo_sung);
                    });
                    $item->quyetDinhXuPhats->bienPhapKhacPhucHauQua->each(function ($value) use ($bienPhapKhacPhucHauQua, $soTienChungCau) {
                        $bienPhapKhacPhucHauQua->push($value->noi_dung_bien_phap_khac_phuc_hau_qua);
                        $soTienChungCau->push($value->so_tien_chung_cau);
                    });
                }

                $daNopPhat = [];
                $nopMotPhan = [];
                $noiDungDaKhacPhuc = collect();
                $noiDungChuaKhacPhuc = collect();
                $baoCao = [];
                foreach ($item->ketQuaThanhTras->ketQuaKhacPhucHauQuas as $value) {
                    if ($value->da_nop_phat) {
                        $daNopPhat[] = $value->da_nop_phat;
                    } else if ($value->nop_mot_phan) {
                        $nopMotPhan[] = $value->nop_mot_phan;
                    }
                    $noiDungDaKhacPhuc->push($value->noi_dung_da_khac_phuc);
                    $noiDungChuaKhacPhuc->push($value->noi_dung_chua_khac_phuc);
                    if ($value->da_bao_cao) {
                        if ($value->so_hieu_bao_cao) {
                            $baoCao[] = ($value->so_hieu_bao_cao . "(" . $value->ngay_ban_hanh_bao_cao . ")\n" . $value->tinh_trang_bao_cao);
                        }
                        if (!in_array('Đã báo cáo', $baoCao)) {
                            $baoCao[] = 'Đã báo cáo';
                        }
                    }
                };

                $loaiHinhONhiem = collect();
                if ($item->loaiHinhONhiem && $item->loaiHinhONhiem->count() > 0 && gettype($item->loaiHinhONhiem) === 'object') {
                    foreach ($item->loaiHinhONhiem as $value) {
                        if (gettype($value) === 'object') {
                            $loaiHinhONhiem->push($value->ten);
                        }
                    }
                }
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }
                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }

                $ghiChu = $item->ketQuaThanhTraChungs->flatMap(function ($ketQuaChung) {
                    return $ketQuaChung->ketQuaThanhTraChungLoaiHinhHoatDongs->pluck('ghi_chu');
                })->implode(", ");

                return [
                    'A' => $key + 1,
                    'B' => $item->ma_dinh_danh,
                    'C' => $item->ten,
                    'D' => $item->dia_chi_hoat_dong,
                    'E' => $phuongXa,
                    'F' => $quanHuyen,
                    'G' => $tinhThanh,
                    'H' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ma_dinh_danh : '',
                    'I' => $item->organization->chuDauTu ? $item->organization->chuDauTu->ten : '',
                    'J' => $item->organization->chuDauTu ? $item->organization->chuDauTu->dia_chi : '',
                    'K' => $item->organization->chuDauTu ? $item->organization->chuDauTu->xa_phuong : '',
                    'L' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->quanHuyen ? $item->organization->chuDauTu->quanHuyen->ten : '') : '',
                    'M' => $item->organization->chuDauTu ? ($item->organization->chuDauTu->tinhThanh ? $item->organization->chuDauTu->tinhThanh->ten : '') : '',
                    'N' => $item->so_giay_chung_nhan_kinh_doanh,
                    'O' => $item->ngay_cap_giay_chung_nhan_kinh_doanh,
                    'P' => $item->coQuanKinhDoanh ? $item->coQuanKinhDoanh->ten : '',
                    'Q' => $item->loaiKhuCongNghiep ?  $item->loaiKhuCongNghiep->ten : '',
                    'R' => $loaiHinhONhiem->implode(", "),
                    'S' => $loaiNganhNghe->implode(", "),
                    'T' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->so_quyet_dinh : '') : '',
                    'U' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->ngay_ra_quyet_dinh : '') : '',
                    'V' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? ($item->ketQuaThanhTras->quyetDinhThanhTra->coQuanQuyetDinh ? $item->ketQuaThanhTras->quyetDinhThanhTra->coQuanQuyetDinh->ten  : '') : '') : '',
                    'W' => $item->ketQuaThanhTras ? $item->ketQuaThanhTras->so_quyet_dinh : '',
                    'X' => $item->ketQuaThanhTras ? $item->ketQuaThanhTras->ngay_thanh_tra : '',
                    'Y' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? ($item->ketQuaThanhTras->quyetDinhThanhTra->coQuanThucHien ? $item->ketQuaThanhTras->quyetDinhThanhTra->coQuanThucHien->ten  : '') : '') : '',
                    'Z' => $item->quyetDinhXuPhats ? $item->quyetDinhXuPhats->so_quyet_dinh : '',
                    'AA' => $item->quyetDinhXuPhats ? $item->quyetDinhXuPhats->thoi_gian_ban_hanh : '',
                    'AB' => $item->quyetDinhXuPhats ? ($item->quyetDinhXuPhats->coQuanQuyetDinhXuPhat ? $item->quyetDinhXuPhats->coQuanQuyetDinhXuPhat->ten : '') : '',
                    'AC' => $noiDungViPham->implode("\n"),
                    'AD' => $tongTienViPham->sum() + $soTienChungCau->sum(),
                    'AE' => $noiDungSuPhatBoSung->implode("\n"),
                    'AF' => $bienPhapKhacPhucHauQua->implode("\n"),
                    'AG' => count($daNopPhat) > 0 ? 'Đã nộp phạt' : (count($nopMotPhan) > 0 ? 'Nộp một phần' : ''),
                    'AH' => $noiDungDaKhacPhuc->implode("\n"),
                    'AI' => $noiDungChuaKhacPhuc->implode("\n"),
                    'AJ' => implode(", ", $baoCao),
                    'AK' => $item->ketQuaThanhTras->mo_ta,
                    'AL' => $ghiChu
                ];
            });
        }
        $ketQuaThanhTraChung = collect();
        $queryKetQuaThanhTra->get()->map(function ($item) use ($ketQuaThanhTraChung) {
            if ($item->ketQuaThanhTraChungs && gettype($item->ketQuaThanhTraChungs) == 'object' && $item->ketQuaThanhTraChungs->count() > 0) {
                foreach ($item->ketQuaThanhTraChungs as $value) {
                    if (getType($value) == 'object') {
                        $ketQuaThanhTraChung->push($item);
                        $ketQuaThanhTraChung[$ketQuaThanhTraChung->count() - 1]->ketQuaThanhTraChungs = $value;
                    }
                }
            }
        });

        if ($request->get('maus_khu_sx_dv_tap_trung')) {
            $loaiKhuCongNghiep = ['02', 'CCN'];
            $khuSXDV = $theoDoiThanhTra->filter(function ($item) use ($loaiKhuCongNghiep) {
                return in_array($item->loai_khu_cong_nghiep, $loaiKhuCongNghiep);
            })->values();
            $dataReturn['KhuSxDvTapTrung'] = $khuSXDV->map(function ($item, $key) use ($loaiKhuCongNghiep) {
                $noiDungViPham = collect();
                $tongTienViPham = collect();
                $noiDungSuPhatBoSung = collect();
                $bienPhapKhacPhucHauQua = collect();
                $bienPhapKhacPhucHauQua = collect();
                $tongTienViPham = collect();
                $noiDungViPham = collect();
                $noiDungSuPhatBoSung = collect();

                if ($item->quyetDinhXuPhats) {
                    $item->quyetDinhXuPhats->xuPhatChinh->each(function ($value) use ($tongTienViPham, $noiDungViPham) {
                        $noiDungViPham->push($value->noi_dung_vi_pham);
                        $tongTienViPham->push($value->so_tien_xu_phat_chinh);
                    });
                    $item->quyetDinhXuPhats->xuPhatBoSung->each(function ($value) use ($noiDungSuPhatBoSung) {
                        $noiDungSuPhatBoSung->push($value->noi_dung_xu_phat_bo_sung);
                    });
                    $item->quyetDinhXuPhats->bienPhapKhacPhucHauQua->each(function ($value) use ($bienPhapKhacPhucHauQua) {
                        $bienPhapKhacPhucHauQua->push($value->noi_dung_bien_phap_khac_phuc_hau_qua);
                    });
                    $item->quyetDinhXuPhats->xuPhatChinh->each(function ($value) use ($tongTienViPham, $noiDungViPham) {
                        $noiDungViPham->push($value->noi_dung_vi_pham);
                        $tongTienViPham->push($value->so_tien_xu_phat_chinh);
                    });
                    $item->quyetDinhXuPhats->xuPhatBoSung->each(function ($value) use ($noiDungSuPhatBoSung) {
                        $noiDungSuPhatBoSung->push($value->noi_dung_xu_phat_bo_sung);
                    });
                    $item->quyetDinhXuPhats->bienPhapKhacPhucHauQua->each(function ($value) use ($bienPhapKhacPhucHauQua) {
                        $bienPhapKhacPhucHauQua->push($value->noi_dung_bien_phap_khac_phuc_hau_qua);
                    });
                }


                $daNopPhat = collect();
                $nopMotPhan = collect();
                $noiDungDaKhacPhuc = collect();
                $noiDungChuaKhacPhuc = collect();
                $baoCao = collect();

                $item->ketQuaThanhTras->ketQuaKhacPhucHauQuas->each(function ($value) use ($daNopPhat, $nopMotPhan, $noiDungDaKhacPhuc, $noiDungChuaKhacPhuc, $baoCao) {
                    if ($value->da_nop_phat) {
                        $daNopPhat->push($value->da_nop_phat);
                    } else if ($value->nop_mot_phan) {
                        $nopMotPhan->push($value->nop_mot_phan);
                    }
                    $noiDungDaKhacPhuc->push($value->noi_dung_da_khac_phuc);
                    $noiDungChuaKhacPhuc->push($value->noi_dung_chua_khac_phuc);
                    if ($value->da_bao_cao) {
                        $baoCao->push($value->so_hieu_bao_cao . "(" . $value->ngay_ban_hanh_bao_cao . ")\n" . $value->tinh_trang_bao_cao);
                    }
                });

                $loaiHinhONhiem = collect();
                if ($item->loaiHinhONhiem && $item->loaiHinhONhiem->count() > 0 && gettype($item->loaiHinhONhiem) === 'object') {
                    foreach ($item->loaiHinhONhiem as $value) {
                        if (gettype($value) === 'object') {
                            $loaiHinhONhiem->push($value->ten);
                        }
                    }
                }
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }

                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }

                if (in_array($item->loai_khu_cong_nghiep, $loaiKhuCongNghiep)) {
                    return [
                        'A' => $key + 1,
                        'B' => $item->ma_dinh_danh,
                        'C' => $item->ten,
                        'D' => $item->dia_chi_hoat_dong,
                        'E' => $phuongXa,
                        'F' => $quanHuyen,
                        'G' => $tinhThanh,
                        'H' => $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->quyetDinhThanhTra ? $item->ketQuaThanhTras->quyetDinhThanhTra->so_quyet_dinh : '') : '',
                        'I' => $item->quyetDinhXuPhats ? $item->quyetDinhXuPhats->so_quyet_dinh : '',
                        'J' =>  $item->ketQuaThanhTras && $item->ketQuaThanhTras->quyetDinhThanhTra && $item->ketQuaThanhTras->quyetDinhThanhTra->ngay_ra_quyet_dinh ?  Carbon::createFromFormat('d/m/Y', $item->ketQuaThanhTras->quyetDinhThanhTra->ngay_ra_quyet_dinh)->year : '',
                        'K' =>  $item->quyetDinhXuPhats && $item->quyetDinhXuPhats->thoi_gian_ban_hanh ? Carbon::createFromFormat('d/m/Y', $item->quyetDinhXuPhats->thoi_gian_ban_hanh)->year : '',
                        'L' => $tongTienViPham->sum(),
                        'M' => $noiDungViPham->implode("\n"),
                        'N' => $noiDungSuPhatBoSung->implode("\n"),
                        'O' => $bienPhapKhacPhucHauQua->implode("\n"),
                    ];
                }
            });
        }
        $chatThaiRanChung = collect();
        $coSoSanXuat->map(function ($item) use ($chatThaiRanChung) {
            if ($item->ketQuaThanhTraChungs && gettype($item->ketQuaThanhTraChungs) == 'object' && $item->ketQuaThanhTraChungs->count() > 0) {
                foreach ($item->ketQuaThanhTraChungs as $value) {
                    $chatThaiRanChung->push($item);
                    $chatThaiRanChung[$chatThaiRanChung->count() - 1]->ketQuaThanhTraChungs = $value;
                }
            }
        });
        $chatThaiRan = collect();
        $chatThaiRanChung->map(function ($item) use ($chatThaiRan) {
            if (
                $item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiRanSinhHoats->count() > 0
                || $item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiRanCNTT->count() > 0
                || $item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiNguyHai->count() > 0
            ) {
                $chatThaiRan->push($item);
            }
        });

        $chatThai = $chatThaiRan->filter(function ($item) {
            $chatThaiSinhHoat = 0;
            $chatThaiCn = 0;
            $chatThaiNguyHai = 0;
            foreach ($item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiRanCNTT as $value) {
                $chatThaiCn = $chatThaiCn + round($value->khoi_luong_phat_sinh);
            }

            foreach ($item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiRanSinhHoats as $value) {
                $chatThaiSinhHoat = $chatThaiSinhHoat + round($value->khoi_luong_phat_sinh);
            }
            foreach ($item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiNguyHai as $value) {
                $chatThaiNguyHai = $chatThaiNguyHai + round($value->khoi_luong_phat_sinh_thuc_te);
            }
            return $chatThaiSinhHoat > 0 ||  $chatThaiCn > 0 || $chatThaiNguyHai > 0;
        })->values();

        if ($request->get('maus_chat_thai_ran')) {
            $dataReturn['ChatThaiRan'] = $chatThai->map(function ($item, $key) {
                $chatThaiSinhHoat = collect();
                $chatThaiCn = collect();
                $chatThaiNguyHai = collect();
                $item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiRanSinhHoats->each(function ($value) use ($chatThaiSinhHoat) {
                    $chatThaiSinhHoat->push(round($value->khoi_luong_phat_sinh, 2));
                });
                $item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiRanCNTT->each(function ($value) use ($chatThaiCn) {
                    $chatThaiCn->push(round($value->khoi_luong_phat_sinh, 2));
                });
                $item->ketQuaThanhTraChungs->ketQuaThanhTraChatThaiNguyHai->each(function ($value) use ($chatThaiNguyHai) {
                    $chatThaiNguyHai->push(round($value->khoi_luong_phat_sinh_thuc_te, 2));
                });
                $loaiNganhNghe = collect();
                if ($item->loaiNganhNghe && $item->loaiNganhNghe->count() > 0 && gettype($item->loaiNganhNghe) === 'object') {
                    foreach ($item->loaiNganhNghe as $value) {
                        if (gettype($value) === 'object') {
                            $loaiNganhNghe->push($value->ten);
                        }
                    }
                }
                $tinhThanh = '';
                if (isset($item->tinhThanhs) && $item->tinhThanhs) {
                    foreach ($item->tinhThanhs as $it) {
                        if ($item->ten) {
                            $tinhThanh = $tinhThanh ? "$it->ten, $tinhThanh" : $it->ten;
                        }
                    }
                }
                $quanHuyen = '';
                if (isset($item->quanHuyens) && $item->quanHuyens) {
                    foreach ($item->quanHuyens as $it) {
                        if ($item->ten) {
                            $quanHuyen = $quanHuyen ? "$it->ten, $quanHuyen" : $it->ten;
                        }
                    }
                }
                $phuongXa = '';
                if (isset($item->phuongXas) && $item->phuongXas) {
                    foreach ($item->phuongXas as $it) {
                        if ($item->ten) {
                            $phuongXa = $phuongXa ? "$it->ten, $phuongXa" : $it->ten;
                        }
                    }
                }
                return [
                    'A' => $key + 1,
                    'B' => $item->ma_dinh_danh,
                    'C' => $item->ten,
                    'D' => $item->dia_chi_hoat_dong,
                    'E' => $phuongXa,
                    'F' => $quanHuyen,
                    'G' => $tinhThanh,
                    'H' =>  $item->loaiKhuCongNghiep ?  $item->loaiKhuCongNghiep->ten : '',
                    'I' => $loaiNganhNghe->implode(", "),
                    'J' => $chatThaiSinhHoat->sum(),
                    'K' => $chatThaiCn->sum(),
                    'L' => $chatThaiNguyHai->sum()
                ];
            });
        }

        if ($request->get('maus_xu_ly_vi_pham_hanh_chinh')) {
            // Lấy danh sách cơ sở có quyết định xử phạt
            $xuLyViPham = $coSoSanXuat->filter(function ($item) {
                return $item->organization->ketQuaThanhTras
                    && $item->organization->ketQuaThanhTras->flatMap(function ($kq) {
                        return $kq->quyetDinhXuPhats;
                    })->count() > 0;
            })->values();

            // Gom theo tỉnh
            $theoTinh = $xuLyViPham->groupBy(function ($item) {
                // Ưu tiên lấy từ $item->tinhThanhs nếu có
                if (isset($item->tinhThanhs) && $item->tinhThanhs->count() > 0) {
                    return $item->tinhThanhs->first()->ten;
                }
                return 'Không xác định';
            });

            $dataReturn['XuLyViPhamHanhChinh'] = collect();

            foreach ($theoTinh as $tenTinh => $dsCoSo) {
                // Tính tổng tiền phạt toàn tỉnh
                $tongTienTinh = 0;
                $rowsTinh = collect();

                foreach ($dsCoSo as $itemKey => $item) {
                    $tongSoTien = collect();
                    $noiDungViPham = collect();
                    $nhomHanhVi = collect();
                    $soQuyetDinh = collect();

                    foreach ($item->organization->ketQuaThanhTras as $ketqua) {

                        foreach ($ketqua->quyetDinhXuPhats as $qd) {
                            if (!$qd->so_quyet_dinh) {
                                continue;
                            }

                            // Gom nội dung vi phạm từng Quyết định
                            $noiDungChiTiet = collect();

                            if ($qd->xuPhatChinh) {
                                foreach ($qd->xuPhatChinh as $xp) {
                                    $tongSoTien->push($xp->so_tien_xu_phat_chinh);

                                    // Gộp hành vi chi tiết dạng “- Nội dung: …”
                                    $noiDungChiTiet->push(
                                        '- ' . trim($xp->noi_dung_vi_pham)
                                    );
                                }
                            }

                            // Dạng đầy đủ: “11/QĐ-XPHC ngày 16/11/2022:\n- hành vi 1\n- hành vi 2”
                            $qdText =
                                ($qd->so_quyet_dinh ? $qd->so_quyet_dinh : '') .
                                ($qd->thoi_gian_ban_hanh ? ' ngày ' . $qd->thoi_gian_ban_hanh : '') .
                                ":\n" .
                                $noiDungChiTiet->implode("\n");

                            $soQuyetDinh->push($qdText);

                            // Nhóm hành vi
                            if ($ketqua->nhomHanhViViPhams) {
                                foreach ($ketqua->nhomHanhViViPhams as $nhom) {
                                    $nhomHanhVi->push($nhom->ten_nhom);
                                }
                            }
                        }
                    }

                    $tongTienTinh += $tongSoTien->sum();

                    $rowsTinh->push([
                        'A' => $itemKey + 1,
                        'B' => mb_convert_encoding($item->ten ?? '', 'UTF-8', 'UTF-8'),
                        'C' => number_format($tongSoTien->sum(), 0, ',', '.'),
                        'D' => mb_convert_encoding($soQuyetDinh->unique()->implode("\n"), 'UTF-8', 'UTF-8'),
                        'E' => mb_convert_encoding($nhomHanhVi->unique()->implode("\n"), 'UTF-8', 'UTF-8'),
                    ]);
                }

                $dataReturn['XuLyViPhamHanhChinh']->push([
                    'A' => '',
                    'B' => mb_strtoupper($tenTinh),
                    'C' => number_format($tongTienTinh, 0, ',', '.'),
                    'D' => '',
                    'E' => '',
                    '__is_header' => true,
                ]);

                $dataReturn['XuLyViPhamHanhChinh'] = $dataReturn['XuLyViPhamHanhChinh']->merge($rowsTinh);
            }
        }

        if ($request->get('maus_tong_hop_ket_qua_kiem_tra_vi_pham')) {
            $theoDoiTT = $this->filterKetQuaKhacPhuc($info, $theoDoiThanhTra);

            $dataReturn['TongHopKetQuaKiemTraViPham'] = $theoDoiTT->map(function ($item, $key) {
                $noiDungViPham = collect();
                $tongTienViPham = collect();
                $noiDungSuPhatBoSung = collect();
                $bienPhapKhacPhucHauQua = collect();
                $soTienChungCau = collect();
                $dsQD = collect();

                if ($item->quyetDinhXuPhats) {
                    // Hành vi & tiền phạt
                    $item->quyetDinhXuPhats->xuPhatChinh->each(function ($value) use ($tongTienViPham, $noiDungViPham) {
                        $noiDungViPham->push($value->noi_dung_vi_pham);
                        $tongTienViPham->push($value->so_tien_xu_phat_chinh);
                    });
                    // Chi phí KPHQ
                    $item->quyetDinhXuPhats->bienPhapKhacPhucHauQua->each(function ($value) use ($soTienChungCau) {
                        $soTienChungCau->push($value->so_tien_chung_cau);
                    });
                    // QĐXP (ghép Số + Ngày)
                    $dsQD->push(
                        trim(
                            ($item->quyetDinhXuPhats->so_quyet_dinh ?: '') .
                                ($item->quyetDinhXuPhats->thoi_gian_ban_hanh ? ' ngày ' . $item->quyetDinhXuPhats->thoi_gian_ban_hanh : '')
                        )
                    );
                }

                // Địa chỉ
                $diaChiChiTiet = $item->dia_chi_hoat_dong;

                // KQ kiểm tra (Vi phạm, tồn tại)
                $ketQuaKiemTra = $noiDungViPham->filter()->implode("\n");

                // Lập BBVP
                $lapBBVP = $ketQuaKiemTra !== '' || ($item->quyetDinhXuPhats && $item->quyetDinhXuPhats->so_quyet_dinh) ? 'Có' : 'Không';

                $soKetLuan = $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->so_quyet_dinh ?? '') : '';
                $ngayBanHanhKL = $item->ketQuaThanhTras ? ($item->ketQuaThanhTras->ngay_thanh_tra ?? '') : '';
                $thongBaoKLKT = trim($soKetLuan . ($ngayBanHanhKL ? ' ngày ' . $ngayBanHanhKL : ''));

                // Số tiền đã nộp
                $soTienDaNop = '';

                return [
                    'A' => $key + 1,                           // TT
                    'B' => $item->ten ?? '',                   // Tên cơ sở kiểm tra
                    'C' => $diaChiChiTiet,                     // Địa chỉ (Chỉ Chi tiết)
                    'D' => $ketQuaKiemTra,                     // KQ kiểm tra (Vi phạm, tồn tại) = Nội dung vi phạm
                    'E' => $lapBBVP,                           // Lập BBVP (Có/Không)
                    'F' => $dsQD->filter()->unique()->implode("\n"),        // QĐXP
                    'G' => $tongTienViPham->sum(),             // Tiền phạt (đồng)
                    'H' => $soTienChungCau->sum(),             // Chi phí KPHQ (đồng)
                    'I' => $soTienDaNop,                       // Số tiền đã nộp (đồng)
                    'J' => $thongBaoKLKT,                      // Thông báo KLKT = Số KLTT + Ngày ban hành
                    'K' => '',                                 // Cán bộ xử lý (chưa có nguồn dữ liệu)
                    'L' => '',                                 // Tiến độ công việc (chưa có nguồn dữ liệu)
                ];
            })->values();
        }


        return $this->sanitizeUtf8($dataReturn);
    }
    // public function exportExcel(Request $request)
    // {
    //     try {
    //         $dataExport = $this->_getDataNew($request);
    //         $excelFile  = public_path('report/xuatExcel.xlsx');

    //         if (!file_exists($excelFile)) {
    //             abort(404, 'Không tìm thấy file template xuatExcel.xlsx');
    //         }

    //         while (ob_get_level() > 0) {
    //             ob_end_clean();
    //         }

    //         // xử lý file Excel lớn
    //         set_time_limit(0);
    //         ini_set('memory_limit', '1024M');

    //         return \Excel::load($excelFile, function ($doc) use ($dataExport) {

    //             // Bản đồ giữa key dữ liệu và tên sheet trong file Excel
    //             $sheetsMap = [
    //                 'DoiTuongThanhTraCoBan'       => 'ĐỐI TƯỢNG THANH TRA CƠ BẢN',
    //                 'ThongTinChung'               => 'THÔNG TIN CHUNG',
    //                 'XaThai'                      => 'XẢ THẢI',
    //                 'ThuTucHanhChinh'             => 'THỦ TỤC HÀNH CHÍNH',
    //                 'TheoDoiThanhTra'             => 'THEO DÕI KẾT LUẬN TT CÁC NĂM',
    //                 'KhuSxDvTapTrung'             => 'KHU SX DỊCH VỤ TẬP TRUNG',
    //                 'ChatThaiRan'                 => 'CHẤT THẢI RẮN',
    //                 'XuLyViPhamHanhChinh'         => 'XỬ LÝ VI PHẠM HÀNH CHÍNH',
    //                 'TongHopKetQuaKiemTraViPham'  => 'TỔNG HỢP KẾT QUẢ',
    //             ];

    //             // Lặp qua từng nhóm dữ liệu
    //             foreach ($sheetsMap as $key => $sheetName) {
    //                 if (empty($dataExport[$key]) || $dataExport[$key]->isEmpty()) {
    //                     \Log::info("Bỏ qua sheet '{$sheetName}' (không có dữ liệu)");
    //                     continue;
    //                 }

    //                 try {
    //                     // Đặt sheet theo tên
    //                     $doc->setActiveSheetIndexByName($sheetName);
    //                     $sheet = $doc->getActiveSheet();

    //                     if (!$sheet) {
    //                         \Log::warning("Không tìm thấy sheet '{$sheetName}' trong file template");
    //                         continue;
    //                     }

    //                     // Xác định dòng bắt đầu ghi dữ liệu
    //                     $startRow = ($key === 'KhuSxDvTapTrung') ? 3 : 4;

    //                     // Sinh danh sách cột từ A → AZ
    //                     $cols = range('A', 'Z');
    //                     $cols = array_merge($cols, [
    //                         'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL',
    //                         'AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
    //                     ]);

    //                     // Ghi dữ liệu vào sheet
    //                     $dataExport[$key]->each(function ($row, $i) use ($sheet, $cols, $startRow) {
    //                         foreach ($cols as $c) {
    //                             $address = $c . strval($i + $startRow);
    //                             $value = isset($row[$c]) ? $row[$c] : '';
    //                             $this->setValue($sheet, $address, $value);
    //                         }
    //                     });

    //                     \Log::info("Đã ghi dữ liệu cho sheet '{$sheetName}'");

    //                 } catch (\Throwable $e) {
    //                     \Log::error("Lỗi khi xử lý sheet '{$sheetName}': " . $e->getMessage());
    //                 }
    //             }

    //         })->download('xlsx');

    //     } catch (\Throwable $e) {
    //         \Log::error("Lỗi exportExcel(): " . $e->getMessage());
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Xuất Excel thất bại: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function exportExcel(Request $request)
    {
        try {
            $dataExport = $this->_getDataNew($request);
            $excelFile  = public_path('report/xuatExcel.xlsx');

            if (!file_exists($excelFile)) {
                abort(404, 'Không tìm thấy file template xuatExcel.xlsx');
            }

            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            set_time_limit(0);
            ini_set('memory_limit', '1024M');

            return \Excel::load($excelFile, function ($doc) use ($dataExport) {

                $sheetsMap = [
                    'DoiTuongThanhTraCoBan'       => 'ĐỐI TƯỢNG THANH TRA CƠ BẢN',
                    'ThongTinChung'               => 'THÔNG TIN CHUNG',
                    'XaThai'                      => 'XẢ THẢI',
                    'ThuTucHanhChinh'             => 'THỦ TỤC HÀNH CHÍNH',
                    'TheoDoiThanhTra'             => 'THEO DÕI KẾT LUẬN TT CÁC NĂM',
                    'KhuSxDvTapTrung'             => 'KHU SX DỊCH VỤ TẬP TRUNG',
                    'ChatThaiRan'                 => 'CHẤT THẢI RẮN',
                    'XuLyViPhamHanhChinh'         => 'XỬ LÝ VI PHẠM HÀNH CHÍNH',
                    'TongHopKetQuaKiemTraViPham'  => 'TỔNG HỢP KẾT QUẢ',
                ];

                // ✅ Danh sách cột tương ứng từng sheet
                $sheetCols = [
                    'DoiTuongThanhTraCoBan'       => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'],
                    'ThongTinChung'               => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB'],
                    'XaThai'                      => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD'],
                    'ThuTucHanhChinh'             => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'],
                    'TheoDoiThanhTra'             => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL'],
                    'KhuSxDvTapTrung'             => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'],
                    'ChatThaiRan'                 => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'],
                    'XuLyViPhamHanhChinh'         => ['A', 'B', 'C', 'D', 'E'],
                    'TongHopKetQuaKiemTraViPham'  => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'],
                ];

                // ✅ Ghi dữ liệu cho từng sheet
                foreach ($sheetsMap as $key => $sheetName) {
                    if (empty($dataExport[$key]) || $dataExport[$key]->isEmpty()) {
                        \Log::info("Bỏ qua sheet '{$sheetName}' (không có dữ liệu)");
                        continue;
                    }

                    try {
                        $doc->setActiveSheetIndexByName($sheetName);
                        $sheet = $doc->getActiveSheet();

                        if (!$sheet) {
                            \Log::warning("Không tìm thấy sheet '{$sheetName}' trong file template");
                            continue;
                        }

                        // Xác định dòng bắt đầu ghi dữ liệu
                        $startRow = ($key === 'KhuSxDvTapTrung') ? 3 : 4;

                        // Lấy danh sách cột tương ứng
                        $cols = $sheetCols[$key] ?? ['A', 'B', 'C', 'D', 'E'];

                        // Ghi dữ liệu từng dòng
                        $dataExport[$key]->each(function ($row, $i) use ($sheet, $cols, $startRow) {
                            foreach ($cols as $c) {
                                $address = $c . strval($i + $startRow);
                                $value   = isset($row[$c]) ? $row[$c] : '';
                                $this->setValue($sheet, $address, $value);
                            }
                        });

                        // ===== CHỈ ÁP DỤNG FORMAT CHO SHEET XỬ LÝ VI PHẠM HÀNH CHÍNH =====
                        if ($key === 'XuLyViPhamHanhChinh') {
                            $sheet->getColumnDimension('A')->setWidth(6);
                            $sheet->getColumnDimension('B')->setWidth(45);
                            $sheet->getColumnDimension('C')->setWidth(20);
                            $sheet->getColumnDimension('D')->setWidth(90);
                            $sheet->getColumnDimension('E')->setWidth(60);

                            $lastRow = $startRow + max(0, $dataExport[$key]->count() - 1);

                            // Bật wrap text + canh top cho cột B,D,E
                            $sheet->getStyle("B{$startRow}:D{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            $sheet->getStyle("D{$startRow}:E{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            // Format tiền tệ cho cột C
                            $sheet->getStyle("C{$startRow}:C{$lastRow}")
                                ->getNumberFormat()
                                ->setFormatCode('#,##0');
                        }

                        if ($key === 'TongHopKetQuaKiemTraViPham') {
                            $sheet->getColumnDimension('A')->setWidth(6);
                            $sheet->getColumnDimension('B')->setWidth(45);
                            $sheet->getColumnDimension('C')->setWidth(40);
                            $sheet->getColumnDimension('D')->setWidth(90);
                            $sheet->getColumnDimension('E')->setWidth(25);
                            $sheet->getColumnDimension('F')->setWidth(25);
                            $sheet->getColumnDimension('G')->setWidth(25);
                            $sheet->getColumnDimension('H')->setWidth(25);
                            $sheet->getColumnDimension('I')->setWidth(25);
                            $sheet->getColumnDimension('J')->setWidth(40);
                            $sheet->getColumnDimension('K')->setWidth(25);
                            $sheet->getColumnDimension('L')->setWidth(45);

                            $lastRow = $startRow + max(0, $dataExport[$key]->count() - 1);

                            // Wrap text cho các cột dài
                            $sheet->getStyle("B{$startRow}:D{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            $sheet->getStyle("C{$startRow}:D{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            $sheet->getStyle("D{$startRow}:D{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            $sheet->getStyle("J{$startRow}:J{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            $sheet->getStyle("L{$startRow}:L{$lastRow}")
                                ->getAlignment()
                                ->setWrapText(true)
                                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

                            // Format tiền tệ cho các cột G, H, I
                            foreach (['G', 'H', 'I'] as $col) {
                                $sheet->getStyle("{$col}{$startRow}:{$col}{$lastRow}")
                                    ->getNumberFormat()
                                    ->setFormatCode('#,##0');
                            }
                        }

                        \Log::info("Đã ghi dữ liệu cho sheet '{$sheetName}'");
                    } catch (\Throwable $e) {
                        \Log::error("Lỗi khi xử lý sheet '{$sheetName}': " . $e->getMessage());
                    }
                }
            })->download('xlsx');
        } catch (\Throwable $e) {
            \Log::error("Lỗi exportExcel(): " . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Xuất Excel thất bại: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function sanitizeUtf8($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeUtf8'], $data);
        } elseif ($data instanceof \Illuminate\Support\Collection) {
            return $data->map(function ($item) {
                return $this->sanitizeUtf8($item);
            });
        } elseif (is_string($data)) {
            // Chuẩn hóa UTF-8, loại bỏ ký tự điều khiển và ký tự lỗi
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
            $data = preg_replace('/[^\PC\s]/u', '', $data);
            return $data;
        } else {
            return $data;
        }
    }
}
