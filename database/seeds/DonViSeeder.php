<?php

use Illuminate\Database\Seeder;

use App\DonVi;
class DonViSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
				$array = [
					"ten"=>"Trung tâm thông tin và dữ liệu môi trường",
					"eng_ten"=>"Centre for Environmental Infomation and Data",
					"short_ten"=>"CEID",
					"website"=>"http://ceid.gov.vn/"
				];
				DonVi::create($array);
    }
}
