const page_header = $("#page_header").html("Manage All Vehicles.");
const add_vehicle_btn = $("#add_vehicle_btn").html("Add New Vehicle");
const alert = $("#alert").html(`
                <div class="alert alert-info mb-4" role="alert">
                    <p> <strong> This page contains all the vehicles in the system. It also contains details on
                            how to add a new vehicle.</strong></p>
                    <hr>
                    <p> Click on a vehicle to view more information about it or click the add new vehicle on 
                        the top right to add a new vehicle to the system.
                    </p>
                </div>`);

const page_title = $("#page_title").html("Manage Vehicles || Vanga Fleet Manage");

// select2-bootstrap5-theme- universal object property 
const selectProperties = {
    theme: 'bootstrap-5',
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
};

const add_vehicle_modal = $("#add_vehicle_modal");

// form validator for adding vehicle. 
const add_vehicle_form = $("#add_vehicle_form").validate({
    rules: {
        vehicle_name: {
            required: true,
        },
        vehicle_company: {
            required: true
        },
        category: {
            required: true
        },
        reg_no: {
            required: true
        }
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
            url: "./queries/insert_new_vehicle",
            type: "POST",
            data: $(form).serialize(),
        }).done((response) => {
            // parse the response in to an object from the json form. 
            const r = JSON.parse(response);
            // hide the modal as we are waiting for the responses.
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
                        $("#add_vehicle_form").each(function () {
                            this.reset();
                        });
                        vehicle_table.ajax.reload(null, false);
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