<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeoCode;
use App\Traits\GetDataCache;
use App\Traits\ExecuteString;

class AddressController extends Controller
{
    use GeoCode, GetDataCache, ExecuteString;

    public function standardizedaddress(Request $request) {
        $address = $request->get('address');
        if(empty($address)) {
            return response()->json([           
                'message' => 'Thành công',
                'result'  => []
            ], 200, []);
        }
        $provinces = $this->getDataByName(\App\TinhThanh::class);
        $districts = $this->getDataByName(\App\QuanHuyen::class);
        return response()->json([           
            'message' => 'Thành công',
            'result'  => $this->getAddressFromText($this->cappitalizeEachWord($address), $provinces, $districts)
        ], 200, []);        
    }
}
