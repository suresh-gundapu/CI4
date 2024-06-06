$(function () {

    $('#username').focus();
  
    $("#form-login").validate({
      rules: {
        "username": "required",
        "password": "required",
      },
      messages: {
  
        "username": "Please enter Username",
        "password": "Please enter Password",
      },
      submitHandler: function () {
        var formData = new FormData($('#form-login')[0]);
        $(".login").attr("disabled", "disabled");
        $.ajax({
          url: base_url + "users/check-user",
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
                window.location.href = base_url + "users/dashboard";
              }, 1000);
              $(".login").removeAttr("disabled");
  
            } else {
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
  
    $("#frgt-pwd-mdl").validate({
      rules: {
        "email": "required",
      },
      messages: {
  
        "email": "Please enter email",
      }
    });
    $('.frgt-pwd-mdl').on('click', function (e) {
  
      e.preventDefault();
      var validator = $("#frgt-pwd-mdl").validate();
      validator.form();
      if (validator.form() == true) {
        var formData = new FormData($('#frgt-pwd-mdl')[0]);
        $(".frgt-pwd-mdl").attr("disabled", "disabled");
        $.ajax({
          url: base_url + "admin/check-user-email",
          type: "POST",
          cache: false,
          data: formData,
          processData: false,
          contentType: false,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          dataType: "JSON",
          success: function (data) {
            $(".frgt-pwd-mdl").removeAttr("disabled");
            console.log(data);
            if (data.success == true) {
              Swal.fire({
                icon: 'success',
                title: 'Verified...',
                text: data.msg,
              });
              setTimeout(function () {
                window.location.href = base_url + "users/forgot-pwd";
              }, 1000);
            } else {
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
  
  