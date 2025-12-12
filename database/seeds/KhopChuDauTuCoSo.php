<?php

use App\CoSoSanXuat;
use App\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhopChuDauTuCoSo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/khop_chudautu_coso.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/khop_chudautu_coso.json")));
        $dem = 0;
        $tong = count($data);
        $khongKhop = 0;
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                $item = (array)$item;
                $organization = Organization::where('id',  $item['idchudautu'])->first();
                if ($organization) {
                    CoSoSanXuat::where('id', $item['id_coso'])->update(['organization_id' => $item['idchudautu']]);
                }else {$khongKhop++;}
                $dem = $dem + 1;
                echo "Da nhap $dem / $tong \n";
                echo "Khong khop $khongKhop";
            }

            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
