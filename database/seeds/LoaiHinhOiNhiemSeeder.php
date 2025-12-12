<?php

use App\LoaiHinhOiNhiem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiHinhOiNhiemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/loaiHinhOiNhiem.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/loaiHinhOiNhiem.json")));
        $data = (array) $data;
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                $check = LoaiHinhOiNhiem::where('ten', $item->name)->first();
                if (!$check) {
                    LoaiHinhOiNhiem::create([
                        'ten' => $item->name
                    ]);
                }
            }
            DB::commit();
            echo 'Done';
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
