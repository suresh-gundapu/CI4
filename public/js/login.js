$(function () {


  $("#form-login").validate({
    rules: {
      "username": "required",
      "password": "required",
    },
    messages: {
      "username": "Please enter Username",
      "password": "Please enter Password",
    },

    errorPlacement: function (error, element) {
      if (element.attr('name') === 'username') {
        $("#username_err").html(error);
      } else {
        error.insertAfter(element.parent());
      }
      if (element.attr('name') === 'password') {
        $("#password_err").html(error);
      }
    },
    submitHandler: function () {
      var formData = new FormData($('#form-login')[0]);
      $(".login").attr("disabled", "disabled");
      $.ajax({
        url: base_url + "admin/check-user",
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
            $(".login").removeAttr("disabled");

          } else if (data.success === 3) {
            window.location.href = base_url + "admin/otp-authentication";
          }
          else {
            Swal.fire({
              icon: 'error',
              title: 'Error...',
              text: data.msg,
            });
            $(".login").removeAttr("disabled");

          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
      });
    }
  });

  $("#frgt-pwd-mdl").validate({
    rules: {
      "username": "required",
    },
    messages: {

      "username": "Please enter Username",
    },
    errorPlacement: function (error, element) {
      if (element.attr('name') === 'username') {
        $("#username_err").html(error);
      } else {
        error.insertAfter(element.parent());
      }
   
    },
  });
  $('.frgt-pwd-mdl').on('click', function (e) {

    e.preventDefault();
    var validator = $("#frgt-pwd-mdl").validate();
    validator.form();
    if (validator.form() == true) {
      var formData = new FormData($('#frgt-pwd-mdl')[0]);
      $(".frgt-pwd-mdl").attr("disabled", "disabled");
      $.ajax({
        url: base_url + "admin/forgot-password-action",
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
              title: "Success..",
              text: data.message,
            });
            setTimeout(function () {
              window.location.href = data.url;
            }, 1000);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error...',
              text: data.message,
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
      });
    }
  });

  $("#change-pwd-frm").validate({
    rules: {
      "password": { required: true },
      "retype_password": { required: true, equalTo: '[name="password"]' },
      "reset_code": "required",
    },
    messages: {
      password: {
        required: "Please enter the password."
      },

      "password": "Please Enter New Password",
      retype_password: {
        required: "Please enter the Re-type password.",
        equalTo: "New Password and Re-type Password should be same ! "
      },
      "reset_code": "Please Enter Reset Code"
    },
    errorPlacement: function (error, element) {
      error.insertAfter(element.parent());
    },
    errorPlacement: function (error, element) {
      if (element.attr('name') === 'password') {
        $("#password_err").html(error);
      } else {
        error.insertAfter(element.parent());
      }
      if (element.attr('name') === 'retype_password') {
        $("#retype_password_err").html(error);
      }
      if (element.attr('name') === 'reset_code') {
        $("#reset_code_err").html(error);
      }
    },
  });
  $('.change-pwd-frm').on('click', function (e) {

    e.preventDefault();
    var validator = $("#change-pwd-frm").validate();
    validator.form();
    if (validator.form() == true) {
      var formData = new FormData($('#change-pwd-frm')[0]);
      $(".change-pwd-frm").attr("disabled", "disabled");
      $.ajax({
        url: base_url + "admin/reset-pwd-action",
        type: "POST",
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        dataType: "JSON",
        success: function (data) {
          $(".change-pwd-frm").removeAttr("disabled");
          console.log(data);
          if (data.success == true) {
            Swal.fire({
              icon: 'success',
              title: "Success..",
              text: data.message,
            });
            setTimeout(function () {
              window.location.href = data.url;
            }, 3000);
          } else {
            Swal.fire({
              icon: 'error',
              title: "Error..",
              text: data.message,
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
      });
    }
  });
  $('#pwd_show_hide').on("click", function () {
    var status = $('#pwd_icon').attr('status');
    if (status == 'hide') {
      $('#pwd_icon').removeClass('fa-eye-slash').addClass('fa-eye').attr('status', 'show');
      $('#password').attr('type', 'text');
    } else {
      $('#pwd_icon').removeClass('fa-eye').addClass('fa-eye-slash').attr('status', 'hide');
      $('#password').attr('type', 'password');
    }
  });



});

