<?php

use App\LuuVucSong;
use App\TinhThanh;
use App\TinhThanhLuuVucSong;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LuuVucSongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $luuVucIds = TinhThanh::whereNotNull('luu_vuc_song_id')->groupBy('luu_vuc_song_id')->pluck('luu_vuc_song_id')->toArray();
        $myfile = fopen("database/seeds/LuuVucSong.json", "r");
        $data = json_decode(fread($myfile, filesize("database/seeds/LuuVucSong.json")));
        try{
            DB::beginTransaction();
            LuuVucSong::whereNotIn('id', $luuVucIds)->delete();
            foreach($data as $item){
                $item = (array) $item;
                $tinhThanh = explode( '||', $item['Tinh']);
                $luuVucExist = LuuVucSong::where('ten', $item['Ten'])->first();
                if($luuVucExist){
                    $luuVucExist->update([
                        'ma' => $item['Ma'],
                        'mo_ta' => isset($item['GhiChu']) ? $item['GhiChu'] : null,
                        'chieu_dai' => isset($item['ChieuDai']) ? $item['ChieuDai'] : null,
                        'dien_tich' => isset($item['DienTich']) ? $item['DienTich'] : null,
                    ]);
                }else {
                    $luuVucExist = LuuVucSong::create([
                        'ten' => $item['Ten'],
                        'ma' => $item['Ma'],
                        'mo_ta' => isset($item['GhiChu']) ? $item['GhiChu'] : null,
                        'chieu_dai' => isset($item['ChieuDai']) ? $item['ChieuDai'] : null,
                        'dien_tich' => isset($item['DienTich']) ? $item['DienTich'] : null,
                    ]);
                }
                $tinhThanhIds = TinhThanh::whereIn('ten', $tinhThanh)->pluck('id')->toArray();

                foreach($tinhThanhIds as $tinh){
                    TinhThanhLuuVucSong::create([
                        'tinh_thanh_id' => $tinh,
                        'luu_vuc_song_id' => $luuVucExist->id
                    ]);
                }
            }

            DB::commit();
            return 'DONE';
        }catch(\Exception $e){
            DB::rollback();
            dd($e);
        }
    }
}
