const page_header = $("#page_header").html("Manage Vehicle Brands.");
const add_vehicle_btn = $("#add_vehicle_btn").html("Add New Vehicle Brand");

const page_title = $("#page_title").html("Manage Vehicle Brand || Vanga Fleet Manager");
const alert = $("#alert").html(`
                <div class="alert alert-info mb-4" role="alert">
                    <p><strong> This page contains all the vehicles brands in the system. 
                            It also contains details on how to add a new vehicle brands.
                        </strong>
                    </p>
                    <hr>
                    <p> Click on a vehicle brand to view more information about it or click the add new vehicle 
                        brand on the top right to add a new vehicle brand to the system.
                    </p>
                </div>`);

const add_vehicle_modal = $("#add_vehicle_modal");

const add_vehicle_brand_form = $("#add_vehicle_brand_form").validate({

    rules: {
        vehicle_brand_name: "required"
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
            url: "./queries/to_add_a_vehicle_brand.php",
            type: "POST",
            data: $(form).serialize()
        }).done((response) => {
            add_vehicle_modal.modal("hide");
            const r = JSON.parse(response);
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
                        $("#add_vehicle_brand_form").each(function () {
                            this.reset();
                        });
                        company_table.ajax.reload(null, false);
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
                })
            }
        })
    })

})