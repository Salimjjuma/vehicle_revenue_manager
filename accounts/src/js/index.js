const page_title = $("#page_title").html("Manage Account Types");
const page_header = $("#page_header").html("Manage Account Types");

const alert = $("#alert").html(`
    <div class="alert alert-info mb-4" role="alert">
                    <p> <strong> This page contains all the accounts in the system. Accounts can be of different </strong></p>
                </div>

`);

const add_vehicle_modal = $("#add_vehicle_modal");

const add_vehicle_btn = $("#add_vehicle_btn").html("Add Account Type")
.prop( "disabled", true );

const add_account_form = $("#add_account_form").validate({

    rules: {
        acc_name: "required",
        acc_description: "required"
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
            url: "./queries/to_add_an_account_to_the_db.php",
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
                        $("#add_account_form").each(function () {
                            this.reset();
                        });
                        accounts_table.ajax.reload(null, false);
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