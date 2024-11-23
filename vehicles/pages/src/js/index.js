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
        sessionStorage.setItem('vehicle_Id', getURLParameter().get("v"));
    }

    setIdToSessionStorage();

    const vehicle_id = sessionStorage.getItem('vehicle_Id');

    var vehicle_details;
    // async function to get the details of the vehicle. 
    async function fetchVehicleDetails(arg) {
        let vehicle = arg;
        let url = `./queries/to_get_details_of_vehicle?vehicle_id=${vehicle}`;
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            const data = await response.json(); // If your API returns JSON data
            // Process the data here or return it to the caller
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    //  disable the edit button until the async returns requested data
    $("#edit_vehicle_btn").prop("disabled", true);

    // revenue and expense table.
    const vehicle_vehicle_table = $("#vehicle_vehicle_table").DataTable({
        ajax: {
            url: "./queries/to_get_revenue_and_expense.php",
            data: {
                vehicle_id: vehicle_id
            },
            dataSrc: "",
            type: "GET",

        },
        columnDefs: [{
                targets: 0,
                data: "entry_date",
                render: ((data) => {
                    let upperCase = data.toUpperCase();
                    return `<a href="./overview?date=${data}">${upperCase}</a>`
                })
            },
            {
                targets: 1,
                data: "total_revenue",
                render: ((data) => {
                    return `<span class="text-success">${data}</span>`
                })
            },
            {
                targets: 2,
                data: "total_expense",
                render: ((data) => {
                    return `<span class="text-danger">${data}</span>`
                })
            },
            {
                targets: 3,
                data: "difference"
            }
        ],

        language: {
            emptyTable: `This vehicle does not contain any revenue or expense.`,
            // You can also customize other language settings if needed
        }

    });

    // variable initialization. 
    const alert = $("#alert"),
        status_of_vehicle = $("#status_of_vehicle"),
        page_header = $("#page_header"),
        page_title = $("#page_title"),
        category_of_vehicle = $("#category_of_vehicle"),
        brand_of_vehicle = $("#brand_of_vehicle"),
        registration_number = $("#registration_number"),
        add_a_transcation = $("#add_a_transcation").html("Add Transcation"),
        date_of_creation = $("#date_of_creation"),
        add_vehicle_modal = $("#add_vehicle_modal"),
        edit_vehicle_modal = $("#edit_vehicle_modal");
    let vehicle_id_input_hidden = $('[name="vehicle_id"]').val(vehicle_id);
    let revenue_against_expense_chart;
    const deleteVehicleModal = $("#deleteVehicleModal");


    // --------------- CHART SELECT2--------------------------------------

    // the ajax select2 for collecting all the charts of accounts.  
    const charts_of_account = $("#charts_of_account").select2({
        theme: selectProperties.theme,
        width: selectProperties.width,
        placeholder: selectProperties.placeholder,
        ajax: {
            url: "./queries/to_get_all_the_chart_of_accounts",
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

    // After the user selects an option we get the value of the option
    charts_of_account.on("select2:select", function (e) {
        // retrieve the values of the option. id, text, isDebited.
        const select_object = e.params.data;

        // make the amount DOM enabled and the button enabled. 
        $('#amount').prop("disabled", false); // Element(s) are now enabled
        $("#save_button").prop("disabled", false);

        // add the value of the hidden transcation type.
        $("#transaction_type").val(select_object.isDebited);

    })
    // ------------------- END OF CHART SELECT2 -------------------------


    // Custom validation method for radio buttons
    $.validator.addMethod('radioSelected', function (value, element) {
        // Check if at least one radio button in the group is checked
        return $("input[name='" + element.name + "']:checked").length > 0;
    }, "Please select one option.");

    // form validator for the POST request (adding a new transcation). 
    const add_a_transcation_form = $("#add_a_transcation_form").validate({
        rules: {
            radios: {
                required: true,
                radioSelected: true // Use the custom validation method for the radio buttons
            },
            amount: "required",
            charts_of_account: "required",
            date: "required",
            transaction_type: "required"
        },
        messages: {
            radios: {
                required: "Please select one option."
            }
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
                url: "./queries/to_add_a_transcation",
                type: "POST",
                data: formData,
            }).done((response) => {
                // parse the response in to an object from the json form. 
                const r = JSON.parse(response);
                // hide the modal as we are waiting for the responses.
                add_vehicle_modal.modal("hide");
                charts_of_account.val(null).trigger('change');
                if (r.success == true) {

                    iziToast.success({
                        message: r.message,
                        position: "bottomLeft",
                        type: "Success",
                        transitionIn: "bounceInLeft",
                        overlay: false,
                        zindex: 999,
                        messageColor: "black",
                        onClosing: function () {
                            vehicle_vehicle_table.ajax.reload(null, false);
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
    });

    function reloadVehicleDetails() {
        fetchVehicleDetails(vehicle_id)
            .then((data) => {
                vehicle_details = data;
                populate_DOM_with_vehicle_details();
            })
            .catch((error) => {
                // Handle errors, if any
                console.error(error);
            });
    }

    reloadVehicleDetails();

    let ctx = document.getElementById('revenue_against_expense_chart').getContext('2d');

    var days = []; // array that holds the charts label.
    var revenue = []; // array that holds the chart datasets data.
    var expense = [];
    const backgroundColors = ['rgba(13, 135, 1, 0.2)', 'rgba(235, 52, 52, 0.2)'];

    async function getChartData(arg) {
        let url = `./queries/to_get_revenue_and_expense_for_chartJs?vehicle_id=${arg}`;
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            const data = await response.json(); // If your API returns JSON data
            // Process the data here or return it to the caller
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    getChartData(vehicle_id).then((data) => {
        for (let i = 0; i < data.length; i++) {
            let ds_items = data[i];
            days.push(ds_items.entry_date)
            revenue.push(ds_items.total_revenue)
            expense.push(ds_items.total_expense)
        }
    }).then(() => {
        setDataToChartJs();
    });

    setInterval(() => {
        revenue_against_expense_chart.update();
        fetchVehicleDetails(vehicle_id)
        vehicle_vehicle_table.ajax.reload(null, false);
    }, 200000);

    const date_filter_to_print_report_form = $("#date_filter_to_print_report_form").validate({

        rules: {
            startDate: "required",
            endDate: "required",
            vehicle_id: "required"
        },

        submitHandler: function () {
            let startDateValue = $("#startDate").val(),
                endDateValue = $("#endDate").val();

            var url = "/reports/vehicles/reports/custom_vehicle_report?" +
                "s=" + encodeURIComponent(startDateValue) +
                "&e=" + encodeURIComponent(endDateValue) +
                "&v=" + encodeURIComponent(vehicle_id);

            // Redirect to the constructed URL.
            window.open(url, '_blank');
        }

    });

    function setDataToChartJs() {
        var data = {
            labels: days,
            datasets: [{
                    label: 'Revenue',
                    data: revenue,
                    borderColor: 'green',
                    backgroundColor: backgroundColors[0],
                    fill: true,
                    lineTension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2, // Border width of data points
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4
                },
                {
                    label: 'Expense',
                    data: expense,
                    borderColor: 'red',
                    backgroundColor: backgroundColors[1],
                    fill: true,
                    lineTension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2, // Border width of data points
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4
                }
            ]
        };

        var options = {
            responsive: true,
            //  layout: {
            //      padding: {
            //          left: 10,
            //          right: 25,
            //          top: 25,
            //          bottom: 0,
            //      },
            //  },
            plugins: {
                title: {
                    display: true,
                    text: 'Revenue & Expense Overview over a period of time.'
                },
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date of Transcation'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Expense / Revenue Value'
                    },
                }
            },
            legend: {
                display: true,
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: "index",
                caretPadding: 10,
            },
        };

        revenue_against_expense_chart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    }

    function populate_DOM_with_vehicle_details() {
        page_header.html(`${vehicle_details.vehicle_name} ~ ${vehicle_details.registration_number}`);
        page_title.html(`${vehicle_details.vehicle_name} || ${vehicle_details.registration_number}`);
        category_of_vehicle.html(`Vehicle Category: ${vehicle_details.category_name}`);
        brand_of_vehicle.html(`Vehicle Brand: ${vehicle_details.company_name}`);
        registration_number.html(`Registration No.: ${vehicle_details.registration_number}`);
        date_of_creation.html(`Date of Creation:  ${vehicle_details.created_at}`);

        if (vehicle_details.isActive == 1) {
            status_of_vehicle.html(`<span class="badge rounded-pill text-bg-success">Active</span>`);
            alert.html(`
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div>
                            This page contains details of the vehicle, in addition to its revenue and expense. Click on the tabs below to 
                            view more on the drivers and conductors assigned over a period of time. 
                        </div>
                    </div>
                    `);
        } else {
            status_of_vehicle.html(`<span class="badge rounded-pill text-bg-danger">InActive</span>`);

            alert.html(`
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            This vehicle is InActive. You need to consult the administrator to activate the vehicle.
                        </div>
                    </div>
                    `);
        }

        $("#edit_vehicle_name").val(vehicle_details.vehicle_name);
        $("#edit_registration_number").val(vehicle_details.registration_number);

        if (vehicle_details.first_name != null) {
            $("#vehicle_owner_").html(`Vehicle Owner: ${vehicle_details.first_name} ${vehicle_details.second_name} ${vehicle_details.last_name}`)
        } else {
            $("#vehicle_owner_").html(`Vehicle Owner Not Assigned.`)
        }
    }

    const select2_for_edit_category = $("#edit_category").select2({
        theme: selectProperties.theme,
        width: selectProperties.width,
        placeholder: selectProperties.placeholder,
        ajax: {
            url: "../queries/get_all_vehicle_category.php",
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

    const select2_for_company_names = $("#edit_company_name").select2({
        theme: selectProperties.theme,
        width: selectProperties.width,
        placeholder: selectProperties.placeholder,
        ajax: {
            url: "../queries/get_all_vehicle_company.php",
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

    const edit_vehicle_form = $("#edit_vehicle_form").validate({
        rules: {
            vehicle_name: "required",
            registration_number: "required",
            category: "required",
            company_name: "required",
            isActive: "required"
        },

        erroClass: "text-danger",

        submitHandler: ((form) => {
            // Get the serialized form data
            const formData = $(form).serializeArray();
            $.ajax({
                url: "./queries/to_edit_vehicle",
                type: "POST",
                data: formData,
            }).done((response) => {
                let r = JSON.parse(response);
                edit_vehicle_modal.modal("hide");
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
                            reloadVehicleDetails();
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
        }),

    })

    $.ajax({
        type: 'GET',
        url: './queries/to_get_details_of_vehicle?vehicle_id=' + vehicle_id
    }).then(function (data) {

        const obj = JSON.parse(data);
        $("#edit_vehicle_btn").prop("disabled", false);
        // create the option and append to Select2
        var option = new Option(obj.category_name, obj.category_id, true, true);
        select2_for_edit_category.append(option).trigger('change');

        // manually trigger the `select2:select` event
        select2_for_edit_category.trigger({
            type: 'select2:select',
            params: {
                data: obj
            }
        });
    });

    const deleteVehicleForm = $("#deleteVehicleForm").validate({
        rules: {
            vehicle_id_for_delete: "required",
            email: "required",
            password: "required"
        },
        erroClass: "text-danger",
        submitHandler: ((form) => {
            const formData = $(form).serializeArray();
            $.ajax({
                url: "./queries/toDeleteAVehicle",
                type: "POST",
                data: formData,
            }).done((response) => {
                deleteVehicleModal.modal("hide");
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

    function deleteVehicle() {
        deleteVehicleModal.modal("show");
        $("#vehicle_id_for_delete").val(vehicle_id);
    }

    const filterRevenueAndExpenseBtn = $("#filterRevenueAndExpenseBtn").click(() => {
        console.log("How are you");
    });