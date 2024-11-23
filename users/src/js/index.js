const page_header = $("#page_header").html("Manage User Accounts");
add_vehicle_btn = $("#add_vehicle_btn").html("Add New User");
page_title = $("#page_title").html("Manage User Accounts || Vanga Fleet Manager");

const activeFlag = 1;

const url_for_datatables = "./queries/get_all_user_accounts";
const url_for_validation = "./queries/add_new_user";

const add_vehicle_modal = $("#add_vehicle_modal");

const alert = $("#alert").html(`
    <div class="alert alert-primary d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
        <use xlink:href="#info-fill" /></svg>
    <div>
        This page contains details of all the user accounts that have been registered in the system.
        Click on Add New User to register a new user in the system.
    </div>
    </div>`);

const users_table = $("#users_table").DataTable({
    ajax : {
        url : url_for_datatables, 
        type : "GET", 
        dataSrc : ""
    }, 
    columnDefs : [
        {
            targets : 0,
            data : "created_at",
        },
        {
            targets : 1,
            data : {
                first_name : "first_name",
                second_name : "second_name",
                last_name : "last_name",
            },
            render : function(data) {
                return `${data.first_name} ${data.second_name} ${data.last_name}`
            }
        },
        {
            targets : 2,
            data: "email_address"
        },
        {
            targets : 3,
            data: "username"
        },
        {
            targets : 4,
            data:  "isActive",
            render : ((data)=>{
                if (data == activeFlag) {
                    return `<span class="badge rounded-pill text-bg-success">Active</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-danger">InActive</span>`;
                }
            })
        }
    ]

});

const add_new_user = $("#add_user_form").validate({
    rules: {
        first_name : "required",
        second_name : "required",
        last_name : "required",
        email_address : {
            required : true,
            email : true
        },
        username : "required",
        password : "required",
        re_password : {
            required : true,
            equalTo : password,
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

    submitHandler : function(form){
        $.ajax({
            url: url_for_validation,
            type : "POST",
            data : $(form).serialize(),
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
                        $("#add_user_form").each(function () {
                            this.reset();
                        });
                        users_table.ajax.reload(null, false);
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
    }
});