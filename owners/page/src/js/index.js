// ---------------------------- START OF VARIABLE DECLARATION AND INITIALIZATION. ------------------------

const queryString = window.location.search; // points to the url and store the value in a constiable
const urlParams = new URLSearchParams(queryString); // the url is passed as an argurment to the search
const owners_id = urlParams.get("owners_id");

const add_vehicle_btn = $("#add_vehicle_btn").html("Add Ownership");
const add_vehicle_modal = $("#add_vehicle_modal");

// Owners object.  
let owner_object;

// select2-bootstrap5-theme- universal object property 
const selectProperties = {
    theme: 'bootstrap-5',
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
};

// input. 
const owners_id_input = $("#owners_id_input").val(owners_id);

$("#alert").html(`
                <div class="alert alert-info mb-4" role="alert">
                    <p><strong> This page contains all the vehicles that the owner possesses. 
                        </strong>
                    </p>
                    <hr>
                    <p>Click Add Ownership to add a vehicle to the owner.
                    </p>
                </div>`);

// -------------------------------- END OF VARIABLE ------------------------------------------------------- 

const owners_id_revoke = $("#owners_id_revoke").val(owners_id);

function init() {
    $.ajax({
        url: "./queries/to_get_owners_details.php",
        data: {
            owners_id: owners_id,
        },
        type: "GET"
    }).done((data) => {
        owner_object = JSON.parse(data);
        setOwnersAttributes();
    });
}

init();

// VIEW TABLE DATATABLE INITIALIZATION --------------------------------------------------------------------
const view_table = $("#view_table").DataTable({
    ajax: {
        url: "./queries/to_get_all_vehicles_assign_to_owners.php",
        data: {
            owners_id: owners_id
        },
        dataSrc: "",
        type: "GET"
    },
    columnDefs: [{
            targets: 0,
            data: "vehicle_name",
        },
        {
            targets: 1,
            data: {
                "registration_number": "registration_number",
                "vehicle_id": "vehicle_id",
            },
            render: ((data) => {
                return `<a href="/vehicles/pages/index?v=${data.vehicle_id}">${data.registration_number}</a>`
            })
        },
        {
            targets: 2,
            data: "date_of_ownership",
        },
        {
            targets: 3,
            data: "isActive",
            render: ((data) => {
                if (data == 1) {
                    return `<span class="badge rounded-pill text-bg-success">Active Ownership</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-danger">InActive Ownership</span>`;
                }
            })
        },
        {
            targets: 4,
            data: "category_name",
        },
        {
            targets: 5,
            data: "company_name",
        },

        {
            targets: 6,
            data: {
                "vehicle_id": "vehicle_id",
                "end_date_of_ownership": "end_date_of_ownership"
            },
            render: ((data) => {
                if (data.end_date_of_ownership == "null") {
                    return `<button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#revoke_ownership"> Disown Ownership
                            </button>`;
                } else {
                    return `<button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#revoke_ownership"> Issue Ownership
                            </button>`;
                }
            })
        },
    ],
    language: {
        emptyTable: `This owner has no vehicle ownership. To add Ownership, click on Add Ownership `,
        // You can also customize other language settings if needed
    }
});
// END OF VIEW DATATABLES -----------------------------------------------------------------------------------


// POST VALIDATION FOR INSERT OF OWNER OWNERSHIPS -------------------------------------------------------------
const add_ownership_form = $("#add_ownership_form").validate({
    rules: {
        vehicle: "required",
        owners_id_input: "required"
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
            url: "./queries/to_add_ownership_of_vehicle.php",
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
                        $("#add_ownership_form").each(function () {
                            this.reset();
                        });
                        view_table.ajax.reload(null, false);
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
// END OF POST VALIDATION -------------------------------------------------------------------------------------

function setOwnersAttributes() {
    let owners_status;

    $("#page_header").html(
        `${owner_object.first_name} ${owner_object.second_name}
        ${owner_object.last_name}`);

    $("#page_title").html(`${owner_object.first_name} ${owner_object.second_name} ${owner_object.last_name} || Manage Vehicle Ownership `);

    if (owner_object.isActive == 1) {
        owners_status = `<span class="badge rounded-pill text-bg-success">Active</span>`;
    } else {
        owners_status = `<span class="badge rounded-pill text-bg-danger">InActive</span>`;
    }

    $("#sub_content").html(`
            <div class="d-flex justify-content-between align-items-center pt-2 pb-2">
                    <h6 class="h6">Phone No. - ${owner_object.phone_number}</h6>

                    <h6 class="h6">Owners Status: ${owners_status} </h6>
                </div>`);

    $("#header_content").html(`
    <div class="d-flex justify-content-between align-items-center">
                    <h6 class="h6">Manage Vehicle Ownership</h6>
                </div>
`);
}

const revoke_vehicle = $("#revoke_vehicle").select2({

    theme: selectProperties.theme,
    width: selectProperties.width,
    placeholder: selectProperties.placeholder,
    ajax: {
        url: "./queries/to_get_all_the_vehicles_in_the_db",
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

















// const revoke_ownership = $("#revoke_ownership").validate({
//     rules: {
//         revoke_vehicle: "required",
//         owners_id_revoke: "required"
//     },
//     errorClass: "text-danger",

//     invalidHandler: function (event, validator) {
//         var errors = validator.numberOfInvalids();
//         if (errors) {
//             var message =
//                 errors == 1 ? "You missed 1 field" : `You missed ${errors} fields`;
//             $("div.errors span").html(message);
//             $("div.errors").show();
//         } else {
//             $("div.errors").hide();
//         }
//     },
//     submitHandler: function (form) {
//         $.ajax({
//             url: "./queries/revoke_ownership.php",
//             type: "POST",
//             data: $(form).serialize(),
//         }).done((response) => {
//             // parse the response in to an object from the json form. 
//             // const r = JSON.parse(response);
//             // // hide the modal as we are waiting for the responses.
//             // add_vehicle_modal.modal("hide");
//             // if (r.success == true) {

//             //     iziToast.success({
//             //         message: r.message,
//             //         position: "bottomLeft",
//             //         type: "Success",
//             //         transitionIn: "bounceInLeft",
//             //         overlay: true,
//             //         zindex: 999,
//             //         messageColor: "black",
//             //         onClosing: function () {
//             //             $("#add_ownership_form").each(function () {
//             //                 this.reset();
//             //             });
//             //             view_table.ajax.reload(null, false);
//             //         },
//             //     })
//             // } else {
//             //     iziToast.error({
//             //         message: r.message,
//             //         position: "bottomLeft",
//             //         type: "Error",
//             //         overlay: true,
//             //         zindex: 999,
//             //         transitionIn: "bounceInLeft",
//             //         progressBar: false,
//             //         messageColor: "black",
//             //     });
//             // }
//         });
//     }

// });


// span for adding modal to the page. 
// const additional_content = $("#additional_content").html(`<div class="modal fade" id="revoke_ownership" data-bs-backdrop="static" tabindex="-1"
//     aria-labelledby="add_account_modal_label" aria-hidden="true">
//     <div class="modal-dialog">
//         <div class="modal-content">
//             <div class="modal-header">
//                 <h1 class="modal-title fs-5" id="add_account_modal_label">Revoke Ownership</h1>
//                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
//                     <strong>X</strong></button>
//             </div>
//             <div class="modal-body">

//                 <div class="alert alert-danger" role="alert">
//                     <p><strong>You are about to revoke ownership of a owner.</strong></p>
//                     <hr />
//                     <p class="mb-0">Click Revoke Ownership to revoke the vehicle from the owner.</p>
//                 </div>

//                 <form id="revoke_ownership" autocomplete="off">

//                     <input type="hidden" name="owners_id_revoke" id="owners_id_revoke">

//                     <div class="form-group mb-2">
//                         <label for="vehicle" class="text-danger">Choose Vehicle to Revoke Ownership.</label>
//                         <select name="revoke_vehicle" id="revoke_vehicle" class="form-select"
//                             data-placeholder="Fuso, Toyota, Nissan">
//                         </select>
//                     </div>

//                     <hr my-3>
//                    <div class="d-grid gap-2">
//                         <button class="btn btn-danger" type="submit">
//                             Revoke Ownership
//                         </button>
//                     </div>
//                 </form>

//             </div>

//         </div>
//     </div>
// </div>

// `);