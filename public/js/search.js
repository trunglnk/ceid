$(document).ready(function () {
  var tinhs =JSON.parse($('#search_tinh_thanh').attr('data'));
  if($('#search_quan_huyen').attr('data')){
    var quanhuyens =JSON.parse($('#search_quan_huyen').attr('data'));
  }
  

$('#search_mien').change(function () {
  $('#search_tinh_thanh option').each(function() {
    if ( $(this).val() != '' ) {
      $(this).remove();
    }
  });
  $("#search_tinh_thanh").append('<option value="-1" >Chọn </option>');
  $.each(tinhs, function (key, value) {
    $.each($('#search_mien').val(), function (key2, value2) {
      if (value['mien_id'] == value2) {
        $("#search_tinh_thanh").append('<option value="' + value.id + '" >' + value.ten + '</option>');
      }
    });
  });
  if($('#search_mien').val() == ''){
    $.each( tinhs, function( key, value ) {
      $("#search_tinh_thanh").append('<option value="'+value.id+'" >'+value.ten+'</option>');
    });
  }
});


$('#search_tinh_thanh').change(function () {
  $('#search_quan_huyen option').each(function() {
    if ( $(this).val() != '' ) {
      $(this).remove();
    }
  });
  $("#search_tinh_thanh").append('<option value="-1" >Chọn </option>');
  $.each(quanhuyens, function (key, value) {
    $.each($('#search_tinh_thanh').val(), function (key2, value2) {
      if (value['tinh_thanh_id'] == value2) {
        $("#search_quan_huyen").append('<option value="' + value.id + '" >' + value.ten + '</option>');
      }
    });
  });
  if($('#search_tinh_thanh').val() == ''){
    $.each( quanhuyens, function( key, value ) {
      $("#search_quan_huyen").append('<option value="'+value.id+'" >'+value.ten+'</option>');
    });
  }
});
})


