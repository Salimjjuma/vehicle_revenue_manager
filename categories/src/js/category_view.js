let category_object;

// select2-bootstrap5-theme- universal object property 
const selectProperties = {
    theme: 'bootstrap-5',
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
};

// Function to extract the ID parameter from the URL
const getURLParameter = () => {
    const queryString = window.location.search; // points to the url and store the value in a constiable
    const urlParams = new URLSearchParams(queryString); // the url is passed as an argurment to the search
    return urlParams;
}

const setIdToSessionStorage = () => {
    sessionStorage.setItem('category_id', getURLParameter().get("cid"));
}

setIdToSessionStorage();

const category_id = sessionStorage.getItem('category_id');

function getCategoryDetails() {
    $.ajax({
        url: "../queries/view/get_details_of_a_category",
        data: {
            cid: category_id
        },
        type: "GET",
    }).done((response) => {
        category_object = JSON.parse(response);

        populateDOMwithDetails();
    });
};

getCategoryDetails();

const populateDOMwithDetails = () => {
    $("#page_header").html(category_object.category_name);
    $("#page_title").html(`${category_object.category_name} || Manage Vehicle Category`);
    $("#date_of_creation").html(category_object.created_at);

    if (category_object.isActive == 1) {
        $("#status_of_vehicle").html(`<span class="badge rounded-pill text-bg-success">Active</span>`);
        $("#alert").html(`
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div>
                            Use this page to view all the vehicles in these vehicle category and 
                            all the details about the vehicle.
                        </div>
                    </div>
                    `);
    } else {
        $("#status_of_vehicle").html(`<span class="badge rounded-pill text-bg-danger">InActive</span>`);

        $("#alert").html(`
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            This vehicle is InActive. You need to consult the administrator to activate the vehicle.
                        </div>
                    </div>
                    `);
    }

    setEditCategory(category_id);
}

const category_view_datatables = $("#category_view_datatables").DataTable({
    ajax: {
        url: "../queries/view/get_all_the_vehicles_in_a_datatables",
        type: "GET",
        dataSrc: "",
        data: {
            cid: category_id
        }
    },
    columnDefs: [{
            targets: 0,
            data: "created_at"
        },
        {
            targets: 1,
            data: "vehicle_name"
        },
        {
            targets: 2,
            data: "registration_number",
        },
        {
            targets: 3,
            data: "company_name"
        }, {
            targets: 4,
            data: "isActive",
            render: (data) => {
                if (data == 1) {
                    return `<span class="badge rounded-pill text-bg-success">Active</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-danger">InActive</span>`;
                }
            },
        }
    ],
    language: {
        emptyTable: `This category doesn't contain any vehicle `,
    }
})

// edit category ---------------------------------------------------------------------
function setEditCategory(cat_id) {
    $("#edit_category_id").val(cat_id);
    $("#edit_category_name").val(category_object.category_name);
}

const edit_category_form = $("#edit_category_form").validate({
    rules: {
        edit_category_name: "required"
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
            url: "../queries/view/update_category",
            type: "POST",
            data: $(form).serialize()
        }).done((response) => {
            const r = JSON.parse(response);
            $("#edit_category_modal").modal("hide");
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
                        $("#edit_category_form").each(function () {
                            this.reset();
                        });
                        getCategoryDetails();
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
    }

})
// end of edit category -------------------------------------------------------------

// DELETE CATEGORY ---------------------------------------------------------------------
function to_delete_a_category() {
    $("#delete_category_modal").modal("show");
    $("#category_id_for_delete").val(category_id);
}

$("#deleteVehicleCategoryForm").validate({
    rules: {
        category_id_for_delete: "required",
        email: "required",
        password: "required"
    },
    erroClass: "text-danger",
    submitHandler: ((form) => {
        const formData = $(form).serializeArray();
        $.ajax({
            url: "../queries/view/deleteCategory",
            type: "POST",
            data: formData,
        }).done((response) => {
            $("#delete_category_modal").modal("hide");
            let r = JSON.parse(response);

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
                        document.location.href = "../index";
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

        })
    })
})
// end of delete category -----------------------------------------------------------