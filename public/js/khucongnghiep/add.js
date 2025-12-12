$(document).ready(function () {
  var quanhuyens =JSON.parse($('#quan_huyen_id').attr('data'));

  $('#tinh_thanh_id').change(function () {
    $('#quan_huyen_id option').each(function() {
      if ( $(this).val() != '' ) {
        $(this).remove();
      }
    });
    $("#tinh_thanh_id").append('<option value="-1" >Chọn </option>');
    $.each(quanhuyens, function (key, value) {
        if (value['tinh_thanh_id'] == $('#tinh_thanh_id').val()) {
          $("#quan_huyen_id").append('<option value="' + value.id + '" >' + value.ten + '</option>');
      }
    });
    if($('#tinh_thanh_id').val() == ''){
      $.each( quanhuyens, function( key, value ) {
        $("#quan_huyen_id").append('<option value="'+value.id+'" >'+value.ten+'</option>');
      });
    }
  });

  $('.tinh').change(function () {
    var tinh_id =$(this).val()
    var id =$(this).attr('id')
    $('#update_quan_huyen_id_'+$(this).attr('id')+' option').each(function() {
      if ( $(this).val() != '' ) {
        $(this).remove();
      }
    });
    $(this).append('<option value="-1" >Chọn </option>');

    $.each(quanhuyens, function (key, value) {
      if (value['tinh_thanh_id'] == tinh_id) {
        $("#update_quan_huyen_id_"+id).append('<option value="' + value.id + '" >' + value.ten + '</option>');
      }
    });
    if($('#update_tinh_thanh_id').val() == ''){
      $.each( quanhuyens, function( key, value ) {
        $("#update_quan_huyen_id_"+id).append('<option value="'+value.id+'" >'+value.ten+'</option>');
      });
    }
  });
})


