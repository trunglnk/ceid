<?php

use Illuminate\Database\Seeder;
use app\TinhThanh;
use app\QuanHuyen;
use App\ChuDauTu;
use App\Organization;
use Illuminate\Support\Facades\DB;
use App\CoSoSanXuat;
use App\LoaiCoSo;
use App\LoaiHinhCoSo;
use App\LoaiHinhOiNhiem;
use App\LuuVucSong;
use Carbon\Carbon;


class MoiTruongCoSoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/MoiTruongCoSoThanhTraCuoi.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/MoiTruongCoSoThanhTraCuoi.json")));
        $dem = 0;
        $tong = count($data);
        // dd($data[808]);
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                $item = (array) $item;
                $IDCoSoSanXuat = CoSoSanXuat::where('id', $item['id_coso'])->first();
                $IDChuDauTu = ChuDauTu::where('id', $item['idchudautu'])->first();
                $tenChuDauTu = ChuDauTu::where('ten', $item['tenchudautu'])->first();
                if (!$IDChuDauTu && !$tenChuDauTu) {
                    ChuDauTu::create([
                        'id' => $item['idchudautu'],
                        'ten' => $item['tenchudautu']
                    ]);
                }
                $IDChuDauTu = ChuDauTu::where('id', $item['idchudautu'])->first();
                $tenChuDauTu = ChuDauTu::where('ten', $item['tenchudautu'])->first();
                if ($IDCoSoSanXuat) {
                    if ($IDChuDauTu) {
                        Organization::where('id', $IDCoSoSanXuat['organization_id'])->update([
                            'ten'=>$item['tengoi_chucoso'],
                            'chu_dau_tu_id' =>  $IDChuDauTu['id'],
                        ]);
                    }
                    else{
                        Organization::where('id', $IDCoSoSanXuat['organization_id'])->update([
                            'ten'=>$item['tengoi_chucoso'],
                            'chu_dau_tu_id' =>  $tenChuDauTu['id'],
                        ]);
                    }
                };

                $tinhThanh = $item['tinhthanh'];
                $tinhThanh = TinhThanh::where('ten', 'ilike', "%{$item['tinhthanh']}%")->first();
                // $tinhThanh = TinhThanh::where('ten',$item['tinhthanh'])->first();
                $quanHuyen = $item['quanhuyen'];
                $quanHuyen = QuanHuyen::where('tinh_thanh_id', $tinhThanh['id'])->where('ten', $item['quanhuyen'])->first();
                // $quanHuyen = QuanHuyen::where('tinh_thanh_id',$tinhThanh['id'])->where('ten','ilike',"%{$item['quanhuyen']}%")->first();
                if (isset($item['LoaiHinhCoSo'])) {
                    $loaiCoSo = LoaiCoSo::where('ten_co_so', $item['LoaiHinhCoSo'])->first();
                    if ($loaiCoSo) {
                    } else {
                        LoaiCoSo::create([
                            'ten_co_so' => $item['LoaiHinhCoSo'],
                        ]);
                    }
                    $loaiCoSo = LoaiCoSo::where('ten_co_so', $item['LoaiHinhCoSo'])->first();
                }

                // if(isset($item['LoaihinhsanxuatconguycotacdongxaudenMT'])){
                //     $loaiHinhOiNhiem = LoaiHinhOiNhiem::where('ten', $item['LoaihinhsanxuatconguycotacdongxaudenMT'])->first();
                // }
                if (isset($item['luuvucsong'])) {
                    $luuVucSong = LuuVucSong::where('ten', $item['luuvucsong'])->first();
                }
                $tenChuCoSo = Organization::where('ten', $item['tengoi_chucoso'])->first();
                if ($IDCoSoSanXuat) {
                    CoSoSanXuat::where('id', $IDCoSoSanXuat['id'])->update([
                        'ten' => $item['Tengoi_coso'],
                        'loai_co_so_id' => isset($item['LoaiHinhCoSo']) ? $loaiCoSo['id'] : null,
                        // 'loai_hinh_oi_nhiem_id' => isset($item['LoaihinhsanxuatconguycotacdongxaudenMT']) ? $loaiHinhOiNhiem['id'] : null,
                        // 'loai_nganh_kinh_te_id' => isset($item['LoaiNganhNgheKinhTe'])?$loaiNganhNghe['id']:null,
                        'dia_chi_lien_he' => isset($item['diachichitiet']) ? $item['diachichitiet'] : null,
                        'xa_phuong' => isset($item['xaphuong']) ? $item['xaphuong'] : null,
                        'quan_huyen_id' => $quanHuyen['id'],
                        'tinh_thanh_id' => $tinhThanh['id'],
                        'kinh_do' => isset($item['kinhdo']) ? $item['kinhdo'] : null,
                        'vi_do' => isset($item['vido']) ? $item['vido'] : null,
                        'dien_tich' => isset($item['dientich']) ? $item['dientich'] : null,
                        'nguyen_lieu_chinh_su_dung' => isset($item['nguyenlieusudung']) ? $item['nguyenlieusudung'] : null,
                        'nhien_lieu_chinh_su_dung' => isset($item['nhienlieusudung']) ? $item['nhienlieusudung'] : null,
                        'nguon_nuoc_su_dung' => isset($item['nguonnuocsudung']) ? $item['nguonnuocsudung'] : null,
                        'hoa_chat_su_dung' => isset($item['hoachatsudung']) ? $item['hoachatsudung'] : null,
                        'luong_nuoc_su_dung' => isset($item['luuluongnuoc']) ? $item['luuluongnuoc'] : null,
                        'luu_vuc_song_id' => isset($item['luuvucsong']) ? $luuVucSong['id'] : null,
                    ]);
                } else {
                    CoSoSanXuat::create([
                        'id' => (int)$item['id_coso'],
                        'created_by' => 2,
                        'updated_by' => 2,
                        'ten' => $item['Tengoi_coso'],
                        'loai_co_so_id' => isset($item['LoaiHinhCoSo']) ? $loaiCoSo['id'] : null,
                        // 'loai_hinh_oi_nhiem_id' => isset($item['LoaihinhsanxuatconguycotacdongxaudenMT'])?$loaiHinhOiNhiem['id']:null,
                        // 'loai_nganh_kinh_te_id' => isset($item['LoaiNganhNgheKinhTe'])?$loaiNganhNghe['id']:null,
                        'dia_chi_lien_he' => isset($item['diachichitiet']) ? $item['diachichitiet'] : null,
                        'xa_phuong' => isset($item['xaphuong']) ? $item['xaphuong'] : null,
                        'quan_huyen_id' => $quanHuyen['id'],
                        'tinh_thanh_id' => $tinhThanh['id'],
                        'kinh_do' => isset($item['kinhdo']) ? $item['kinhdo'] : null,
                        'vi_do' => isset($item['vido']) ? $item['vido'] : null,
                        'dien_tich' => isset($item['dientich']) ? $item['dientich'] : null,
                        'nguyen_lieu_chinh_su_dung' => isset($item['nguyenlieusudung']) ? $item['nguyenlieusudung'] : null,
                        'nhien_lieu_chinh_su_dung' => isset($item['nhienlieusudung']) ? $item['nhienlieusudung'] : null,
                        'nguon_nuoc_su_dung' => isset($item['nguonnuocsudung']) ? $item['nguonnuocsudung'] : null,
                        'hoa_chat_su_dung' => isset($item['hoachatsudung']) ? $item['hoachatsudung'] : null,
                        'luong_nuoc_su_dung' => isset($item['luuluongnuoc']) ? $item['luuluongnuoc'] : null,
                        'luu_vuc_song_id' => isset($item['luuvucsong']) ? $luuVucSong['id'] : null,
                        'organization_id' => $tenChuCoSo ? $tenChuCoSo['id'] : null,
                    ]);
                };
                $dem = $dem + 1;
                echo "Da nhap $dem / $tong \n";
            }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
