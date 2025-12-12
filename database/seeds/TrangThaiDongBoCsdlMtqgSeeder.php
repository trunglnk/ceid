<?php

use App\TrangThaiDongBoCsdlMtqg;
use Illuminate\Database\Seeder;

class TrangThaiDongBoCsdlMtqgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         if (TrangThaiDongBoCsdlMtqg::count() == 0) {
            // Tạo bản ghi ban đầu với trang_thai = false
            TrangThaiDongBoCsdlMtqg::create([
                'trang_thai' => false,
            ]);
        }
    }
}
