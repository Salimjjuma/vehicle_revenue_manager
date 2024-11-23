<?php

require_once __DIR__ . "/index.php";

$vehicle_driver_conductors_id_from_table = htmlentities($_POST['vehicle_driver_conductors_id_from_table']);
$email = htmlentities($_POST['email']);
$password = htmlentities($_POST['password']);

echo json_encode(stopLeaseQuery($vehicle_driver_conductors_id_from_table, $email, $password));
