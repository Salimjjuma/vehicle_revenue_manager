// Function to extract the ID parameter from the URL
const getURLParameter = () => {
    const queryString = window.location.search; // points to the url and store the value in a constiable
    const urlParams = new URLSearchParams(queryString); // the url is passed as an argurment to the search
    return urlParams;
}

// function to store the data in the sessionStorage. 
const setIdToSessionStorage = () => {
    sessionStorage.setItem('driver', getURLParameter().get("driver"));
}

// Invocation of the setIdToSessionStorage function. 
setIdToSessionStorage();

const driverId = sessionStorage.getItem("driver");
var vehicleObject;

async function getDriverDetails(driver) {
    const url = `./queries/toGetDriverDetails?driverId=${driver}`;
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

getDriverDetails(driverId).then((result) => {
    vehicleObject = result;
    populateDOMElements(vehicleObject);
})

const populateDOMElements = (fetchedDriverDetails) => {
    $("#page_header").html(`${fetchedDriverDetails.first_name} ${fetchedDriverDetails.second_name} ${fetchedDriverDetails.last_name}`);

    if (fetchedDriverDetails.isActive == 1) {
        $("#status_of_driver").html(`<span class="badge rounded-pill text-bg-success">Active Driver</span>`);
        $("#alert").html(`
                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div>
                            This page contains details of a driver, vehicle assigned and the conductor against the date of lease. 
                        </div>
                    </div>
                    `);
    } else {
        $("#status_of_driver").html(`<span class="badge rounded-pill text-bg-danger">InActive Driver</span>`);

        $("#alert").html(`
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            This driver is InActive. You need to consult the administrator to activate the vehicle.
                        </div>
                    </div>
                    `);
    }

    $("#drivers_license_no").html(`Drivers License No: <span class="text-dark">${fetchedDriverDetails.drivers_license_no}</span>`)
    $("#date_of_creation").html(`Date of Employment: <span class="text-dark">${fetchedDriverDetails.created_at}</span>`)
    $("#phone_number").html(`Phone Number: <span class="text-dark">${fetchedDriverDetails.phone_number}</span>`)
    $("#page_title").html(`${fetchedDriverDetails.first_name} ${fetchedDriverDetails.second_name} ${fetchedDriverDetails.last_name} - Manage Vehicle Driver`);
}

const vehicle_driver_table = $('#vehicle_driver_table').DataTable({
    ajax: {
        url: `./queries/getVehicleAssignedToDriver?driverId=${driverId}`,
        type: "GET",
        dataSrc: "",
    },
    columnDefs: [{
            targets: 0,
            data: "created_at"
        },
        {
            targets: 1,
            data: "vehicle_name",
        },
        {
            targets: 2,
            data: "registration_number",
        },
        {
            targets: 3,
            data: "leaseStartDate"
        },
        {
            targets: 4,
            data: "leaseExpiryDate",
            render: function (data) {
                if (data != null) {
                    return `${data}`
                } else {
                    return `-----------`

                }
            }
        },
        {
            targets: 5,
            orderable: false,
            data: "leaseExpiryDate",
            render: function (data) {
                if (data != null) {
                    return `<span class="badge rounded-pill text-bg-danger">Expired</span>`
                } else {
                    return `<span class="badge rounded-pill text-bg-success">Not Expired</span>`
                }
            }
        },
        {
            targets: 6,
            data: "conductor",
        }
    ]
})