const page_header = $("#page_header").html("Manage Vehicle Owners - (Custodian).");
const add_vehicle_btn = $("#add_vehicle_btn").html("Add New Owner");

const page_title = $("#page_title").html("Manage Vehicle Owners || Vanga Fleet Manager");
const alert = $("#alert").html(`
                <div class="alert alert-info mb-4" role="alert">
                    <p><strong> This page contains all the vehicles owners in the system. 
                            It also contains details on how to add a new vehicle owner / custodian.
                        </strong>
                    </p>
                    <hr>
                    <p> Click on a vehicle owner to view more information about it or click the add new owner 
                         on the top right to add a new vehicle owner     to the system.
                    </p>
                </div>`);

const add_vehicle_modal = $("#add_vehicle_modal");

const add_owner_form = $("#add_owner_form").validate({
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
            url: "./queries/to_add_new_owner.php",
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
                        $("#add_owner_form").each(function () {
                            this.reset();
                        });
                        owners_table.ajax.reload(null, false);
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
