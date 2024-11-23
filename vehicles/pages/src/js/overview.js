// Function to extract the ID parameter from the URL
getURLParameter = () => {
    const queryString = window.location.search; // points to the url and store the value in a constiable
    const urlParams = new URLSearchParams(queryString); // the url is passed as an argurment to the search
    return urlParams;
}

// function to store the data in the sessionStorage. 
setIdToSessionStorage = () => {
    sessionStorage.setItem('date', getURLParameter().get("date"));
}

// Invocation of the setIdToSessionStorage function. 
setIdToSessionStorage();

// getting the vehicle_Id and the date values from the sessionStorage. 
const vehicle_id = sessionStorage.getItem('vehicle_Id'),
    date = sessionStorage.getItem('date');

// Initialize the myPieChart and myBarChart and floating_panels elements. 
const ctx = document.getElementById('myPieChart').getContext('2d'),
    ctp = document.getElementById('myBarChart').getContext('2d'),
    floating_panels = $("#floating_panels");

// the argurment to be passed when making a request using fetch.
const url_args = "vehicle_id",
    query_path = "./queries/";

async function vehicleInit() {
    let vehicle_url = `${query_path}to_get_details_of_vehicle?${url_args}=${vehicle_id}`;
    try {

        const response = await fetch(vehicle_url);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data', error);
    }
}

let vehicle_obj = [];

vehicleInit().then((data) => {
    vehicle_obj = data;

    populateDomWithFetchedData();
});

function populateDomWithFetchedData() {
    $("#page_header").html(`${vehicle_obj.vehicle_name} ~ (${vehicle_obj.registration_number})`);
    $('[name="date"]').html(date);
    $("#category_of_vehicle").html(`<span class="text-dark">Vehicle Category: </span>${vehicle_obj.category_name}`);
    $("#company_name").html(`<span class="text-dark">Vehicle Brand: </span>${vehicle_obj.company_name}`);
    $("#page_title").html(`${date} || ${vehicle_obj.vehicle_name} Daily Revenue and Expense Overview`);
}

// ajax fetch function to get the revenue and expense of a car
// argurments used are date & vehicle_id.
async function getVehiclesRevenueAndExpenses() {
    let revenue_expense_url = `${query_path}to_get_revenue_of_a_vehicle?${url_args}=${vehicle_id}&date=${date}`;
    try {
        const response = await fetch(revenue_expense_url);
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        const data = await response.json(); // If your API returns JSON data
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }

}

// vehicle object that carries the expenses and the revenues. 
let vehicle_details = [],
    daily_revenue_table;

// once the ajax fetch async function is through, 
// then declare the vehicle details object. 
getVehiclesRevenueAndExpenses()
    .then((data) => {

        // initialize the vehicle. 
        vehicle_details = data;
        initializeAndPopulateChart();
        initializeTable();

    }).then(() => {
        // Add a click event listener to the table body.
        daily_revenue_table.on('click', 'tr', function () {
            // when a user clicks on a row, all the value of the row to be assigned in an object. 
            var clickedRow = daily_revenue_table.row(this).data();

            $("#edit_transcation").modal("show");

            let id = $("#id_of_revenue_xpense").val(clickedRow.id),
                debit = $("#debit_or_credit_flag").val(clickedRow.debit),
                new_amount = $("#new_amount").val(clickedRow.amount)
        });
    }).catch((error) => {
        console.error(error);
    });

// function to initialize both the chart bar and pie. 
async function initializeAndPopulateChart() {
    try {

        // constants used in the chart to populate the data and label. 
        const {
            labels,
            data
        } = pushItemsToDataAndLabelObjects();

        // colors used in the bar and the pie charts
        const backgroundColors = ['green', 'purple'];

        // Invocation of the addFloatingPanelsToTheDIV function.
        addFloatingPanelsToTheDIV();

        // fxn to invoce and create the pie chart
        functionToCreatePieChart(labels, data, backgroundColors);

        // fxn to invoce and create the bar chart
        functionToCreateBarChart(labels, data, backgroundColors);

    } catch (error) {
        console.error('Error initializing object:', error);
    }
}

// declaration of pushItemsToDataAndLabelObjects fxn
function pushItemsToDataAndLabelObjects() {
    const labels = [],
        data = [];

    vehicle_details.forEach(item => {
        labels.push(item.chart_name);
        data.push(item.amount);
    });
    return {
        labels,
        data
    };
}

// declaration of the addFloatingPanelsToTheDIV function.
function addFloatingPanelsToTheDIV() {
    // loop through the revenue and expenses fetched and stored in the object
    // on every iteration we append the name of the account and the balance
    for (i = 0; i < vehicle_details.length; i++) {
        floating_panels.append(`
                    <div class="col-md-4 col-xl-4 mb-1">
                        <div class="card">
                            <div class="card-body">
                                <div id="revenue_card" class="text-primary mb-1">
                                    ${vehicle_details[i]['chart_name']}
                                </div>
                                <div class="h3" id="revenue_value">
                                    Ksh ${vehicle_details[i]['amount']}
                                </div>
                            </div>
                        </div> 
                    </div>
            `);
    }
}

// declaration of the fxn to invoce and create the bar chart
// using the argurments passed to the fxn.
function functionToCreateBarChart(labels, data, backgroundColors) {
    return new Chart(ctp, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                label: `${vehicle_obj.vehicle_name} - Revenue against Expense on (${date})`,
                borderWidth: 0,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
}

// declaration of the fxn to invoce and create the pie chart 
// using the argurment passed to the fxn.
function functionToCreatePieChart(labels, data, backgroundColors) {
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top'
                },
            }
        }
    });
}

function initializeTable() {
    daily_revenue_table = $("#daily_revenue_table").DataTable({
        data: vehicle_details,
        columns: [{
                data: "chart_name"
            },
            {
                data: "type"
            },
            {
                data: {
                    "amount": "amount",
                    "type": "type",
                },
                render: ((data) => {
                    if (data.type == "Revenue") {
                        return `<span class="text-success">${data.amount}</span>`
                    } else {
                        return `<span class="text-danger">${data.amount}</span>`
                    }
                })
            },
            {
                data: "entry_date"
            }
        ]

    })
};

$("#print_daily_revenue_report").click(() => {
    window.open(
        `/../../reports/vehicles/reports/daily_vehicle_report?date=${date}&vid=${vehicle_id}`
    );
});

const selectProperties = {
    theme: 'bootstrap-5',
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
};

$("#charts_of_account").select2({
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

const url_for_editing = "./queries/to_edit_a_transcation",
    edit_transcation_ = $("#edit_transcation_").validate({
        rules: {
            debit_or_credit_flag: "required",
            charts_of_account: "required",
            new_amount: {
                required: true,
                number: true
            },
            id_of_revenue_xpense: "required"
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
                url: url_for_editing,
                type: "POST",
                data: $(form).serialize(),
            }).done((response) => {
                const r = JSON.parse(response);
                $("#edit_transcation").modal("hide");

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
                            $("#edit_transcation_").each(function () {
                                this.reset();
                            });
                            getVehiclesRevenueAndExpenses();
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
    })