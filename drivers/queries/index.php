<?php

require_once __DIR__ . '/../../fxn/config.php';

// define("CON", $dbh);

function fetchAllTheDrivers()
{
    global $dbh;

    $stmt = "SELECT vehicle_driver_id, drivers_license_no, first_name, second_name, 
                last_name, phone_number, isActive, created_at 
             FROM vehicle_drivers";

    $query = $dbh->prepare($stmt);
    $query->execute();

    $drivers = $query->fetchAll(PDO::FETCH_ASSOC);
    return $drivers;
}

function toInsertDriver($first_name, $second_name, $last_name, $phone_number, $drivers_license_no)
{
    global $dbh;

    $data = [];
    $error = [];

    if (empty($first_name) || empty($second_name) || empty($last_name) || empty($phone_number) || empty($drivers_license_no)) {
        $error['input'] = "Invalid Inputs Provided";
    }

    if (!empty($error)) {
        $data['success'] = false;
        $data['message'] = $error;
    } else {

        $vehicle_driver_id = bin2hex(random_bytes(30));

        $stmt = "INSERT INTO `vehicle_drivers`
                 (`vehicle_driver_id`, `drivers_license_no`, `first_name`, `second_name`, `last_name`, `phone_number`, `created_at`) 
                 VALUES (:vehicle_id, :drivers_license_no, :first_name, :second_name, :last_name, :phone_number, NOW())";

        $query = $dbh->prepare($stmt);

        $query->bindParam(":vehicle_id", $vehicle_driver_id, PDO::PARAM_STR);
        $query->bindParam(":drivers_license_no", $drivers_license_no, PDO::PARAM_STR);
        $query->bindParam(":first_name", $first_name, PDO::PARAM_STR);
        $query->bindParam(":second_name", $second_name, PDO::PARAM_STR);
        $query->bindParam(":last_name", $last_name, PDO::PARAM_STR);
        $query->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);

        $query->execute();
        $errorInfo = $query->errorInfo();

        if ($query->rowCount() > 0) {
            $data['success'] = true;
            $data['message'] = "Vehicle Driver added succesfully";
        } else {
            $data['success'] = false;
            $data['message'] = $errorInfo[2];
        }
    }

    return $data;
}

// CONDUCTOR SECTION. 

function fetchAllTheConductorsForDataTables()
{
    global $dbh;

    $stmt = "SELECT `conductor_id`, `first_name`, `second_name`, `last_name`, 
            `phone_number`, `isActive`, `created_at` 
            FROM `conductors`";

    $query = $dbh->prepare($stmt);
    $query->execute();

    $conductors = $query->fetchAll(PDO::FETCH_ASSOC);
    return $conductors;
}

function toInsertConductor($first_name, $second_name, $last_name, $phone_number)
{
    global $dbh;

    $data = [];
    $error = [];

    if (empty($first_name) || empty($second_name) || empty($last_name) || empty($phone_number)) {
        $error['input'] = "Invalid Inputs Provided";
    }

    if (!empty($error)) {
        $data['success'] = false;
        $data['message'] = $error;
    } else {
        $conductor_id = bin2hex(random_bytes(30));

        $stmt = "INSERT INTO `conductors`
                (`conductor_id`, `first_name`, `second_name`, `last_name`, `phone_number`, `created_at`) 
                VALUES 
                (:conductor_id, :first_name, :second_name, :last_name, :phone_number, NOW())";

        $query = $dbh->prepare($stmt);

        $query->bindParam(":conductor_id", $conductor_id, PDO::PARAM_STR);
        $query->bindParam(":first_name", $first_name, PDO::PARAM_STR);
        $query->bindParam(":second_name", $second_name, PDO::PARAM_STR);
        $query->bindParam(":last_name", $last_name, PDO::PARAM_STR);
        $query->bindParam(":phone_number", $phone_number, PDO::PARAM_STR);

        $query->execute();
        $errorInfo = $query->errorInfo();

        if ($query->rowCount() > 0) {
            $data['success'] = true;
            $data['message'] = "Conductor added succesfully";
        } else {
            $data['success'] = false;
            $data['message'] = $errorInfo[2];
        }
    }
    return $data;

}