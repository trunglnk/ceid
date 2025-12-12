<?php

use Illuminate\Database\Seeder;
use App\Organization;
use App\ChuDauTu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class CoSoChuDauTuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/toChuc.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/toChuc.json")));
        $tongToChuc = Organization::query()->count();
        $count = 0;
        try {
            DB::beginTransaction();
            foreach($data as $item){
            $item = (array) $item;
            $chudautu = $item['tenchudautu'];
            $chudautu = ChuDauTu::where('ten',$item['tenchudautu'])->first();
            if ($chudautu==null){}
            else{
            Organization::where('ten',$item['tengoi_chucoso'])->update([
                'chu_dau_tu_id'=>$chudautu['id']
            ]);               
            }
        }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
