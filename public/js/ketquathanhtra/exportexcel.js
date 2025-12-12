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
  var searchNam = document.getElementById("search_year");
  var searchThoiGianBatDau = document.getElementById("search_time_start");
  var searchThoiGianKetThuc = document.getElementById("search_time_end");
  var order_column = document.getElementById("order_column");
  var order_direction = document.getElementById("order_direction");

  var uploaderForm = new FormData();
  var strUrl = "?";

  let resultQuyetDinhThanhTra = document.getElementsByName(
    "search_quyet_dinh_thanh_tra[]"
  );
  for (let i = 0; i < resultQuyetDinhThanhTra.length; i++) {
    if (resultQuyetDinhThanhTra[i].value) {
      strUrl +=
        "search_quyet_dinh_thanh_tra[]=" +
        resultQuyetDinhThanhTra[i].value +
        "&&";
    }
  }

  let resultToChuc = document.getElementsByName("search_to_chuc[]");
  for (let i = 0; i < resultToChuc.length; i++) {
    if (resultToChuc[i].value) {
      strUrl += "search_to_chuc[]=" + resultToChuc[i].value + "&&";
    }
  }

  if (searchNam && searchNam.value != "") {
    strUrl += "search_year=" + searchNam.value + "&&";
  }

  if (order_column && order_column.value != "") {
    strUrl += "order_column=" + order_column.value + "&&";
  }

  if (order_direction && order_direction.value != "") {
    strUrl += "order_direction=" + order_direction.value + "&&";
  }

  if (
    searchThoiGianBatDau &&
    searchThoiGianBatDau.value != "" &&
    searchNam.value == ""
  ) {
    strUrl += "search_time_start=" + searchThoiGianBatDau.value + "&&";
  }

  if (
    searchThoiGianKetThuc &&
    searchThoiGianKetThuc.value != "" &&
    searchNam.value == ""
  ) {
    strUrl += "search_time_end=" + searchThoiGianKetThuc.value + "&&";
  }

  ajax.open("GET", "ketquathanhtra/exports/excel" + strUrl + "");
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
