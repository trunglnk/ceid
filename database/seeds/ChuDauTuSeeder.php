<?php

use Illuminate\Database\Seeder;
use app\TinhThanh;
use app\QuanHuyen;
use App\ChuDauTu;
use App\CoQuanToChuc;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ChuDauTuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/chuDauTuChuanHoa.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/chuDauTuChuanHoa.json")));
        $dem=0;
        $tong = count($data);
        // dd($data[3]);
        try{
            DB::beginTransaction();
            Organization::query()->update([
                'chu_dau_tu_id' => null
            ]);
            ChuDauTu::query()->delete();
            DB::statement('ALTER SEQUENCE chu_dau_tus_id_seq RESTART WITH 1');
            foreach($data as $item){
            $item = (array) $item;
            $tinhThanh = $item['tinhthanh'];
            $tinhThanh = TinhThanh::where('ten','ilike', "%{$item['tinhthanh']}%")->first();
            // $tinhThanh = TinhThanh::where('ten',$item['tinhthanh'])->first();
            $quanHuyen = $item['quanhuyen'];
            $quanHuyen = QuanHuyen::where('tinh_thanh_id',$tinhThanh['id'])->where('ten',$item['quanhuyen'])->first();
            // $quanHuyen = QuanHuyen::where('tinh_thanh_id',$tinhThanh['id'])->where('ten','ilike',"%{$item['quanhuyen']}%")->first();
            ChuDauTu::create([
                'id'=>(int)$item['idChuDauTu'],
                'ten' =>$item['tengoi'],
                'dia_chi'=>$item['diachilienhe'],
                'xa_phuong'=>isset($item['xaphuong']) ? $item['xaphuong'] : null,
                'quan_huyen_id'=>$quanHuyen['id'],
                'tinh_thanh_id'=>$tinhThanh['id'],
                'ma_so_qlctnh'=>isset($item['MaSoQLCTNH']) ? $item['MaSoQLCTNH'] : null,
                'so_giay_chung_nhan_dang_ky_kinh_doanh'=> isset($item['sogcnkd']) ? $item['sogcnkd'] :null,
                'co_quan_cap_giay_chung_nhan_dang_ky_kinh_doanh'=> isset($item['coquancapgcnkd']) ? $item['coquancapgcnkd'] : null,
                'ngay_cap_giay_chung_nhan_dang_ky_kinh_doanh'=> isset($item['ngaycapgcnkd']) ? Carbon::parse($item['ngaycapgcnkd']) : null,
                'ghi_chu'=>isset($item['ghichu']) ? $item['ghichu'] : null ,
            ]);
            $dem=$dem+1;
            echo "Da nhap $dem / $tong \n";
            }
            DB::commit();
            return 'Done';
        }catch(\Exception $e){
            DB::rollback();
            dd($e);
        }

    }
}
