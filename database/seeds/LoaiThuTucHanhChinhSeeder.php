<?php

use App\LoaiThuTucHanhChinh;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LoaiThuTucHanhChinhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/loaiThuTucHanhChinh.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/loaiThuTucHanhChinh.json")));
        $dem = 0;
        $tong = count($data);
        try {
            DB::beginTransaction();
            $loaiThuTuc = LoaiThuTucHanhChinh::query()->pluck('ten')->toArray();
            $not = [];
            DB::table('loai_thu_tuc_hanh_chinhs')->update([
                'active' => false
            ]);
            foreach ($data as $item) {
                $item = (array)$item;
                if (!in_array($item['ten_muc'], $loaiThuTuc)) {
                    $not[] = $item['ten_muc'];
                }
                $checkExist =  LoaiThuTucHanhChinh::where('ten', $item['ten_muc'])->first();
                if ($checkExist) {
                    $checkExist->update([
                        'phan_loai' => $item['loai'],
                        'ma_muc_mtqg' => $item['ma_muc'], 
                        'active' => true
                    ]);
                }else {
                    LoaiThuTucHanhChinh::create([
                        'ma' => $item['loai'].$item['ma_muc'],
                        'ten' => $item['ten_muc'],
                        'ma_muc_mtqg' => $item['ma_muc'],
                        'phan_loai' => $item['loai'],
                    ]);
                }

            }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
