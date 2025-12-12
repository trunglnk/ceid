<?php

use App\DanhMucHanhViViPham;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NghiDinh45Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/nghidinh45.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/nghidinh45.json")));
        $dem = 0;
        $tong = count($data);
        try{
            DB::beginTransaction();
            DanhMucHanhViViPham::query()->delete();
            DB::statement('ALTER SEQUENCE danh_muc_hanh_vi_vi_phams_id_seq RESTART WITH 1');
            foreach ($data as $item) {
                $item = (array) $item;
                DanhMucHanhViViPham::create([
                    'ma_hanh_vi' => $item['ma_hanh_vi'],
                    'nhom_hanh_vi' => isset($item['nhom_hanh_vi']) ? $item['nhom_hanh_vi'] : "",
                    'ten_hanh_vi' => $item['ten_hanh_vi'],
                    'phat_nho_nhat' =>  isset($item['phat_nho_nhat']) ? $item['phat_nho_nhat'] : "",
                    'phat_lon_nhat' =>  isset($item['phat_lon_nhat']) ? $item['phat_lon_nhat']: "",
                    'dieu_luat' => isset($item['dieu_luat']) ? $item['dieu_luat']: "",
                    'xu_phat_bo_xung' => isset($item['phat_bo_xung']) ? $item['phat_bo_xung'] : "",
                    'bien_phap_khac_phuc_hau_qua' => isset($item['bien_phap']) ? $item['bien_phap'] : "",
                    'phap_ly' => '45/2022/NĐ-CP'
                ]);
                $dem = $dem + 1;
                echo "Da nhap $dem / $tong \n";
            }
            DB::commit();
            return 'Done';
        }catch(\Exception $e){
            dd($e);
        }
    }
}
