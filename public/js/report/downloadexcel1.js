function download(route, buttonId = 'btnXuatExcel', param = Array) {
    if (route != undefined) {
        var btnXuatExcel = document.getElementById(buttonId);
        if (btnXuatExcel) {
            btnXuatExcel.disabled = true;
        }

        var ajax = new XMLHttpRequest();
        ajax.responseType = 'blob';

        var searchMien = document.getElementById('search_mien');
        var searchTinhThanh= document.getElementById('search_tinh_thanh');
        var searchLuuVucSong = document.getElementById('search_luu_vuc_song');
        var searchKtTrongDiem= document.getElementById('search_kte_trong_diem');
        var searchDoanThanhTra = document.getElementById('search_doan_thanh_tra');
        var searchTuNgay = document.getElementById('search_tu_ngay');
        var searchDenNgay = document.getElementById('search_den_ngay');

        ajax.addEventListener("load", function (e) {
            if (btnXuatExcel) {
                btnXuatExcel.disabled = false;
            }
            if (e.target.status == 200) {
                var disposition = ajax.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'file.xlsx');

                var blob = new Blob([ajax.response], { type: 'application/xlsx' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();

                document.body.removeChild(link);
            }
        }, false);

        ajax.addEventListener("error", function (e) {
            if (btnXuatExcel) {
                btnXuatExcel.disabled = true;
            }
        }, false);

        ajax.addEventListener("abort", function (e) {
            if (btnXuatExcel) {
                btnXuatExcel.disabled = true;
            }
        }, false);
        var strUrl = '?';
        if(param!=undefined&&param!=null){
            
            param.forEach(element => {
    
            });
            for (var key in param) {
                strUrl = strUrl + key + '=' + param[key] + '&';
            }
        }
        if(searchMien&&searchMien.value){
            strUrl+='search_mien='+searchMien.value+'&&';
          }
        if(searchTinhThanh&&searchTinhThanh.value){
        strUrl+='search_tinh_thanh='+searchTinhThanh.value+'&&';
        }
        if(searchLuuVucSong&&searchLuuVucSong.value){
            strUrl+='search_luu_vuc_song='+searchLuuVucSong.value+'&&';
            }
        if(searchKtTrongDiem && searchKtTrongDiem.value!=''){
            strUrl+='search_kte_trong_diem='+searchKtTrongDiem.value+'&&';
        }
        if(searchTuNgay && searchTuNgay.value!=''){
        strUrl+='search_tu_ngay='+searchTuNgay.value+'&&';
        }
        if(searchDenNgay && searchDenNgay.value!=''){
        strUrl+='search_den_ngay='+searchDenNgay.value+'&&';
        }
        ajax.open("get", route + strUrl);
        ajax.setRequestHeader('Accept', 'xlsx');
        ajax.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr("content"));

        var uploaderForm = new FormData();
        ajax.send(uploaderForm);
    }
}