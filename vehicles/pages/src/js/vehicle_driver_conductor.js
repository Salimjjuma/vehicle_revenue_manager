const add_vehicle_driver_modal = $("#add_vehicle_driver_modal");

const vehicle_id_for_driver_leasing_form = $("#vehicle_id_for_driver_leasing_form").val(vehicle_id);

const driver_vehicle_datatables_url = "./queries/to_get_the_vehicle_driver_conductors_for_a_specific_vehicle";

const driver_vehicle_datatables = $("#driver_vehicle_datatables").DataTable({
    ajax: {
        url: driver_vehicle_datatables_url,
        data: {
            vehicle_id: vehicle_id
        },
        dataSrc: "",
        type: "GET",
    },
    language: {
        emptyTable: `This vehicle does not contain any driver or conductor.`,
    },
    columnDefs: [{
            targets: 0,
            data: "created_at"
        },
        {
            targets: 1,
            data: "driver",
        },
        {
            targets: 2,
            data: "conductor"
        },
        {
            targets: 3,
            data: "leaseStartDate",
        },
        {
            targets: 4,
            data: "leaseExpiryDate",
            width: '5%',
            orderable: false,
            render: function (data) {
                if (data != null) {
                    return `<span class="badge rounded-pill text-bg-danger">Expired</span>`
                } else {
                    return `<span class="badge rounded-pill text-bg-success">Not Expired</span>`
                }
            }
        }, {
            targets: 5,
            data: "date_diff",
            render: function (data) {
                if (data == null) {
                    return "----"
                } else {
                    return data;
                }
            }
        },
        {
            targets: 6,
            data: {
                vehicle_driver_conductors: "vehicle_driver_conductors",
                leaseExpiryDate: "leaseExpiryDate"
            },
            render: function (data) {
                if (data.leaseExpiryDate == null) {
                    return `<a style = "color:red" onClick="triggerStopDriverVehicleLease('${data.vehicle_driver_conductors}')">Stop Lease</a>`;
                } else {
                    return `${data.leaseExpiryDate}`;
                }
            }
        }
    ],
});
//
const errorDuringStopLeasePlaceholder = $("#errorDuringStopLeasePlaceholder");

$('[data-toggle="datepicker"]').datepicker({
    format: 'yyyy-mm-dd'
});

const vehicle_driver_select = $("#vehicle_driver_select").select2({
    theme: selectProperties.theme,
    width: selectProperties.width,
    placeholder: selectProperties.placeholder,
    ajax: {
        url: "./queries/to_get_all_the_driver_for_select2",
        type: "POST",
        dataType: "json",
        delay: 250,
        data: function (params) {
            return {
                searchTerm: params.term,
            };
        },
        processResults: function (response) {
            return {
                results: response,
            };
        },
        cache: true,
    }
});

const vehicle_conductor_select = $("#vehicle_conductor_select").select2({
    theme: selectProperties.theme,
    width: selectProperties.width,
    placeholder: selectProperties.placeholder,
    ajax: {
        url: "./queries/to_get_all_the_conductors_for_select2",
        type: "POST",
        dataType: "json",
        delay: 250,
        data: function (params) {
            return {
                searchTerm: params.term,
            };
        },
        processResults: function (response) {
            return {
                results: response,
            };
        },
        cache: true,
    }
})

const assignDriverToVehicleForm = $("#assignDriverToVehicleForm").validate({
    rules: {
        leaseStartDate: "required",
        vehicle_conductor_select: "required",
        vehicle_driver_select: "required",
    },
    erroClass: "text-danger",

    invalidHandler: function (event, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {
            var message =
                errors == 1 ? "You missed 1 field" : `You missed ${errors} fields`;
            $("div.errors span").html(message);
            $("div.errors").show();
        } else {
            $("div.errors").hide();
        }
    },

    submitHandler: function (form) {
        // Get the serialized form data
        const formData = $(form).serializeArray();

        $.ajax({
            url: "./queries/to_insert_vehicle_driver",
            type: "POST",
            data: formData,
        }).done((response) => {
            // parse the response in to an object from the json form. 
            const r = JSON.parse(response);
            // hide the modal as we are waiting for the responses.
            add_vehicle_driver_modal.modal("hide");
            if (r.success == true) {

                iziToast.success({
                    message: r.message,
                    position: "bottomLeft",
                    type: "Success",
                    transitionIn: "bounceInLeft",
                    overlay: true,
                    zindex: 999,
                    messageColor: "black",
                    onClosing: function () {
                        driver_vehicle_datatables.ajax.reload(null, false);
                        $("#assignDriverToVehicleForm").each(function () {
                            this.reset();
                        });
                        //  set the value of the hidden attribute.
                        vehicle_id_input_hidden.val(vehicle_id);
                    },
                })
            } else {
                iziToast.error({
                    message: r.message,
                    position: "bottomLeft",
                    type: "Error",
                    overlay: true,
                    zindex: 999,
                    transitionIn: "bounceInLeft",
                    progressBar: false,
                    messageColor: "black",
                });
            }
        });
    }
})

function triggerStopDriverVehicleLease(vehicle_driver_conductors) {
    $("#vehicle_driver_conductors_id_from_table").val(vehicle_driver_conductors);
    $("#stopLeaseModal").modal("show");
}

const stopLeaseForm = $("#stopLeaseForm").validate({
    rules: {},
    errorClass: "text-danger",
    invalidHandler: function (event, validator) {},
    submitHandler: function (form) {

        // Get the serialized form data
        const formData = $(form).serializeArray();

        $.ajax({
            url: "./queries/stopVehicleLease.php",
            type: "POST",
            data: formData
        }).done((response) => {
            const r = JSON.parse(response);

            if (r.success == true) {
                // hide the modal as we are waiting for the responses.
                $("#stopLeaseModal").modal("hide");
                iziToast.success({
                    message: r.message,
                    position: "bottomLeft",
                    type: "Success",
                    transitionIn: "bounceInLeft",
                    overlay: true,
                    zindex: 999,
                    messageColor: "black",
                    onClosing: function () {
                        driver_vehicle_datatables.ajax.reload(null, false);
                        $("#stopLeaseForm").each(function () {
                            this.reset();
                        });
                    },
                })
            } else {
                errorDuringStopLeasePlaceholder.html(`
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                        <use xlink:href="#exclamation-triangle-fill" />
                    </svg>
                    <div>
                        ${r.message}
                    </div>
                </div>
                `)
            }

        })
    }
});