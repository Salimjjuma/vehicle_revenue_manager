const page_header = $("#page_header").html("Manage Vehicle Category.");
const add_vehicle_btn = $("#add_vehicle_btn").html("Add New Category");

const page_title = $("#page_title").html("Manage Vehicle Category || Vanga Fleet Manager");
const alert = $("#alert").html(`
                <div class="alert alert-info mb-4" role="alert">
                    <p><strong> This page contains all the vehicles categories in the system. 
                            It also contains details on how to add a new vehicle category.
                        </strong>
                    </p>
                    <hr>
                    <p> Click on a vehicle category to view more information about it or click the add new vehicle 
                        category on the top right to add a new vehicle category to the system.
                    </p>
                </div>`);

const add_vehicle_modal = $("#add_vehicle_modal");


const add_category_form = $("#add_category_form").validate({
    rules: {
        category_name: "required"
    },
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
    errorClass: "text-danger",

    submitHandler: ((form) => {
        $.ajax({
            url: "./queries/add_new_category",
            type: "POST",
            data: $(form).serialize(),
        }).done((response) => {
            const r = JSON.parse(response);
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
                        $("#add_category_form").each(function () {
                            this.reset();
                        });
                        category_table.ajax.reload(null, false);
                    }
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
        })
    }),
})