// ----- GLOBAL VARIABLES ------------- 
var charts_of_account_details;
const alert = $("#alert");
let isDebitValue;

// ------ END OF GLOBAL VARIABLES -------

// ---- VARIABLES TO BE USED BY THE CHART. ---
const ctx = document.getElementById('revenue_expense_chartjs');
let myChart;
var days = []; // array that holds the charts label.
var balances = []; // array that holds the chart datasets data.
const backgroundColors = ['rgba(13, 135, 1, 0.2)', 'rgba(235, 52, 52, 0.2)'];
// -----------END OF CHART VARIABLES------------------------



// -------- Function to extract the ID parameter from the URL
const getURLParameter = () => {
    const queryString = window.location.search; // points to the url and store the value in a constiable
    const urlParams = new URLSearchParams(queryString); // the url is passed as an argurment to the search
    return urlParams;
}

const dataTables = $("#charts_view_datatables").DataTable({
    columns: [{
            data: 'entry_date',
            width: "5%"
        },
        {
            data: {
                'vehicle_name': 'vehicle_name',
                'company_name': 'company_name'
            },
            width: "30%",
            render: ((data) => {
                return `${data.vehicle_name} ~(${data.company_name})`
            })
        }, {
            data: 'registration_number',
            width: "5%",
            render: ((data) => {
                return `<a href="${data}">${data}</a>`
            })
        }, {
            data: 'category_name',
            width: "10%",
        }, {
            data: 'balance',
            width: "15%",
        }
    ]
});

// -----Start of sessionStorage------------------------------------------------
const setIdToSessionStorage = () => {
    sessionStorage.setItem('charts_of_accounts_id', getURLParameter().get("cid"));
}
setIdToSessionStorage();
const charts_of_accounts_id = sessionStorage.getItem('charts_of_accounts_id');
// ----End of sessionStorage--------------------------------------------

const populateDomWithDetails = () => {
    $("#page_header").html(`${charts_of_account_details.revenue_name}`);
    $("#page_title").html(`${charts_of_account_details.revenue_name} ~ ${charts_of_account_details.acc_name}`);;
    const status_of_chart_of_account = $("#status_of_chart_of_account");
    $("#type_of_chart_of_account").html(`Account Type: ${charts_of_account_details.acc_name}`)
    $("#date_of_creation").html(`Date of Creation: ${charts_of_account_details.creation_date}`);
    checkIfChartOfAccountIsActive(status_of_chart_of_account);
    $("#acc_desc").html(`Account Desc: ${charts_of_account_details.revenue_description}`);

    //  using the strict equality operator to check for null in the max and min chart amount.
    if (charts_of_account_details.min_amount === null || charts_of_account_details.max_amount === null) {
        $("#cards_section").html(
            `<div class="alert alert-danger d-flex align-items-center" role="alert">
                   <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                   <div>
                   This account maximum and minimum value of transcations has not been set. Click <a href="#">here</a> to set 
                   min and max transcation values for your account.
               </div>
           </div>`
        )
    } else {

        $("#cards_section").html(
            `<div class="row gx-5">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Minimum Value Transcation
                        </div>
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">Minimum value for every
                                transcation
                                made in this account.
                            </h6>
                            <h3 class="card-text">${charts_of_account_details.min_amount}</h3>
                            <a href="#" class="card-link">Edit this value</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Maximum Account Value Transcation
                        </div>
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">Maximum value for every
                                transcation
                                made in this account.
                            </h6>
                            <h3 class="card-text">${charts_of_account_details.max_amount}</h3>
                            <a href="#" class="card-link">Edit this value</a>
                        </div>
                    </div>
                </div>
            </div>`
        )
    }
}

// -----------------------------------------------------------------------------
//  set sessionStorage for the isDebited to the value of the debit account. 
async function setParametersToSessionStorage() {
    await sessionStorage.setItem('isDebited', charts_of_account_details.debit);
}

//-------------------------------------------------------------------------

const checkIfChartOfAccountIsActive = (status_of_chart_of_account) => {
    if (charts_of_account_details.isActive == 1) {
        status_of_chart_of_account.html(`<span class="badge rounded-pill text-bg-success">Active</span>`);
        alert.html(`
                  <div class="alert alert-primary d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                      <div>
                          This page contains details of the chart of account, in addition to its balances. The chart below shows 
                          the balances of the account over a period of time. Hover above the see more details.
                      </div>
                  </div>
                  `);
    } else {
        status_of_chart_of_account.html(`<span class="badge rounded-pill text-bg-danger">InActive</span>`);

        alert.html(`
                  <div class="alert alert-danger d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                      <div>
                          This vehicle is InActive. You need to consult the administrator to activate the vehicle.
                      </div>
                  </div>
                  `);
    }
}

// async function to get the details of the vehicle. 
async function fetchChartsOfAccountsDetails(charts_of_account) {
    let url = `./page_queries/getTheDetailsOfChartsOfAccounts?charts_of_accounts_id=${charts_of_account}`;
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

//  invocation of the async function to fetchChartsOfAccountsDetails()
fetchChartsOfAccountsDetails(charts_of_accounts_id).then((data) => {

    // assigning the value of data to the object charts_of_accounts details
    charts_of_account_details = data;

    //  setting the value to be placed on the sessionStorage.
    setParametersToSessionStorage().then(() => {
        isDebitValue = sessionStorage.getItem('isDebited');

        getBalancesValuesForChartJs(isDebitValue).then((data) => {

            for (let i = 0; i < data.length; i++) {
                let ds_items = data[i];
                days.push(ds_items.entry_date);
                balances.push(ds_items.balance);
            }
            updateChart(balances, days, isDebitValue, backgroundColors);
        })

        createDataTableForAccounts();

    })
    //  invocation of the populateDOMwithDetails() function
    populateDomWithDetails();
})

function createDataTableForAccounts() {
    $.ajax({
        url: "./page_queries/getTheBalancesOfTheChartsOfAccountsToPopulateTheDataTables",
        type: "GET",
        data: {
            charts_of_accounts_id: charts_of_accounts_id,
            isDebitValue: isDebitValue
        },
        dataSrc: "",
    }).done((data) => {
        const response = JSON.parse(data);
        dataTables.rows.add(response).draw();
    })
}

// --------------- CHART JS -------------------------------------------------

async function getBalancesValuesForChartJs(debit) {

    const url = `./page_queries/getTheBalancesOfTheChartsOfAccountsToPopulateChartjs?charts_of_accounts_id=${charts_of_accounts_id}&debit=${debit}`;

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

myChart = new Chart(ctx, {
    type: 'line',
});

function updateChart(data, labels, debitValue, color) {

    let colorToUse = {};

    if (debitValue === "0") {
        colorToUse.color = color[0]
        colorToUse.borderColor = "green"
    } else {
        colorToUse.color = color[1]
        colorToUse.borderColor = "red"
    }

    myChart.data.datasets[0] = {
        data: data,
        label: charts_of_account_details.revenue_name,
        borderColor: colorToUse.borderColor,
        backgroundColor: colorToUse.color,
        fill: true,
        lineTension: 0.4,
        pointRadius: 3,
        pointBackgroundColor: 'rgba(0, 123, 255, 1)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2, // Border width of data points
        cubicInterpolationMode: 'monotone',
        tension: 0.4
    }
    myChart.data.labels = labels
    myChart.options = {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Revenue Overview for all the vehicles over a period of time.'
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
                    text: 'Revenue Value'
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
    }
    myChart.update();
}
// ----------------- END OF CHART JS ----------------------------------------


setInterval(function () {
    dataTables.draw();
}, 10000);