<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Illuminate\Support\Facades\Route;

Route::get('quyet-dinh-thanh-lap', 'ShareApiController@getQuyetDinhThanhLap');
Route::get('ket-luan-thanh-tra', 'ShareApiController@getKetQuaThanhTra');
Route::get('quyet-dinh-xu-phat-hanh-chinh', 'ShareApiController@quyetDinhXuPhatHanhChinh');
Route::get('giay-phep-moi-truong', 'ShareApiController@getGiayPhepMoiTruong');

