<?php

use App\CoQuanToChuc;
use App\TinhThanh;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCoQuanDonViSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/CoQuanToChuc.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/CoQuanToChuc.json")));
        $dem = 0;
        $tong = count($data);
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                $item = (array)$item;
                $query = CoQuanToChuc::where('ma_dinh_danh', $item['MaDinhDanh']);
                if (isset($item['TenGoi'])) {
                    $query->orWhere('ten', 'ilike', $item['TenGoi']);
                }
                $check = $query->first();
                $tinhThanhID = null;
                if (isset($item['DiaChi']) && isset($item['DiaChi']->TinhThanh) && isset($item['DiaChi']->TinhThanh->MaMuc)) {
                    $tinhThanhID = TinhThanh::where('ma', $item['DiaChi']->TinhThanh->MaMuc)->first()->id;
                }
                if ($check) {
                    $check->update(['tinh_thanh_id' => $tinhThanhID, 'ma_dinh_danh' => $item['MaDinhDanh']]);
                } else {
                    if (isset($item['TenGoi'])) {
                        CoQuanToChuc::create([
                            'tinh_thanh_id' => $tinhThanhID,
                            'ma_dinh_danh' => $item['MaDinhDanh'],
                            'ten' => $item['TenGoi']
                        ]);
                    }
                }
                $dem++;
                echo "Da nhap $dem / $tong \n";
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
