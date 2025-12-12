$(document).ready(function() {
  var district = JSON.parse($("#data_district").attr("data"));
  $("#province").change(function() {
    $("#district option").each(function() {
      if ($(this).val() != "") {
        $(this).remove();
      }
    });
    $.each(district, function(key, value) {
      if (value["province_id"] == $("#province").val()) {
        $("#district").append(
          '<option value="' + value.id + '" >' + value.name + "</option>"
        );
      }
    });
  });

  $('#address').change(function() {
    $.ajax({
      global: true,
      type: "GET",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/standardizedaddress?address=" + $(this).val(),      
      beforeSend: function () {
        $(this).disabled = true;
      },
      success: function (response) {                                         
        if (response.result.province && response.result.province.id) {
          $('#province').val(+response.result.province.id).trigger("change");
        }
        if (response.result.district && response.result.district.id) {
          $('#district').val(+response.result.district.id).trigger("change");
        }                           
      },
      error: function (error) {
        console.log(error);
      },
      complete: function () {
        $(this).disabled = false;
      }
    });
  });
});
