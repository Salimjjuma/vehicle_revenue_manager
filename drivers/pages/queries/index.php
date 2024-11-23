<?php

require_once __DIR__ . '/../../../fxn/config.php';

//define("CON", $dbh);


function toGetDriverDetails($driverId)
{
    global $dbh;

    $stmt = "SELECT `drivers_license_no`, `first_name`, `second_name`, 
            `last_name`, `phone_number`, `isActive`, `created_at` 
            FROM `vehicle_drivers`
            WHERE vehicle_driver_id=:vehicle_driver_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":vehicle_driver_id", $driverId, PDO::PARAM_STR);
    $query->execute();

    $driverDetails = $query->fetch(PDO::FETCH_OBJ);

    return $driverDetails;
}

function toUpdateDriverDetails($name, $phone)
{

}

function toDeleteDriver($driverId)
{
    global $dbh;
    $stmt = "DELETE FROM `vehicle_drivers` 
             WHERE `vehicle_driver_id` =:vehicle_driver_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":vehicle_driver_id", $driverId, PDO::PARAM_STR);

    $query->execute();

    $errorInfo = $query->errorInfo();

    if ($query->rowCount() > 0) {
        $data['success'] = true;
        $data['message'] = "Vehicle driver assignment deleted succesfully";
    } else {
        $data['success'] = true;
        $data['message'] = $errorInfo[2];
    }
}


// what am i going to show on the vehicle driver table. 
function getVehicleAssignedToDriver($driverId)
{
    global $dbh;

    $stmt = "SELECT leaseStartDate, leaseExpiryDate, v.vehicle_name, v.registration_number, 
            CAST(vdc.created_at AS DATE) as created_at, 
            CONCAT(c.first_name, ' ', c.second_name, ' ', c.last_name) AS conductor
            FROM `vehicle_driver_conductors` vdc 
                LEFT JOIN vehicle_drivers vd 
                ON vdc.driver_id = vd.vehicle_driver_id 
                LEFT JOIN vehicles v 
                ON vdc.vehicle_id = v.vehicle_id 
                LEFT JOIN conductors c
                ON vdc.conductor_id = c.conductor_id 
             WHERE vdc.driver_id =:driver_id";

    $query = $dbh->prepare($stmt);
    $query->bindParam(":driver_id", $driverId, PDO::PARAM_STR);

    $query->execute();

    $vehicleAssigned = $query->fetchAll(PDO::FETCH_OBJ);

    return $vehicleAssigned;
}