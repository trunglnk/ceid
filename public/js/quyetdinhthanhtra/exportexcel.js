function downloadExcel() {
  var btnXuatExcel = document.getElementById("btnXuatExcel");
  btnXuatExcel.disabled = true;
  var ajax = new XMLHttpRequest();
  ajax.responseType = "blob";

  ajax.addEventListener(
    "load",
    function(e) {
      if (e.target.status == 200) {
        var disposition = ajax.getResponseHeader("content-disposition");
        var matches = /"([^"]*)"/.exec(disposition);
        var filename = matches != null && matches[1] ? matches[1] : "file.xlsx";

        var blob = new Blob([ajax.response], { type: "application/xlsx" });
        var link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;

        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);
      }
      btnXuatExcel.disabled = false;
    },
    false
  );

  ajax.addEventListener(
    "error",
    function(e) {
      btnXuatExcel.disabled = "false";
    },
    false
  );

  ajax.addEventListener(
    "abort",
    function(e) {
      btnXuatExcel.disabled = false;
    },
    false
  );

  var searchHinhThucThanhTra = document.getElementById("hinh_thuc_thanh_tra");
  var searchCoQuanQuyetDinh = document.getElementById("co_quan_quyet_dinh");
  var searchCoQuanThucHien = document.getElementById("co_quan_thuc_hien");
  var searchNam = document.getElementById("search_year");
  var searchThoiGianBatDau = document.getElementById("search_start_time");
  var searchThoiGianKetThuc = document.getElementById("search_end_time");

  var uploaderForm = new FormData();
  var strUrl = "?";

  if (searchHinhThucThanhTra) {
    let resultHinhThucThanhTra = this.getSelectValues(searchHinhThucThanhTra);
    for (let i = 0; i < resultHinhThucThanhTra.length; i++) {
      strUrl += "hinh_thuc_thanh_tra[]=" + resultHinhThucThanhTra[i] + "&&";
    }
  }

  if (searchCoQuanQuyetDinh) {
    let resultCoQuanQuyetDinh = this.getSelectValues(searchCoQuanQuyetDinh);
    for (let i = 0; i < resultCoQuanQuyetDinh.length; i++) {
      strUrl += "co_quan_quyet_dinh[]=" + resultCoQuanQuyetDinh[i] + "&&";
    }
  }

  if (searchCoQuanThucHien) {
    let resultCoQuanThucHien = this.getSelectValues(searchCoQuanThucHien);
    for (let i = 0; i < resultCoQuanThucHien.length; i++) {
      strUrl += "co_quan_thuc_hien[]=" + resultCoQuanThucHien[i] + "&&";
    }
  }

  if (searchNam && searchNam.value != "") {
    strUrl += "search_year=" + searchNam.value;
  }

  if (
    searchThoiGianBatDau &&
    searchThoiGianBatDau.value != "" &&
    searchNam.value == ""
  ) {
    strUrl += "search_start_time=" + searchThoiGianBatDau.value + "&&";
  }

  if (
    searchThoiGianKetThuc &&
    searchThoiGianKetThuc.value != "" &&
    searchNam.value == ""
  ) {
    strUrl += "search_end_time=" + searchThoiGianKetThuc.value + "&&";
  }

  ajax.open("GET", "doanthanhtra/exports/excel" + strUrl + "");
  ajax.setRequestHeader(
    "X-CSRF-Token",
    $("meta[name='csrf-token']").attr("content")
  );
  ajax.setRequestHeader("Accept", "xlsx");
  ajax.send(uploaderForm);
}

function getSelectValues(select) {
  var result = [];
  var options = select && select.options;
  var opt;

  for (var i = 0, iLen = options.length; i < iLen; i++) {
    opt = options[i];

    if (opt.selected) {
      result.push(opt.value || opt.text);
    }
  }
  return result;
}
