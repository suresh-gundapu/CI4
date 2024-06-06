$(function() {
        $("#frm-add-user").validate({
      rules: {
        "firstname": "required",
        "lastname": "required",
        "email": "required",
        "username": "required",
        "password": "required",
        "image": "required"
      },
      messages: {
        "firstname": "Please enater firstname",
        "lastname": "Please enter lastname",
        "email": "Please enter email",
        "username": "Please enter username",
        "password": "Please enter password",
        "image": "Please select profileImage"
      }
    });
    $('#frm-add-user').on('submit', function(e) {

        e.preventDefault();

        var formData = new FormData(this);

        //We can add more values to form data
        //formData.append("key", "value");

        $.ajax({
            url: base_url+"admin/save-user",
            type: "POST",
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType: "JSON",
            success: function(data) {
                if (data.success == true) {
                    Swal.fire('Registered!', '', 'success')
                    setTimeout(function(){ 
                        window.location.href=base_url+"admin/login";
                    }, 1000);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
               // alert('Error at add data');
            }
        });
    });
});

