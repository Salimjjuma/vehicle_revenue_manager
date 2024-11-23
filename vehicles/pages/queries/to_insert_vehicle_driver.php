<?php

include __DIR__ . "/index.php";

$leaseStartDate = htmlentities($_POST['leaseStartDate']);
$vehicle_conductor_select = htmlentities($_POST['vehicle_conductor_select']);
$vehicle_driver_select = htmlentities($_POST['vehicle_driver_select']);
$vehicle_id = htmlentities($_POST['vehicle_id']);

echo json_encode(to_insert_vehicle_driver($leaseStartDate, $vehicle_conductor_select, $vehicle_driver_select, $vehicle_id));
