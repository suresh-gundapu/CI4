
$(function () {
    // debugger;
     $("#profile-frm").validate({
         rules: {
             "name": "required",
             "email": "required",
             "mobile_no": "required",
             "user_name": "required",
         },
         messages: {
             "name": "Please enter Name",
             "email": "Please enter Email",
             "mobile_no": "Please enter Mobile Number",
             "user_name": "Please enter UserName"
         },
         submitHandler:function(){
 
             var formData = new FormData($('#profile-frm')[0]);
             $(".update_profile").attr("disabled", "disabled");
             $.ajax({
                 url: base_url + "users/update-profile",
                 type: "POST",
                 cache: false,
                 data: formData,
                 processData: false,
                 contentType: false,
                 headers: { 'X-Requested-With': 'XMLHttpRequest' },
                 dataType: "JSON",
                 success: function (data) {
                     $(".update_profile").removeAttr("disabled");
     
                     if (data.success == true) {
                         Swal.fire({
                             icon: 'success',
                             title: 'Updated...',
                             text: data.msg,
                         });
                         setTimeout(function () {
                             location.reload();
                         }, 2000);
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
 
     $("#change-pwd-frm").validate({
         rules: {
             "c_pass": { required: true },
             "n_pass": "required",
             "confirm_pass": { required: true, equalTo: '[name="n_pass"]' }
         },
         messages: {
             c_pass: {
                 required: "Please enter the password.",
             },
 
             "n_pass": "Please Enter New Password",
             confirm_pass: {
                 required: "Please enter the confirm password.",
                 equalTo: "New Password and Confirm Password should be same ! "
             },
         },
         submitHandler:function(){
             var formData = new FormData($('#change-pwd-frm')[0]);
             $(".update_password").attr("disabled", "disabled");
             $.ajax({
                 url: base_url + "users/change-password",
                 type: "POST",
                 cache: false,
                 data: formData,
                 processData: false,
                 contentType: false,
                 headers: { 'X-Requested-With': 'XMLHttpRequest' },
                 dataType: "JSON",
                 success: function (data) {
                     $(".update_password").removeAttr("disabled");
     
                     if (data.success == true) {
                         Swal.fire({
                             icon: 'success',
                             title: 'Updated...',
                             text: data.msg,
                         });
                         setTimeout(function () {
                             location.reload();
                         }, 2000);
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
 
 