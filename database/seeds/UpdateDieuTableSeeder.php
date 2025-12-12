<?php

use App\Dieu;
use App\Khoan;
use Illuminate\Database\Seeder;

class UpdateDieuTableSeeder extends Seeder
{
    public function run()
    {
        $dieu_13 = Dieu::where('ten', 'Điều 13')->first();
        if (!empty($dieu_13)) {
            Khoan::where('ten', '7')->where('dieu_id', $dieu_13->id)->update(['khoan_phat_them' => true]);
        }
        $dieu_15 = Dieu::where('ten', 'Điều 15')->first();
        if (!empty($dieu_15)) {
            Khoan::where('ten', '6')->where('dieu_id', $dieu_15->id)->update(['khoan_phat_them' => true]);
        }
    }
}
