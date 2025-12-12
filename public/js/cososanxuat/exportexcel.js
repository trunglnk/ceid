function downloadExcel() {
    var btnXuatExcel=document.getElementById('btnXuatExcel');
    btnXuatExcel.disabled=true;
    var ajax = new XMLHttpRequest();
    ajax.responseType = 'blob';

    ajax.addEventListener("load", function (e) {             
        if(e.target.status == 200) {                        
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
        btnXuatExcel.disabled=false;
    }, false);

    ajax.addEventListener("error", function (e) {
        btnXuatExcel.disabled="false";
        
    }, false);

    ajax.addEventListener("abort", function (e) {
        btnXuatExcel.disabled=false;
        
    }, false);

    var searchMien = document.getElementById('search_mien')
    var searchLuuVucSong = document.getElementById('search_luu_vuc_song');
    var searchQuanHuyen = document.getElementById('search_quan_huyen');
    var searchVungKinhTeTrongDiem = document.getElementById('search_vung_kinh_te_trong_diem');
    var searchTinhThanh = document.getElementById('search_tinh_thanh');
    var searchKhuCongNghiep = document.getElementById('search_khu_cong_nghiep');
    var searchLoaiHinhCoSo = document.getElementById('search_loai_hinh_co_so');
    var order_column = document.getElementById('order_column');
    var order_direction = document.getElementById('order_direction');
    var input_cososanxuat = document.getElementById('input_cososanxuat');
    var input_masoDKKD = document.getElementById('input_masoDKKD');
    var input_year = document.getElementById('input_year');
    var uploaderForm = new FormData();  
    var strUrl='?'; 

   if(searchMien){
    let resultMien=this.getSelectValues(searchMien);
    for(let i=0;i<resultMien.length;i++){
     strUrl+='search_mien[]='+resultMien[i]+'&&';
    }
   }

    if(order_column && order_column.value!=''){
      strUrl+='order_column='+order_column.value+'&&';
    } 

  if( order_direction&& order_direction.value!=''){
      strUrl+='order_direction='+order_direction.value+'&&';
  }

  if( input_cososanxuat&& input_cososanxuat.value!=''){
    strUrl+='search_tencososanxuat='+input_cososanxuat.value+'&&';
  }

  if( input_masoDKKD&& input_masoDKKD.value!=''){
    strUrl+='search_masothue='+input_masoDKKD.value+'&&';
  }

  if( input_year&& input_year.value!=''){
    strUrl+='search_year='+input_year.value+'&&';
  }
   
   if(searchLuuVucSong){
    let resultLuuVucSong=this.getSelectValues(searchLuuVucSong);
    for(let i=0;i<resultLuuVucSong.length;i++){
     strUrl+='search_luu_vuc_song[]='+resultLuuVucSong[i]+'&&';
    }
   }
   if(searchQuanHuyen){
    let resultQuanHuyen=this.getSelectValues(searchQuanHuyen);
    for(let i=0;i<resultQuanHuyen.length;i++){
     strUrl+='search_quan_huyen[]='+resultQuanHuyen[i]+'&&';
    }
   }

   if(searchVungKinhTeTrongDiem){
    let resultVungKinhTeTrongDiem=this.getSelectValues(searchVungKinhTeTrongDiem);
    for(let i=0;i<resultVungKinhTeTrongDiem.length;i++){
     strUrl+='search_vung_kinh_te_trong_diem[]='+resultVungKinhTeTrongDiem[i]+'&&';
    }
   }
   
   if(searchTinhThanh){
    let resultTinhThanh=this.getSelectValues(searchTinhThanh);
    for(let i=0;i<resultTinhThanh.length;i++){
     strUrl+='search_tinh_thanh[]='+resultTinhThanh[i]+'&&';
    }
   }
  
   if(searchKhuCongNghiep){
    let resultKhuCN=this.getSelectValues(searchKhuCongNghiep);
    for(let i=0;i<resultKhuCN.length;i++){
     strUrl+='search_khu_cong_nghiep[]='+resultKhuCN[i]+'&&';
    }
   }

   if(searchLoaiHinhCoSo){
    let resultLoaiHinhCoSo=this.getSelectValues(searchLoaiHinhCoSo);
    for(let i=0;i<resultLoaiHinhCoSo.length;i++){
     strUrl+='search_loai_co_so[]='+resultLoaiHinhCoSo[i]+'&&';
    }
   }
   ajax.open("GET", "cososanxuat/exports/excel" + strUrl + "");
     ajax.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr("content"));
     ajax.setRequestHeader("Accept", "xlsx");  
    ajax.send(uploaderForm);     
}

function getSelectValues(select) {
    var result = [];
    var options = select && select.options;
    var opt;
  
    for (var i=0, iLen=options.length; i<iLen; i++) {
      opt = options[i];
  
      if (opt.selected) {
        result.push(opt.value || opt.text);
      }
    }
    return result;
  }