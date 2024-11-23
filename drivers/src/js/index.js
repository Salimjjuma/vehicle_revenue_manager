const page_title = $("#page_title").html("Manage Driver & Conductors");
const page_header = $("#page__header").html("Manage Drivers & Conductors");
const add_vehicle_btn = $("#add_vehicle_btn").html("Add Driver");
const add_conductor_btn = $("#add_conductor_btn").html("Add Conductor");
// modal to view driver input form 
const add_vehicle_modal = $("#add_vehicle_modal");
const add_conductor_modal = $("#add_conductor_modal");
const alert = $("#alert").html(`
                <div class="alert alert-info mb-4" role="alert">
                    <p><strong> This page contains all the vehicles drivers and conductors in the system. 
                            It also contains details on how to add a new vehicle driver and a new conductor.
                        </strong>
                    </p>
                    <hr>
                    <p> Click on a vehicle driver to view more information about it or click the add new driver 
                         on the top right to add a new vehicle driver to the system.
                    </p>
                </div>`);

// urls to get drivers and conductors. 
const url_to_get_drivers_for_datatables = "./queries/fetchAllTheDrivers.php";
const url_to_get_conductors_for_datatables = "./queries/fetchAllTheConductorsForDataTables.php"

// populate all the drivers in the database. 
// datatables.

const driver_table = $("#driver_table").DataTable({
    ajax: {
        url: url_to_get_drivers_for_datatables,
        type: "GET",
        dataSrc: "",
    },
    columnDefs: [{
            targets: 0,
            data: "created_at"
        },
        {
            targets: 1,
            data: {
                "vehicle_driver_id": "vehicle_driver_id",
                "drivers_license_no": "drivers_license_no",
                "first_name": "first_name",
                "second_name": "second_name",
                "last_name": "last_name"
            },
            render: ((data) => {
                return `<a href="./pages/?driver=${data.vehicle_driver_id}"> ${data.first_name} ${data.second_name} ${data.last_name}</a>`
            })
        },
        {
            targets: 2,
            data: "drivers_license_no"
        },
        {
            targets: 3,
            data: "phone_number"
        },
        {
            targets: 4,
            data: "isActive",
            render: ((data) => {
                if (data == 1) {
                    return `<span class="badge rounded-pill text-bg-success">Active</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-success">InActive</span>`;
                }
            })
        }
    ]
});

// CONDUCTORS DATATABLES. ----------------------------------------------------------
const conductor_vehicle_datatables = $("#conductor_vehicle_datatables").DataTable({
    ajax: {
        url: url_to_get_conductors_for_datatables,
        type: "GET",
        dataSrc: ""
    },
    columnDefs: [{
            targets: 0,
            data: "created_at",
        }, {
            targets: 1,
            data: {
                "conductor_id": "conductor_id",
                "first_name": "first_name",
                "second_name": "second_name",
                "last_name": "last_name"
            },
            render: ((data) => {
                return `<a href="./pages/?conductor=${data.conductor_id}">${data.first_name} ${data.second_name} ${data.last_name}</a>`
            })
        }, {
            targets: 2,
            data: "phone_number"
        },
        {
            targets: 3,
            data: "isActive",
            render: ((data) => {
                if (data == 1) {
                    return `<span class="badge rounded-pill text-bg-success">Active</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-success">InActive</span>`;
                }
            })
        }
    ]
})

// function to add a new vehicle driver. 
const add_vehicle_driver_form = $("#add_vehicle_driver_form").validate({
    rules: {
        first_name: "required",
        second_name: "required",
        last_name: "required",
        phone_number: "required",
        drivers_license_no: "required"
    },

    errorClass: "text-danger",

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
        $.ajax({
            url: "./queries/to_add_new_vehicle_driver.php",
            type: "POST",
            data: $(form).serialize(),
        }).done((response) => {
            // // parse the response in to an object from the json form. 
            const r = JSON.parse(response);
            // // hide the modal as we are waiting for the responses.
            add_vehicle_modal.modal("hide");
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
                        $("#add_vehicle_driver_form").each(function () {
                            this.reset();
                        });
                        driver_table.ajax.reload(null, false);
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
});

// function to add a new conductor.
const add_conductor_form = $("#add_conductor_form").validate({
    rules: {
        first_name: "required",
        second_name: "required",
        last_name: "required",
        phone_number: "required"
    },
    errorClass: "text-danger",
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
        $.ajax({
            url: "./queries/toAddConductor.php",
            type: "POST",
            data: $(form).serialize(),
        }).done((response) => {
            // // parse the response in to an object from the json form. 
            const r = JSON.parse(response);
            // // hide the modal as we are waiting for the responses.
            add_conductor_modal.modal("hide");
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
                        $("#add_conductor_form").each(function () {
                            this.reset();
                        });
                        conductor_vehicle_datatables.ajax.reload(null, false);
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