<?php

namespace App\Http\Controllers;

use App\KetQuaThanhTra;
use App\QuyetDinhThanhTra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CreateMoiTruongQuocGiaController extends Controller
{
    protected $API_MTQG = '';
    public function __construct()
    {
        $this->API_MTQG = config('app.api_mtqg');
    }
    private function getToken()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $this->API_MTQG . '/CSDL/quantridulieu/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'key: wqB4WakPOjfqia7VsXwB4jclr1ftJo52'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = (array)json_decode($response);
        return $response['access_token'];
    }

    public function createData($type, $data)
    {
        try {
            $token = $this->getToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  "$this->API_MTQG/CSDL/quantridulieu/$type/create",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'key: kdQw8czmVzwL9BvzSsa2m2TeeS0bQb7k',
                    "Authorization:  Bearer $token",
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            return $response;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function createQuyetDinh(Request $request)
    {
        try {
            $data = $request->get('data', null);
            $id = $request->get('id', null);
            if (!$data || !$id) {
                return response(['message' => 'Dữ liệu không hợp lệ'], 400);
            }
            $qd = QuyetDinhThanhTra::where('id', $id)->first();
            $tepDuLieu = [];
            $data = json_decode($data);
            if ($qd->attachments && count($qd->attachments)) {
                foreach ($qd->attachments as $file) {
                    if (Storage::exists('public/files/' . $file->file_id . '-' . $file->name)) {
                        $fileContent = file_get_contents(storage_path('app/public/files/' . $file->file_id . '-' . $file->name));
                        $base64File = base64_encode($fileContent);
                        $tepDuLieu[] = (object)[
                            'TenTep' =>  $file->name,
                            'NoiDungDuLieu' => $base64File,
                            'TenVanBan' =>  $file->name,
                        ];
                    }
                }
            }
            if (count((array)$tepDuLieu) > 0) {
                $data->TepDuLieu = $tepDuLieu;
            }
            $data = json_encode($data);
            $response = $this->createData('T_DoanThanhTraKiemTra', $data);
            QuyetDinhThanhTra::where('id', $id)->update([
                'thoi_gian_dong_bo' => Carbon::now(),
                'trang_thai_dong_bo_ve' => 'da_dong_bo',
                'trang_thai_dong_bo_len' => 'da_dong_bo'
            ]);
            return $response;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function createKetLuanThanhTra(Request $request)
    {
        try {
            $data = $request->get('data', null);
            $id = $request->get('id', null);
            if (!$data || !$id) {
                return response(['message' => 'Dữ liệu không hợp lệ'], 400);
            }
            $response = $this->createData('T_KetLuanThanhTraKiemTra', $data);
            KetQuaThanhTra::where('id', $id)->update(['thoi_gian_dong_bo' => Carbon::now(), 'trang_thai_dong_bo' => 'da_dong_bo']);
            return $response;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
