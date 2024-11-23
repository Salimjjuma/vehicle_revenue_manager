const dashboard_header = $("#dashboard_header").html("Home Page.");
const ctx = document.getElementById('revenueChart');

var days = []; // array that holds the charts label.
var revenue = []; // array that holds the chart datasets data.
var expense = [];
const backgroundColors = ['rgba(0, 0, 235, 0.5)', 'rgba(235, 0, 0, 1)'];
var bestPerformingVehicleProperties, worstPerformingVehicleProperties;

async function fetchOveralRevenueAndExpenses() {
    let url = `./fxn/queries/dashboardQueries/overrallVehiclePerformance`;
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

fetchOveralRevenueAndExpenses()
    .then((data) => {
        for (let i = 0; i < data.length; i++) {
            let ds_items = data[i];
            days.push(ds_items.entry_date)
            revenue.push(ds_items.total_revenue)
            expense.push(ds_items.total_expense)
        }
    }).then(() => {
        setDataToChartJs();
    })

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
        plugins: {
            title: {
                display: true,
                text: 'Revenue & Expense Overview for all the vehicles over a period of time.'
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

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
}

// get the best performing vehicle. 
function getBestPerformingVehicle() {
    $.ajax({
        url: "./fxn/queries/dashboardQueries/bestPerformingVehicle",
        type: "GET",
        data: {
            "startDate": "",
            "endDate": ""
        }
    }).done((response) => {
        const data = JSON.parse(response);

        bestPerformingVehicleProperties = data;
        setBestPerformingVehicle();
    })
}

getBestPerformingVehicle();

function setBestPerformingVehicle() {
    $("#bestVehicleValue").html(bestPerformingVehicleProperties.maxTotalRevenue);
    $("#bestVehicleName").html(`${bestPerformingVehicleProperties.vehicle_name} ~ (${bestPerformingVehicleProperties.regNumber})`);
}

function getWorstPerformingVehicle() {
    $.ajax({
        url: "./fxn/queries/dashboardQueries/getWorstPerformingVehicle",
        type: "GET",
        data: {
            "startDate": "",
            "endDate": ""
        }
    }).done((response) => {
        const data = JSON.parse(response);

        worstPerformingVehicleProperties = data;
        setWorstPerformingVehicle();
    })
}

getWorstPerformingVehicle()

function setWorstPerformingVehicle() {
    $("#worstPerformingVehicleValue").html(worstPerformingVehicleProperties.maxTotalExpense);
    $("#worstPerformingVehicleName").html(`${worstPerformingVehicleProperties.vehicle_name} ~ (${worstPerformingVehicleProperties.regNumber})`);
}

const page_title = $("#page_title").html("Vanga Fleet Manager || Dashboard");