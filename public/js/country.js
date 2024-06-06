$(function () {

    $("#country-add").validate({
        rules: {
            "country_name": "required",
            "country_code": "required",
            "country_code_iso3": "required",
            "country_daily_code": "required",
        },
        messages: {
            "country_name": "Please enter Name",
            "country_code": "Please enter Country Code",
            "country_code_iso3": "Please enter Country Code ISO3",
            "country_daily_code": "Please enter Dail Code"
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        },
        submitHandler: function () {

            var formData = new FormData($('#country-add')[0]);
            $(".country-add").attr("disabled", "disabled");
            $.ajax({
                url: base_url + "admin/country-add-process",
                type: "POST",
                cache: false,
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                dataType: "JSON",
                success: function (data) {
                    $(".country-add").removeAttr("disabled");

                    if (data.settings.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added...',
                            text: data.settings.message,
                        });
                        setTimeout(function () {
                            window.location.href = base_url + "admin/country-listing";
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: data.settings.message,
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // alert('Error at add data');
                }
            });
        }
    });

    $("#country-edit").validate({
        rules: {
            "country_name": "required",
            "country_code": "required",
            "country_code_iso3": "required",
            "country_daily_code": "required",
        },
        messages: {
            "country_name": "Please enter Name",
            "country_code": "Please enter Country Code",
            "country_code_iso3": "Please enter Country Code ISO3",
            "country_daily_code": "Please enter Dail Code"
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        },
        submitHandler: function () {

            var formData = new FormData($('#country-edit')[0]);
            $(".country-edit").attr("disabled", "disabled");
            $.ajax({
                url: base_url + "admin/country-edit-process",
                type: "POST",
                cache: false,
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                dataType: "JSON",
                success: function (data) {
                    $(".country-edit").removeAttr("disabled");
                    if (data.settings.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Edited...',
                            text: data.settings.message,
                        });
                        setTimeout(function () {
                            window.location.href = base_url + "admin/country-listing";
                        }, 2000);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: data.settings.message,
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // alert('Error at add data');
                }
            });
        }
    });
    $('#countryDataTable').DataTable({
        processing: true,
        serverSide: true,
        scrollCollapse: false,
        filter: true, 
        ajax: {
          url: base_url + "admin/ajax-load-data", 
          type: "post",

        },
        columns: [
          { data: "country_id" },
          { data: "country_name" },
          { data: "country_code" },
          { data: "country_code_iso3" },
          { data: "number_state" },
          { data: "status_c" },
          { data: "action" },

        ],
        columnDefs: [
          { orderable: false, targets: [4,5,6] }
        ],
        responsive: true,

      });
});


function changeStatus($id = 0) {

    Swal.fire({
        title: 'Are you sure?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, change it!',
    }).then((result) => {
        if (result.isConfirmed) {
            var id = $id;
            $.ajax({
                url: base_url + "admin/country-status-change",
                data: {id: id},
                cache: false,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.settings.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status changed...',
                            text: data.settings.message,
                        });
                        setTimeout(function () {
                            window.location.href = base_url + "admin/country-listing";
                        }, 2000);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: data.settings.message,
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // alert('Error at add data');
                }
            });
        }
    });
}
function processDelete($id = 0) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            var id = $id;
            $.ajax({
                url: base_url + "admin/country-delete",
                data: {id: id},
                cache: false,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.settings.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted...',
                            text: data.settings.message,
                        });
                        setTimeout(function () {
                            window.location.href = base_url + "admin/country-listing";
                        }, 2000);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: data.settings.message,
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // alert('Error at add data');
                }
            })

        }
    });
}