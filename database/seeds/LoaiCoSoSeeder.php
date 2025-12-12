<?php

use App\LoaiHinhCoSo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiCoSoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/loaiCoSo.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/loaiCoSo.json")));
        $before = null;
        $convert_data = [];
        foreach ($data as $key => $item) {
            $item = (array) $item;
            if ($item['cap_1'] && empty($item['cap_2'])) {
                $before = (array) $item;
                $convert_data[] =  (array) $item;
                continue;
            }
            if ($item['cap_2'] && empty($item['cap_1'])) {
                $item['cap_1'] = $before['cap_1'];
                $before = (array) $item;
                $convert_data[] =  (array) $item;
                continue;
            }
            if ($item['cap_3'] && empty($item['cap_2'])) {
                $item['cap_1'] = $before['cap_1'];
                $item['cap_2'] = $before['cap_2'];
                $before = (array) $item;
                $convert_data[] =  (array) $item;
                continue;
            }
            if (empty($item['cap_1'])) {
                $item['cap_1'] = $before['cap_1'];
            }
            if (empty($item['cap_2'])) {
                $item['cap_2'] = $before['cap_2'];
            }
            if (empty($item['cap_3'])) {
                $item['cap_3'] = $before['cap_3'];
            }
            if (empty($item['cap_4'])) {
                $item['cap_4'] = $before['cap_4'];
            }
            $before = $item;
            $convert_data[] = $item;
        };
        try {
            DB::beginTransaction();
            LoaiHinhCoSo::where('van_ban_phap_luat', '27/2018/QĐ-TTg')->delete();
            foreach ($convert_data as $item) {
                if (!empty($item['cap_1']) && empty($item['cap_2'])) {
                    LoaiHinhCoSo::create([
                        'ten' => $item['name'],
                        'ma' => $item['cap_1']
                    ]);
                }
            }
            foreach ($convert_data as $item) {
                if (!empty($item['cap_2']) && empty($item['cap_3'])) {
                    $parent = LoaiHinhCoSo::where('ma', $item['cap_1'])->first();
                    LoaiHinhCoSo::create([
                        'ten' => $item['name'],
                        'ma' => $item['cap_2'],
                        'parent_id' => $parent->id
                    ]);
                }
            }
            foreach ($convert_data as $item) {
                if (!empty($item['cap_3']) && empty($item['cap_4'])) {
                    $parent = LoaiHinhCoSo::where('ma', $item['cap_2'])->first();
                    if (!$parent) {
                        $parent = LoaiHinhCoSo::where('ma', $item['cap_1'])->first();
                    }
                    LoaiHinhCoSo::create([
                        'ten' => $item['name'],
                        'ma' => $item['cap_3'],
                        'parent_id' => $parent->id
                    ]);
                }
            }
            foreach ($convert_data as $item) {
                if (!empty($item['cap_4']) && empty($item['cap_5'])) {
                    $parent = LoaiHinhCoSo::where('ma', $item['cap_3'])->first();
                    if (!$parent) {
                        $parent = LoaiHinhCoSo::where('ma', $item['cap_2'])->first();
                        if (!$parent) {
                            $parent = LoaiHinhCoSo::where('ma', $item['cap_1'])->first();
                        }
                    }
                    LoaiHinhCoSo::create([
                        'ten' => $item['name'],
                        'ma' => $item['cap_4'],
                        'parent_id' => $parent->id
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
