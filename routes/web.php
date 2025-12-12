<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@indexWelcome')->name('indexWelcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/checklogin', 'HomeController@checkLogin')->name('checklogin');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('profile', 'ProfileController@show')->name('profile');
Route::post('profile', 'ProfileController@update')->name('profile.update');
Route::post('changepassword', 'ProfileController@changePassword')->name('profile.changepassword');

Route::get('users', 'UserController@index')->name('users');
Route::post('users/update/{id}', 'UserController@update')->name('users.update');
Route::get('users/update/{id}', 'UserController@showUpdate')->name('users.update');
Route::delete('users/delete/{id}', 'UserController@delete')->name('users.delete');
Route::post('users/add', 'UserController@add')->name('users.add');

Route::get('roles', 'SystemController@indexRole')->name('system.roles');
Route::post('roles/add', 'SystemController@addRole')->name('system.roles.add');
Route::put('roles/update/{id}', 'SystemController@updateRole')->name('system.roles.update');
Route::delete('roles/delete/{id}', 'SystemController@deleteRole')->name('system.roles.delete');
Route::get('roles/update/{id}', 'SystemController@showUpdateRole')->name('system.roles.update');
Route::post('roles/{id}/functions/add', 'SystemController@addFunctionOfRole')->name('system.roles.functions.add');

Route::get('menus', 'SystemController@indexMenu')->name('system.menus');
Route::post('menus/add', 'SystemController@addMenu')->name('system.menus.add');
Route::put('menus/update/{id}', 'SystemController@updateMenu')->name('system.menus.update');
Route::delete('menus/delete/{id}', 'SystemController@deleteMenu')->name('system.menus.delete');
Route::get('menus/update/{id}', 'SystemController@showUpdateMenu')->name('system.menus.update');
Route::post('menus/{id}/functions/add', 'SystemController@addFunctionOfMenu')->name('system.menus.functions.add');

Route::get('functions', 'SystemController@indexFunctions')->name('system.functions');
Route::post('functions/add', 'SystemController@addFunctions')->name('system.functions.add');
Route::delete('functions/delete/{id}', 'SystemController@deleteFunctions')->name('system.functions.delete');

//nghi dinh
Route::get('nghidinhs', 'DanhMucController@nghiDinh'); //danh sách nghị định
Route::post('nghidinhs', 'DanhMucController@nghiDinhStore'); //danh sách nghị định
Route::put('nghidinhs/{id}', 'DanhMucController@nghiDinhUpdate'); //update nghị định
Route::delete('nghidinhs/{id}', 'DanhMucController@nghiDinhDelete'); //delete nghị định

Route::get('moitruongquocgia/dongbohanhvi/{nghi_dinh_id}', 'MoiTruongQuocGiaController@dongBoHanhVi'); // đồng bộ nghị định
Route::get('danhmuc/hanhvivipham/{nghi_dinh_id}/chuadongbo', 'DanhMucController@getDanhMucHanhViChuaDongBo')->name('danhmuc.hanhvivipham.chuadongbo');
Route::get('danhmuc/hanhvivipham/{nghi_dinh_id}/dadongbo', 'DanhMucController@getDanhMucHanhViDaDongBoNew')->name('danhmuc.hanhvivipham.dadongbo');
Route::delete('xoahanhvi/{id}', 'DanhMucController@xoaDanhMucHanhVi');
Route::put('hanhvivipham/{id}', 'DanhMucController@updateHanhViViPham');
Route::post('hanhvivipham', 'DanhMucController@addHanhViViPham');









Route::get('getnghidinh155', 'DanhMucController@nghiDinh155');
Route::get('getnghidinh155dongbo', 'DanhMucController@nghiDinh155DaDongbo');
// Route::get('getnghidinh45cp', 'DanhMucController@getDanhMucHanhVi')->name('get.nghidinh45');
Route::get('getnghidinh45cpdongbo', 'DanhMucController@getDanhMucHanhViDaDongBo')->name('get.nghidinh45');
Route::get('danhmuc/nghidinh45cp', 'DanhMucController@nghiDinh45View')->name('category.nghidinh45');
Route::get('danhmuc', 'DanhMucController@index')->name('category.index');
Route::get('danhmucthanhtra', 'DanhMucController@indexDanhMucThanhTra')->name('danhmucthanhtra');
Route::get('danhmuc/cosophaply', 'DanhMucController@cosophaply')->name('category.cosophaply');
Route::get('loaikhucongnghieps', 'DanhMucController@indexLoaiKCN')->name('category.loaikhucongnghieps');
Route::post('loaikhucongnghieps', 'DanhMucController@addLoaiKCN')->name('category.loaikhucongnghieps');
Route::put('loaikhucongnghieps/{id}', 'DanhMucController@updateLoaiKCN')->name('category.loaikhucongnghieps');
Route::delete('loaikhucongnghieps/{id}', 'DanhMucController@deleteLoaiKCN')->name('category.loaikhucongnghieps');
Route::get('loaihinhcosos', 'DanhMucController@indexLoaiHinhCoSo')->name('category.loaihinhcosos');
Route::post('loaihinhcosos', 'DanhMucController@addLoaiHinhCoSo')->name('category.loaihinhcososadd');
Route::put('loaihinhcosos/{id}', 'DanhMucController@updateLoaiHinhCoSo')->name('category.loaihinhcososedit');
Route::delete('loaihinhcosos/{id}', 'DanhMucController@deleteLoaiHinhCoSo')->name('category.loaihinhcososdelete');
Route::get('nhomloaihinhcosos', 'DanhMucController@indexNhomLoaiHinhCoSo')->name('category.nhomloaihinhcosos');
Route::get('chitietloaihinhcoso/{id}', 'DanhMucController@getChiTietLoaiHinhCoSo');
Route::get('nganhnghes', 'DanhMucController@getLoaiNganhNghe')->name('category.loainganhnghes');
Route::get('loaihinhoinhiem', 'DanhMucController@getLoaiHinhOiNhiem')->name('category.loaioinhiem');
Route::post('loaihinhoinhiem', 'DanhMucController@addLoaiHinhOiNhiem')->name('category.addloaioinhiem');
Route::put('loaihinhoinhiem/{id}', 'DanhMucController@updateLoaiHinhOiNhiem')->name('category.updateloaioinhiem');
Route::delete('loaihinhoinhiem/{id}', 'DanhMucController@deleteLoaiHinhOiNhiem')->name('category.loaioinhiem');
Route::put('xoaloaihinhoinhiem/{id}', 'DanhMucController@editLoaiHinhOiNhiem')->name('category.editloaioinhiem');

Route::get('chudautu', 'ChuDauTuController@viewIndex')->name('chudautu');
Route::get('chudautu/add', 'ChuDauTuController@showFromAdd')->name('showadd.chudautu');
Route::get('chudautu/edit/{id}', 'ChuDauTuController@showFromEdit')->name('showedit.chudautu');
Route::post('chudautu', 'ChuDauTuController@addChuDauTu')->name('add.chudautu');
Route::get('chudautu/get', 'ChuDauTuController@getChuDauTu')->name('chudautu.get');
Route::put('chudautu/{id}', 'ChuDauTuController@updateChuDauTu')->name('chudautu.edit');
Route::delete('chudautu/delete/{id}', 'ChuDauTuController@deleteChuDauTu')->name('chudautu.delete');

Route::get('diemtramquantrac', 'DiemTramQuanTracController@viewIndex')->name('diemtramquantrac');
Route::get('diemtramquantrac/get', 'DiemTramQuanTracController@getDiemTramQuanTrac')->name('diemtramquantrac.get');
Route::get('diemtramquantrac/edit/{id}', 'DiemTramQuanTracController@showFromEdit')->name('showedit.diemtramquantrac');
Route::get('diemtramquantrac/add', 'DiemTramQuanTracController@showFromAdd')->name('showadd.diemtramquantrac');
Route::post('diemtramquantrac', 'DiemTramQuanTracController@addDiemTramQuanTrac')->name('add.diemtramquantrac');
Route::delete('diemtramquantrac/delete/{id}', 'DiemTramQuanTracController@deleteDiemTramQuanTrac')->name('diemtramquantrac.delete');
Route::put('diemtramquantrac/{id}', 'DiemTramQuanTracController@updateDiemTramQuanTrac')->name('diemtramquantrac.edit');

Route::get('phattangthems', 'DanhMucController@getPhatTangThem')->name('category.phattangthems');
Route::post('phattangthems', 'DanhMucController@addPhatTangThem')->name('category.phattangthems');
Route::put('phattangthems/{id}', 'DanhMucController@updatePhatTangThem')->name('category.phattangthems');
Route::delete('phattangthems/{id}', 'DanhMucController@deletePhatTangThem')->name('category.phattangthems');

Route::get('coquantochucs', 'DanhMucController@indexCoQuanToChuc')->name('category.coquantochucs');
Route::post('coquantochucs', 'DanhMucController@addCoQuanToChuc')->name('category.coquantochucs');
Route::put('coquantochucs/{id}', 'DanhMucController@updateCoQuanToChuc')->name('category.coquantochucs');
Route::delete('coquantochucs/{id}', 'DanhMucController@deleteCoQuanToChuc')->name('category.coquantochucs');
Route::get('asynctochuc', 'DanhMucController@async')->name('asynctochuc');
//
Route::get('loaithutuchanhchinhs', 'DanhMucController@indexLoaiTTHC')->name('category.loaithutuchanhchinhs');
Route::post('loaithutuchanhchinhs', 'DanhMucController@addLoaiTTHC')->name('category.loaithutuchanhchinhs');
Route::put('loaithutuchanhchinhs/{id}', 'DanhMucController@updateLoaiTTHC')->name('category.loaithutuchanhchinhs');
Route::delete('loaithutuchanhchinhs/{id}', 'DanhMucController@deleteLoaiTTHC')->name('category.loaithutuchanhchinhs');

Route::get('luuvucsongs', 'DanhMucController@indexLuuVucSong')->name('category.luuvucsongs');
Route::post('luuvucsongs', 'DanhMucController@addLuuVucSong')->name('category.luuvucsongs');
Route::put('luuvucsongs/{id}', 'DanhMucController@updateLuuVucSong')->name('category.luuvucsongs');
Route::delete('luuvucsongs/{id}', 'DanhMucController@deleteLuuVucSong')->name('category.luuvucsongs');
//
Route::get('vungkinhtetrongdiems', 'DanhMucController@indexVungKinhTe')->name('category.vungkinhtetrongdiems');
Route::post('vungkinhtetrongdiems', 'DanhMucController@addVungKinhTeTrongDiem')->name('category.vungkinhtetrongdiems.add');
Route::put('vungkinhtetrongdiems/{id}', 'DanhMucController@updateVungKinhTeTrongDiem')->name('category.vungkinhtetrongdiems.');
Route::delete('vungkinhtetrongdiems/{id}', 'DanhMucController@deleteVungKinhTeTrongDiem')->name('category.vungkinhtetrongdiems');

Route::get('miens', 'DanhMucController@indexMien')->name('category.miens');
Route::post('miens', 'DanhMucController@addMien')->name('category.miens');
Route::put('miens/{id}', 'DanhMucController@updateMien')->name('category.miens');
Route::delete('miens/{id}', 'DanhMucController@deleteMien')->name('category.miens');

Route::get('cacbienphapkhacphuchauquas', 'DanhMucController@indexCacBienPhapKhacPhucHauQua')->name('category.cacbienphapkhacphuchauquas');
Route::post('cacbienphapkhacphuchauquas', 'DanhMucController@addCacBienPhapKhacPhucHauQua')->name('category.cacbienphapkhacphuchauquas');
Route::put('cacbienphapkhacphuchauquas/{id}', 'DanhMucController@updateCacBienPhapKhacPhucHauQua')->name('category.cacbienphapkhacphuchauquas');
Route::delete('cacbienphapkhacphuchauquas/{id}', 'DanhMucController@deleteCacBienPhapKhacPhucHauQua')->name('category.cacbienphapkhacphuchauquas');

Route::get('loaixuphatbosungs', 'DanhMucController@indexLoaiXuPhatBoSung')->name('category.loaixuphatbosungs');
Route::post('loaixuphatbosungs', 'DanhMucController@addLoaiXuPhatBoSung')->name('category.loaixuphatbosungs');
Route::put('loaixuphatbosungs/{id}', 'DanhMucController@updateLoaiXuPhatBoSung')->name('category.loaixuphatbosungs');
Route::delete('loaixuphatbosungs/{id}', 'DanhMucController@deleteLoaiXuPhatBoSung')->name('category.loaixuphatbosungs');

Route::get('tinhthanhs', 'DanhMucController@indexTinhThanh')->name('category.tinhthanhs');
Route::post('tinhthanhs', 'DanhMucController@addTinhThanh');
Route::put('tinhthanhs/{id}', 'DanhMucController@updateTinhThanh')->name('category.tinhthanhs.update');
Route::delete('tinhthanh/{id}', 'DanhMucController@deleteTinhThanh');

Route::get('quanhuyens', 'DanhMucController@indexQuanHuyen')->name('category.quanhuyens');
Route::get('quanhuyen', 'DanhMucController@getQuanHuyen');
Route::put('quanhuyens/{id}', 'DanhMucController@updateQuanHuyen')->name('category.quanhuyens.update');
Route::post('quanhuyens', 'DanhMucController@addQuanHuyen');
Route::delete('quanhuyen/{id}', 'DanhMucController@deleteQuanHuyen');
Route::get('allquanhuyen', 'DanhMucController@getAllQuanHuyen');

Route::get('async-phuong-xa', 'DanhMucController@listXaPhuong');
Route::get('phuongxa', 'DanhMucController@getPhuongXa');
Route::get('allphuongxa', 'DanhMucController@getAllPhuongXa');
Route::post('phuongxa', 'DanhMucController@addPhuongXa');
Route::put('phuongxas/{id}', 'DanhMucController@updatePhuongXa');
Route::delete('phuongxa/{id}', 'DanhMucController@deletePhuongXa');

Route::get('khucongnghieps', 'DanhMucController@indexKhuCongNghiep')->name('category.khucongnghieps');
Route::get('asynkhucongnghiep', 'DanhMucController@asynKhuCongNghiep');
Route::post('khucongnghiep/add', 'DanhMucController@addKhuCongNghiep')->name('khucongnghiep.add');
Route::put('khucongnghiep/update/{id}', 'DanhMucController@updateKhuCongNghiep')->name('khucongnghiep.update');
Route::delete('khucongnghiep/delete/{id}', 'DanhMucController@deleteKhuCongNghiep')->name('khucongnghiep.delete');

Route::get('dieus', 'DanhMucController@indexDieu')->name('category.dieus');
Route::get('standardizedaddress', 'AddressController@standardizedaddress');

Route::get('cososanxuats', 'CoSoSanXuatController@getCoSoSanXuat')->name('cososanxuats');
Route::get('dataCososanxuats', 'CoSoSanXuatController@getDataIndex');
Route::get('asynorganization', 'CoSoSanXuatController@asynOrganization');
Route::get('cososanxuats/organization', 'CoSoSanXuatController@coSoSanXuat');
Route::get('cososanxuat', 'CoSoSanXuatController@index')->name('cososanxuat');
Route::get('cososanxuat/tracuu', 'CoSoSanXuatController@traCuu')->name('cososanxuat.tracuu');
Route::get('cososanxuat/showformadd', 'CoSoSanXuatController@showFormAdd')->name('cososanxuat.showformadd');
Route::post('cososanxuat/add', 'CoSoSanXuatController@add')->name('cososanxuat.add');
Route::get('cososanxuat/showformupdate/{id}', 'CoSoSanXuatController@showFormUpdate')->name('cososanxuat.showformupdate');
Route::put('cososanxuat/update/{id}', 'CoSoSanXuatController@update')->name('cososanxuat.update');
Route::delete('cososanxuat/delete/{id}', 'CoSoSanXuatController@delete')->name('cososanxuat.delete');
Route::get('cosothanhtra/{id}', 'CongTacThanhTraController@showCoSoThanhTra')->name('cosothanhtra');
Route::post('cososanxuat/getlatlon', 'CoSoSanXuatController@getLatLonByAddress');

Route::get('danhsachcoso', 'CoSoSanXuatController@showDanhSachCoSoSanXuat')->name('danhsachcoso');
Route::get('cososanxuat/add', 'CoSoSanXuatController@showFormAddCoSo');
Route::post('cososanxuat/add-co-so', 'CoSoSanXuatController@addCoSoSanXuat')->name('danhsachcoso.addcoso');
Route::get('cososanxuat/update/{id}', 'CoSoSanXuatController@showUpdateCoSo')->name('danhsachcoso.update');
Route::post('cososanxuat/update-co-so/{id}', 'CoSoSanXuatController@updateCoSoSanXuat')->name('danhsachcoso.updatecoso');
Route::get('cososanxuat/ketquaqtmt/{id}', 'CoSoSanXuatController@showKetQuaQTMT');

Route::get('detaildanhsachcoso/{id}', 'CoSoSanXuatController@detailCoSoSanXuat');
Route::get('getdanhsachcoso', 'CoSoSanXuatController@getDanhSachCoSo');

Route::get('chedothanhtras', 'CongTacThanhTraController@indexCheDoThanhTra')->name('chedothanhtras');

Route::get('doanthanhtra', 'CongTacThanhTraController@indexDoanThanhTra')->name('doanthanhtra');
Route::get('doanthanhtra/update/{id}', 'CongTacThanhTraController@showUpdateDoanThanhTra')->name('show.doanthanhtra.update');
Route::put('doanthanhtra/update/{id}', 'CongTacThanhTraController@updateDoanThanhTra')->name('doanthanhtra.update');
Route::delete('doanthanhtra/delete/{id}', 'CongTacThanhTraController@deleteDoanThanhTra')->name('doanthanhtra.delete');
Route::get('doanthanhtra/add', 'CongTacThanhTraController@showAddDoanThanhTra')->name('show.doanthanhtra.add');
Route::post('doanthanhtra/add', 'CongTacThanhTraController@addDoanThanhTra')->name('doanthanhtra.add');
Route::get('asyncdoanthanhtra', 'CongTacThanhTraController@asyncdoanthanhtra')->name('asyncdoanthanhtra');



Route::get('baocaotonghop', 'BaoCaoController@baocaotonghop')->name('baocaotonghop');
Route::get('baocaotonghop/xemtruoc', 'BaoCaoController@showPreview')->name('baocaotonghop.xemtruoc');
Route::get('baocaotonghop/export/excel', 'BaoCaoController@exportExcel')->name('baocaotonghop.export.excel');
Route::get('baocaotonghop/dulieuxemtruoc', 'BaoCaoController@getPreview')->name('baocaotonghop.xemtruoc.get');
Route::get('excel/baocaotonghop', 'BaoCaoController@xuatExcelBaoCao')->name('xuatexcel');
Route::post('cososanxuat/diadiemhoatdong/delete/{id}', 'CoSoSanXuatController@deleteDiaDiemHoatDong');

Route::delete('file/delete/{id}', 'FileController@delete')->name('file.delete');
Route::get('file/download/{id}', 'FileController@download')->name('file.download');
Route::post('file/add', 'FileController@addFile')->name('file.add');

Route::get('quyetdinhthanhtras', 'CongTacThanhTraController@indexQuyetDinhThanhTras')->name('quyetdinhthanhtras');
Route::get('cososanxuatbythanhtras', 'CongTacThanhTraController@indexCoSoSanXuatByThanhTras')->name('cososanxuatbythanhtras');
Route::get('chatthais/{id}', 'CongTacThanhTraController@getChatThais')->name('chatthais');

Route::get('danhmuc/chuyendoidonvis', 'DanhMucController@indexChuyenDoiDonVi')->name('danhmuc.chuyendoidonvis');
Route::get('luuluongnuocthai', 'DanhMucController@luuluongnuocthai')->name('danhmuc.luuluongnuocthai');
Route::put('updateluuluongnc/{id}', 'DanhMucController@updateLuuLuongNuocThai')->name('danhmuc.updateLuuLuongNuocThai');
Route::put('updatedonvigoc', 'DanhMucController@updateDonViGoc')->name('danhmuc.updatedonvigoc');
Route::delete('deleteluuluongnuoc/{id}', 'DanhMucController@deleteLuuLuongNuocThai')->name('danhmuc.deleteLuuLuongNuocThai');
Route::post('addluuluongnuocthai', 'DanhMucController@addLuuLuongNuocThai')->name('danhmuc.addLuuLuongNuocThai');
Route::get('luuluongkhithai', 'DanhMucController@luuluongkhithai')->name('danhmuc.luuluongkhithai');
Route::post('addluuluongkhithai', 'DanhMucController@addLuuLuongKhiThai')->name('danhmuc.addluuluongkhithai');
Route::get('congsuatxulykhithai', 'DanhMucController@congsuatxulykhithai')->name('danhmuc.congsuatxulykhithai');
Route::post('addcongsuatkhithai', 'DanhMucController@addcongsuatkhithai')->name('danhmuc.addcongsuatkhithai');
Route::get('congsuatxulynuocthai', 'DanhMucController@congsuatxulynuocthai')->name('danhmuc.congsuatxulynuocthai');
Route::post('addcongsuatnuocthai', 'DanhMucController@addCongSuatNuocThai')->name('danhmuc.addCongSuatNuocThai');
Route::get('khoiluongphatsinhchatthai', 'DanhMucController@khoiluongphatsinhchatthai')->name('danhmuc.khoiluongphatsinhchatthai');
Route::post('addphatsinhchatthai', 'DanhMucController@addphatsinhchatthai')->name('danhmuc.addphatsinhchatthai');
Route::get('congsuathethong', 'DanhMucController@congsuathethong')->name('danhmuc.congsuathethong');
Route::post('addcongsuathethong', 'DanhMucController@addcongsuathethong')->name('danhmuc.addcongsuathethong');
Route::get('congsuatthietke', 'DanhMucController@congsuatthietke')->name('danhmuc.congsuatthietke');
Route::post('addcongsuatthietke', 'DanhMucController@addcongsuatthietke')->name('danhmuc.addcongsuatthietke');
Route::get('dientich', 'DanhMucController@dientich')->name('danhmuc.dientich');
Route::post('adddientich', 'DanhMucController@AddDienTich')->name('danhmuc.adddientich');
Route::get('thongsovuotchuan', 'DanhMucController@thongSoVuotChuan')->name('danhmuc.thongsovuotchuan');
Route::post('addthongsovuotchuan', 'DanhMucController@addThongSoVuotChuan')->name('danhmuc.addthongsovuotchuan');

Route::get('getloaicoso', 'DanhMucController@getLoaiCoSo');
Route::post('addloaicoso', 'DanhMucController@addLoaiCoSo');
Route::put('updateloaicoso/{id}', 'DanhMucController@updateLoaiCoSo');
Route::delete('deleteloaicoso/{id}', 'DanhMucController@deleteLoaiCoSo');

Route::get('cacbienphapkhacphucviphams', 'KhacPhucViPhamController@cacBienPhapKhacPhucViPham')->name('cacbienphapkhacphucviphams');

Route::get('cososanxuat/xuatexcel/{id}', 'CoSoSanXuatController@xuatExcel');
Route::get('cososanxuat/exports/excel', 'CoSoSanXuatController@index');
Route::get('doanthanhtra/exports/excel', 'CongTacThanhTraController@indexDoanThanhTra');
Route::get('ketquathanhtra/exports/excel', 'CongTacThanhTraController@indexKetQuaThanhTra');
Route::get('ketquathanhtra/excel/{id}', 'CongTacThanhTraController@exportKetQuaThanhTra');
Route::get('quyetdinhthanhlapdoan/excel/{id}', 'CongTacThanhTraController@exportExcel');
Route::resource('danhmuc/donvi', 'DonViController', ['only' => [
    'store',
    'update',
    'destroy',
    'index',
]]);

Route::resource('danhmuc/thongsovuotchuan', 'DanhMucThongSoVuotChuanController', ['only' => [
    'store',
    'update',
    'destroy',
    'index',
]]);

Route::resource('danhmuc/loaihinhquantrac', 'LoaiHinhQuanTracController', ['only' => [
    'store',
    'update',
    'destroy',
    'index',
]]);
Route::get('cache', 'SystemController@cache');

Route::get('{action}/redirect/{social}', 'SocialAuthController@redirect');
Route::get('/callback/{social}', 'SocialAuthController@callback');

Route::view('dtm', 'dtm')->name('documents.dtm');

Route::get('treeloaihinh', 'DanhMucController@treeLoaiHinhCoSo');
Route::get('listloaihinhhoatdong', 'DanhMucController@getSelectLoaiHinhHoatDong');
Route::get('listloainganhnghe', 'DanhMucController@getSelectNganhNghe');

Route::get('demquyetdinh', 'DanhMucController@countQDTTchuaDongBo');

Route::get('moitruongquocgia/loainganhnghekinhte', 'MoiTruongQuocGiaController@dongBoLoaiNganhNgheKinhTe');
Route::get('moitruongquocgia/luuvucsong', 'MoiTruongQuocGiaController@dongBoLuuVucSong');
Route::get('moitruongquocgia/loaihinhoinnhiem', 'MoiTruongQuocGiaController@dongBoLoaiHinhNguyCoOiNhiem');
Route::get('moitruongquocgia/dongbothongso', 'MoiTruongQuocGiaController@dongBoThongSo');
//Route::put('thoigiandongbo', 'ThoiGianDongBoController@thoigiandongbo');
Route::get('inthoigian/{type}', 'ThoiGianDongBoController@getThoiGianDongBo');
Route::get('moitruongquocgia/loaikhucongnghiep', 'MoiTruongQuocGiaController@dongBoLoaiKhuCongNghiep');
Route::get('moitruongquocgia/tinhthanh', 'MoiTruongQuocGiaController@dongboTinhThanh');
Route::get('moitruongquocgia/cosomtquocgia', 'MoiTruongQuocGiaController@importDuLieuCoSoQuocGia');
Route::get('moitruongquocgia/vungmien', 'MoiTruongQuocGiaController@dongBoMien');
Route::get('moitruongquocgia/test', 'MoiTruongQuocGiaController@testDongBo');
Route::get('moitruongquocgia/dongbocoso', 'MoiTruongQuocGiaController@dongBoCoSo');
Route::get('moitruongquocgia/dongbovanbandtm', 'MoiTruongQuocGiaController@dongBoDTM');
Route::get('moitruongquocgia/dongbogiayphepmoitruong', 'MoiTruongQuocGiaController@DongboGiayPhepMoiTruong');
Route::get('moitruongquocgia/quanhuyen', 'MoiTruongQuocGiaController@dongBoQuanHuyen');
Route::get('moitruongquocgia/xaphuong', 'MoiTruongQuocGiaController@dongBoXaPhuong');
Route::get('moitruongquocgia/dongbochudautu', 'MoiTruongQuocGiaController@dongBoChuDauTu');
// Route::get('moitruongquocgia/dongbohanhvi', 'MoiTruongQuocGiaController@dongBoHanhVi');
Route::get('moitruongquocgia/dongbonghidinh155', 'MoiTruongQuocGiaController@dongBoNghiDinh155');
Route::get('moitruongquocgia/dongboquyetdinh', 'MoiTruongQuocGiaController@dongBoQuyetDinhThanhLap');
Route::get('moitruongquocgia/getcoquan', 'MoiTruongQuocGiaController@getCoQuan');
Route::get('moitruongquocgia/dongboketluanthanhtra', 'MoiTruongQuocGiaController@dongBoKetQuaThanhTra');
Route::get('moitruongquocgia/dongboquyetdinhxuphat', 'MoiTruongQuocGiaController@dongBoQuyetDinhXuPhat');
Route::get('moitruongquocgia/dongbodiemtramQTMT', 'MoiTruongQuocGiaController@dongBoDiemTramQTMT');

Route::post('moitruongquocgia/create/quyetdinh', 'CreateMoiTruongQuocGiaController@createQuyetDinh');
Route::post('moitruongquocgia/create/ketluanthanhtra', 'CreateMoiTruongQuocGiaController@createKetLuanThanhTra');


Route::get('ketquathanhtra/add', 'CongTacThanhTraController@showFromAddKetQuaThanhTra')->name('ketquathanhtra.add');
Route::get('ketquathanhtra/edit/{id}', 'CongTacThanhTraController@showFromEditKetQuaThanhTra')->name('ketquathanhtra.edit');
Route::put('ketquathanhtra/update/{id}', 'CongTacThanhTraController@updateKetQuaThanhTra')->name('ketquathanhtra.update');
Route::delete('ketquathanhtra/delete/{id}', 'CongTacThanhTraController@deleteKetQuaThanhTra')->name('ketquathanhtra.delete');
Route::post('ketquathanhtra/add', 'CongTacThanhTraController@addKetQuaThanhTra')->name('ketquathanhtra.add');
Route::get('ketquathanhtra/file/{id}', 'FileController@getFileByKetLuanThanhTra')->name('ketquathanhtra.file');

Route::get('ketquaphantichmau', 'KetQuaPhanTichMauController@viewIndex')->name('ketquaphantichmau');
Route::get('ketquaphantichmau/add', 'KetQuaPhanTichMauController@showFromAdd')->name('showadd.ketquaphantichmau');
Route::get('ketquaphantichmau/edit/{id}', 'KetQuaPhanTichMauController@showFromEdit')->name('showedit.ketquaphantichmau');
Route::post('ketquaphantichmau', 'KetQuaPhanTichMauController@addKetQuaPhanTichMau')->name('add.ketquaphantichmau');
Route::get('ketquaphantichmau/get', 'KetQuaPhanTichMauController@getKetQuaPhanTichMau')->name('ketquaphantichmau.get');
Route::post('ketquaphantichmau/{id}', 'KetQuaPhanTichMauController@updateKetQuaPhanTichMau')->name('ketquaphantichmau.edit');
Route::delete('ketquaphantichmau/delete/{id}', 'KetQuaPhanTichMauController@deleteKetQuaPhanTichMau')->name('ketquaphantichmau.delete');

Route::get('dongbocsdl', 'TrangThaiDongBoCsdlController@viewIndex')->name('dongbocsdl');
Route::get('dongbocsdl/getTrangThaiDongBo', 'TrangThaiDongBoCsdlController@getTrangThaiDongBoCsdl')->name('dongbocsdl.getTrangThaiDongBo');
Route::put('dongbocsdl/{id}', 'TrangThaiDongBoCsdlController@updateTrangThaiDongBoCsdl')->name('dongbocsdl.edit');
Route::get('pdfanalysis', 'PdfAnalysisController@index')->name('pdfanalysis');
Route::post('pdfanalysis', 'PdfAnalysisController@pdfAnalysis');
Route::get('dongbocsdl/logs', 'TrangThaiDongBoCsdlController@getLogDongBoCsdl');

Route::post('checkpdfresult', 'PdfAnalysisController@checkPdfResult');
Route::get('moitruongquocgia/dongboketquaQTMT', 'MoiTruongQuocGiaController@dongBoKetQuaQTMT');

Route::get('getLoaiThongSo', 'DanhMucController@getLoaiThongSo');
