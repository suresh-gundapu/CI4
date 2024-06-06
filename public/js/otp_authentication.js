$(function () {

    $('#2fa_code').focus();
  
    $("#google_authentication").validate({
      rules: {
        "2fa_code": "required",
      },
      messages: {
        "2fa_code": "Please enter OTP",
      },
   
      errorPlacement: function (error, element) {
        if (element.attr('name') === '2fa_code') {
          $("#2fa_code_err").html(error);
        } 
      },
      submitHandler: function () {
        var formData = new FormData($('#google_authentication')[0]);
        $(".login-btn").attr("disabled", "disabled");
        $.ajax({
          url: base_url + "admin/authentication",
          type: "POST",
          cache: false,
          data: formData,
          processData: false,
          contentType: false,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          dataType: "JSON",
          success: function (data) {
            if (data.success == true) {
              Swal.fire({
                icon: 'success',
                title: 'Login...',
                text: data.msg,
              });
              setTimeout(function () {
                window.location.href = base_url + "admin/dashboard";
              }, 1000);
              $(".login-btn").removeAttr("disabled");
  
            } else if(data.success === 3) {
              window.location.href = base_url + "admin/otp-authentication";
            }
            else {
              Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: data.msg,
              });
  
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            // alert('Error at add data');
          }
        });
      }
    });
  
 
  
  
  });
  
  